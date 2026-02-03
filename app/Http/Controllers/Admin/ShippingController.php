<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingZone;
use App\Models\ShippingProvider;
use App\Models\ShippingRate;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ShippingController extends Controller
{
    public function index()
    {
        $zones = ShippingZone::with('rates.provider')->get();
        $providers = ShippingProvider::with('rates.zone')->get();

        return view('admin.shipping.index', compact('zones', 'providers'));
    }

    // --- Zones ---

    public function storeZone(Request $request)
    {
        $request->validate(['name' => 'required', 'countries' => 'required']);
        $countries = array_map('trim', explode(',', $request->countries));

        ShippingZone::create([
            'name' => $request->name,
            'countries' => $countries
        ]);

        return back()->with('success', 'Zone added.');
    }

    public function editZone(ShippingZone $zone)
    {
        return view('admin.shipping.edit_zone', compact('zone'));
    }

    public function updateZone(Request $request, ShippingZone $zone)
    {
        $request->validate(['name' => 'required', 'countries' => 'required']);
        $countries = array_map('trim', explode(',', $request->countries));

        $zone->update([
            'name' => $request->name,
            'countries' => $countries
        ]);

        return redirect()->route('admin.shipping.index')->with('success', 'Zone updated.');
    }

    public function destroyZone(ShippingZone $zone)
    {
        $zone->delete();
        return back()->with('success', 'Zone deleted.');
    }

    // --- Providers ---

    public function storeProvider(Request $request)
    {
        $request->validate(['name' => 'required']);
        ShippingProvider::create(['name' => $request->name]);
        return back()->with('success', 'Provider added.');
    }

    // --- Rates ---

    public function storeRate(Request $request)
    {
        $request->validate([
            'shipping_zone_id' => 'required',
            'shipping_provider_id' => 'required',
            'min_weight' => 'required|numeric',
            'max_weight' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        ShippingRate::create($request->all());
        return back()->with('success', 'Rate added.');
    }

    public function editRate(ShippingRate $rate)
    {
        $zones = ShippingZone::all();
        $providers = ShippingProvider::all();
        return view('admin.shipping.edit_rate', compact('rate', 'zones', 'providers'));
    }

    public function updateRate(Request $request, ShippingRate $rate)
    {
        $request->validate([
            'min_weight' => 'required|numeric',
            'max_weight' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $rate->update($request->all());
        return redirect()->route('admin.shipping.index')->with('success', 'Rate updated.');
    }

    public function destroyRate(ShippingRate $rate)
    {
        $rate->delete();
        return back()->with('success', 'Rate deleted.');
    }

    // --- Bulk Import/Export ---

    public function exportRates(Request $request): StreamedResponse
    {
        $request->validate([
            'shipping_provider_id' => 'required|exists:shipping_providers,id',
        ]);

        $provider = ShippingProvider::with('rates.zone')->findOrFail($request->shipping_provider_id);
        $zones = ShippingZone::orderBy('id')->get();
        $rates = ShippingRate::where('shipping_provider_id', $provider->id)->get();

        $rateMap = [];
        foreach ($rates as $rate) {
            $key = $rate->min_weight . '|' . $rate->max_weight;
            if (!isset($rateMap[$key])) {
                $rateMap[$key] = [
                    'min_weight' => $rate->min_weight,
                    'max_weight' => $rate->max_weight,
                    'prices' => [],
                ];
            }
            $rateMap[$key]['prices'][$rate->shipping_zone_id] = $rate->price;
        }

        $sortedRanges = collect($rateMap)
            ->sortBy(function ($range) {
                return ($range['min_weight'] * 1000000) + $range['max_weight'];
            })
            ->values();

        $fileName = 'shipping_rates_' . Str::slug($provider->name) . '_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($zones, $sortedRanges) {
            $output = fopen('php://output', 'w');
            $header = array_merge(['min_weight', 'max_weight'], $zones->pluck('name')->toArray());
            fputcsv($output, $header);

            foreach ($sortedRanges as $range) {
                $row = [$range['min_weight'], $range['max_weight']];
                foreach ($zones as $zone) {
                    $row[] = $range['prices'][$zone->id] ?? '';
                }
                fputcsv($output, $row);
            }

            fclose($output);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }

    public function importRates(Request $request)
    {
        $request->validate([
            'shipping_provider_id' => 'required|exists:shipping_providers,id',
            'rates_file' => 'required|file|mimes:csv,txt',
        ]);

        $providerId = (int) $request->shipping_provider_id;
        $zones = ShippingZone::orderBy('id')->get();
        $zoneLookup = $zones->reduce(function ($carry, $zone) {
            $carry[strtolower(trim($zone->name))] = $zone->id;
            $carry[strtolower(str_replace(' ', '', trim($zone->name)))] = $zone->id;
            return $carry;
        }, []);
        $zoneOrder = $zones->values();

        $file = $request->file('rates_file');
        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            return back()->with('error', 'Unable to read the uploaded file.');
        }

        $header = fgetcsv($handle);
        if (!$header || count($header) < 3) {
            fclose($handle);
            return back()->with('error', 'Invalid CSV header. Expected min_weight, max_weight, and zone columns.');
        }

        $header = array_map('trim', $header);
        $zoneHeaders = array_slice($header, 2);
        $zoneIds = [];

        foreach ($zoneHeaders as $zoneHeader) {
            $normalized = strtolower(trim($zoneHeader));
            $normalizedNoSpace = strtolower(str_replace(' ', '', $normalized));

            if (isset($zoneLookup[$normalized])) {
                $zoneIds[] = $zoneLookup[$normalized];
                continue;
            }
            if (isset($zoneLookup[$normalizedNoSpace])) {
                $zoneIds[] = $zoneLookup[$normalizedNoSpace];
                continue;
            }

            if (is_numeric($normalized)) {
                $index = (int) $normalized - 1;
                if ($zoneOrder->has($index)) {
                    $zoneIds[] = $zoneOrder->get($index)->id;
                    continue;
                }
            }

            fclose($handle);
            return back()->with('error', 'Unknown zone in CSV header: ' . $zoneHeader);
        }

        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 2) {
                continue;
            }

            $minWeight = trim($row[0]);
            $maxWeight = trim($row[1]);

            if ($minWeight === '' || $maxWeight === '') {
                continue;
            }

            $minWeight = (float) $minWeight;
            $maxWeight = (float) $maxWeight;

            foreach ($zoneIds as $index => $zoneId) {
                $priceIndex = $index + 2;
                if (!isset($row[$priceIndex]) || trim($row[$priceIndex]) === '') {
                    continue;
                }

                $price = (float) $row[$priceIndex];

                ShippingRate::updateOrCreate(
                    [
                        'shipping_provider_id' => $providerId,
                        'shipping_zone_id' => $zoneId,
                        'min_weight' => $minWeight,
                        'max_weight' => $maxWeight,
                    ],
                    ['price' => $price]
                );

                $imported++;
            }
        }

        fclose($handle);

        return back()->with('success', 'Bulk import completed. ' . $imported . ' rates processed.');
    }
}
