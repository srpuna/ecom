<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingZone;
use App\Models\ShippingProvider;
use App\Models\ShippingRate;

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
}
