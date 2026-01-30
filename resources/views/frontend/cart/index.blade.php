@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-12">
        <h1 class="text-3xl font-serif font-bold text-gray-900 mb-8">Shopping Cart</h1>

        @if(count($cartItems) > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            @if($item['product']->main_image)
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                    src="{{ $item['product']->main_image }}" alt="">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200"></div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $item['product']->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ${{ number_format($item['product']->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item['quantity'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    ${{ number_format($item['subtotal'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mt-8">
                <div class="w-full md:w-1/3 bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between mb-4">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-bold text-gray-900">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mb-6">Shipping & taxes calculated at checkout.</p>
                    <a href="{{ route('checkout') }}"
                        class="block w-full bg-green-premium text-white text-center py-3 rounded-lg font-bold hover:bg-green-800 transition">Proceed
                        to Checkout</a>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg mb-6">Your cart is empty.</p>
                <a href="{{ route('home') }}" class="text-green-premium hover:underline">Continue Shopping</a>
            </div>
        @endif
    </div>
@endsection