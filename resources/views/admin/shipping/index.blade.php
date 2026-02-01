@extends('admin.layout')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Shipping Settings
    </h2>
@endsection

@section('content')
    <div x-data="{ activeTab: 'general' }">
        
        <!-- Tabs Navigation -->
        <nav class="flex space-x-6 border-b border-gray-200 mb-8 overflow-x-auto">
            <button @click="activeTab = 'general'" 
                :class="activeTab === 'general' ? 'border-b-2 border-green-600 text-green-600 font-bold' : 'text-gray-500 hover:text-gray-700'" 
                class="pb-2 px-1 whitespace-nowrap transition-colors">
                General Settings
            </button>
            @foreach($providers as $provider)
                <button @click="activeTab = 'provider-{{ $provider->id }}'" 
                    :class="activeTab === 'provider-{{ $provider->id }}' ? 'border-b-2 border-green-600 text-green-600 font-bold' : 'text-gray-500 hover:text-gray-700'" 
                    class="pb-2 px-1 whitespace-nowrap transition-colors">
                    {{ $provider->name }}
                </button>
            @endforeach
        </nav>

        <!-- General Tab: Zones & Providers Management -->
        <div x-show="activeTab === 'general'" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Create Provider -->
                <div class="bg-white p-6 rounded-lg shadow h-fit border border-gray-100">
                    <h3 class="text-lg font-serif font-bold text-gray-800 border-b pb-2 mb-4">Add Shipping Provider</h3>
                    <form action="{{ route('admin.shipping.providers.store') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="name" placeholder="New Provider Name (e.g. DHL)" class="flex-1 p-2 border rounded" required>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Add</button>
                    </form>
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Existing Providers:</h4>
                        <ul class="list-disc pl-5 text-sm text-gray-700">
                            @foreach($providers as $provider)
                                <li>{{ $provider->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Create Zone -->
                <div class="bg-white p-6 rounded-lg shadow h-fit border border-gray-100">
                    <h3 class="text-lg font-serif font-bold text-gray-800 border-b pb-2 mb-4">Create New Zone</h3>
                    <form action="{{ route('admin.shipping.zones.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Zone Name</label>
                            <input type="text" name="name" placeholder="e.g. North America" class="w-full p-2 border rounded mt-1" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Countries (Comma Separated)</label>
                            <input type="text" name="countries" placeholder="US, CA, MX" class="w-full p-2 border rounded mt-1" required>
                        </div>
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded w-full shadow hover:bg-green-700">Create Zone</button>
                    </form>
                </div>
            </div>

            <!-- Zones List -->
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Manage Zones ({{ $zones->count() }})</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($zones as $zone)
                        <div class="p-6 flex justify-between items-center">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $zone->name }}</h4>
                                <p class="text-sm text-gray-500">{{ implode(', ', $zone->countries ?? []) }}</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('admin.shipping.zones.edit', $zone) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                                <form action="{{ route('admin.shipping.zones.destroy', $zone) }}" method="POST" onsubmit="return confirm('Delete Zone? This will delete all associated rates.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Provider Specific Tabs -->
        @foreach($providers as $provider)
            <div x-show="activeTab === 'provider-{{ $provider->id }}'" class="space-y-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-serif font-bold text-gray-900">{{ $provider->name }} Rates Management</h2>
                </div>

                @foreach($zones as $zone)
                    <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden">
                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="font-bold text-gray-800">{{ $zone->name }}</h3>
                            <span class="text-xs text-gray-500">{{ implode(', ', $zone->countries ?? []) }}</span>
                        </div>
                        
                        <div class="p-6">
                            <!-- Rates Table for this Provider & Zone -->
                            @php 
                                $providerRates = $zone->rates->where('shipping_provider_id', $provider->id); 
                            @endphp

                            @if($providerRates->count() > 0)
                                <table class="min-w-full divide-y divide-gray-200 mb-4">
                                    <thead>
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Weight Range</th>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($providerRates as $rate)
                                            <tr>
                                                <td class="px-3 py-2 text-sm text-gray-700">{{ $rate->min_weight }}kg - {{ $rate->max_weight }}kg</td>
                                                <td class="px-3 py-2 text-sm font-bold text-green-600">${{ $rate->price }}</td>
                                                <td class="px-3 py-2 text-right text-sm space-x-2">
                                                    <a href="{{ route('admin.shipping.rates.edit', $rate) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    <form action="{{ route('admin.shipping.rates.destroy', $rate) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete Rate?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="text-sm text-gray-400 italic mb-4">No rates configured for {{ $provider->name }} in this zone.</p>
                            @endif

                            <!-- Quick Add Rate -->
                            <form action="{{ route('admin.shipping.rates.store') }}" method="POST" class="bg-gray-50 p-3 rounded border border-gray-200">
                                @csrf
                                <input type="hidden" name="shipping_zone_id" value="{{ $zone->id }}">
                                <input type="hidden" name="shipping_provider_id" value="{{ $provider->id }}">
                                
                                <div class="flex gap-3 items-end">
                                    <div class="flex-1">
                                        <label class="block text-xs text-gray-500 mb-1">Min Weight (kg)</label>
                                        <input type="number" step="0.001" name="min_weight" placeholder="0" class="w-full border p-1 rounded text-sm" required>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs text-gray-500 mb-1">Max Weight (kg)</label>
                                        <input type="number" step="0.001" name="max_weight" placeholder="5" class="w-full border p-1 rounded text-sm" required>
                                    </div>
                                    <div class="flex-1">
                                        <label class="block text-xs text-gray-500 mb-1">Price ($)</label>
                                        <input type="number" step="0.01" name="price" placeholder="10.00" class="w-full border p-1 rounded text-sm" required>
                                    </div>
                                    <button type="submit" class="bg-gray-800 text-white px-3 py-1.5 rounded text-sm hover:bg-black transition">Add Rate</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection