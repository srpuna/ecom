@extends('admin.layout')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Shipping Settings
    </h2>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Providers -->
        <div class="bg-white p-6 rounded-lg shadow h-fit">
            <h3 class="text-lg font-medium border-b pb-2 mb-4">Shipping Providers</h3>
            <ul class="mb-4 list-disc pl-5">
                @foreach($providers as $provider)
                    <li>{{ $provider->name }} ({{ $provider->is_active ? 'Active' : 'Inactive' }})</li>
                @endforeach
            </ul>
            <form action="{{ route('admin.shipping.providers.store') }}" method="POST" class="flex gap-2">
                @csrf
                <input type="text" name="name" placeholder="New Provider Name (e.g. DHL)" class="flex-1 p-2 border rounded"
                    required>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Add</button>
            </form>
        </div>

        <!-- Zones CREATE -->
        <div class="bg-white p-6 rounded-lg shadow h-fit">
            <h3 class="text-lg font-medium border-b pb-2 mb-4">Create New Zone</h3>
            <form action="{{ route('admin.shipping.zones.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Zone Name</label>
                    <input type="text" name="name" placeholder="e.g. North America" class="w-full p-2 border rounded mt-1"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Countries (Comma Separated)</label>
                    <input type="text" name="countries" placeholder="US, CA, MX" class="w-full p-2 border rounded mt-1"
                        required>
                </div>
                <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded w-full shadow hover:bg-green-700">Create Zone</button>
            </form>
        </div>

    </div>

    <!-- Zones LIST & EDIT -->
    <div class="mt-8">
        <h3 class="text-xl font-bold mb-6 text-gray-800">Manage Zones & Rates</h3>
        <div class="space-y-8">
            @foreach($zones as $zone)
                <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
                    <!-- Zone Header -->
                    <div
                        class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h4 class="text-lg font-bold text-gray-900">{{ $zone->name }}</h4>
                            <p class="text-sm text-gray-500">{{ implode(', ', $zone->countries ?? []) }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('admin.shipping.zones.edit', $zone) }}"
                                class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded text-sm font-medium hover:bg-indigo-200 transition">Edit
                                Zone</a>
                            <form action="{{ route('admin.shipping.zones.destroy', $zone) }}" method="POST"
                                onsubmit="return confirm('Delete Zone? This will delete all associated rates.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-100 text-red-700 px-3 py-1 rounded text-sm font-medium hover:bg-red-200 transition">Delete</button>
                            </form>
                        </div>
                    </div>

                    <!-- Rates Content -->
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h5 class="font-medium text-gray-700">Shipping Rates</h5>
                        </div>

                        @if($zone->rates->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 mb-6">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Provider
                                            </th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Weight Range
                                            </th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($zone->rates as $rate)
                                            <tr>
                                                <td class="px-3 py-2 text-sm text-gray-900">{{ $rate->provider->name }}</td>
                                                <td class="px-3 py-2 text-sm text-gray-500">{{ $rate->min_weight }}kg -
                                                    {{ $rate->max_weight }}kg</td>
                                                <td class="px-3 py-2 text-sm font-medium text-green-600">${{ $rate->price }}</td>
                                                <td class="px-3 py-2 text-right text-sm font-medium space-x-2">
                                                    <a href="{{ route('admin.shipping.rates.edit', $rate) }}"
                                                        class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    <form action="{{ route('admin.shipping.rates.destroy', $rate) }}" method="POST"
                                                        class="inline-block" onsubmit="return confirm('Delete Rate?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-400 italic mb-6">No rates defined for this zone yet.</p>
                        @endif

                        <!-- Add Rate Form -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h6 class="text-sm font-bold text-gray-700 mb-3">Add New Rate to {{ $zone->name }}</h6>
                            <form action="{{ route('admin.shipping.rates.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="shipping_zone_id" value="{{ $zone->id }}">
                                <div class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                                    <div class="col-span-1 md:col-span-1">
                                        <label class="block text-xs text-gray-500 mb-1">Provider</label>
                                        <select name="shipping_provider_id" class="w-full border p-2 rounded text-sm" required>
                                            @foreach($providers as $p)
                                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-1 md:col-span-1">
                                        <label class="block text-xs text-gray-500 mb-1">Min Weight (kg)</label>
                                        <input type="number" step="0.001" name="min_weight" placeholder="0"
                                            class="w-full border p-2 rounded text-sm" required>
                                    </div>
                                    <div class="col-span-1 md:col-span-1">
                                        <label class="block text-xs text-gray-500 mb-1">Max Weight (kg)</label>
                                        <input type="number" step="0.001" name="max_weight" placeholder="5"
                                            class="w-full border p-2 rounded text-sm" required>
                                    </div>
                                    <div class="col-span-1 md:col-span-1">
                                        <label class="block text-xs text-gray-500 mb-1">Price ($)</label>
                                        <input type="number" step="0.01" name="price" placeholder="10.00"
                                            class="w-full border p-2 rounded text-sm" required>
                                    </div>
                                    <div class="col-span-1 md:col-span-1">
                                        <button type="submit"
                                            class="w-full bg-gray-800 text-white py-2 rounded text-sm hover:bg-black transition">Add
                                            Rate</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection