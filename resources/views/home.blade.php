@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-12">
        <div class="flex flex-col md:flex-row gap-12">

            <!-- Sidebar Filter -->
            <aside class="w-full md:w-1/4">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 sticky top-24">
                    <h3 class="text-xl font-serif mb-6 text-gray-800">Categories</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('home') }}"
                                class="flex justify-between items-center text-gray-600 hover:text-green-premium transition group">
                                <span class="{{ !request('category') ? 'font-bold text-green-premium' : '' }}">All
                                    Products</span>
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('home', ['category' => $category->slug]) }}"
                                    class="flex justify-between items-center text-gray-600 hover:text-green-premium transition group">
                                    <span
                                        class="{{ request('category') == $category->slug ? 'font-bold text-green-premium' : '' }}">{{ $category->name }}</span>
                                    <span
                                        class="text-xs bg-gray-100 text-gray-500 rounded-full px-2 py-0.5 group-hover:bg-green-50 group-hover:text-green-700 transition">{{ $category->products_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>

            <!-- Product Grid -->
            <div class="flex-1">
                <div class="mb-8">
                    <h1 class="text-4xl font-serif text-gray-900 mb-2">All Products</h1>
                    <p class="text-gray-500 text-sm">Showing {{ $totalProducts }} products</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @forelse($products as $product)
                        <div
                            class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                            <!-- Image -->
                            <div class="relative h-64 overflow-hidden bg-gray-100">
                                @if($product->main_image)
                                    <img src="{{ $product->main_image }}" alt="{{ $product->name }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
                                @endif

                                <!-- Actions Overlay (Optional aesthetics) -->
                                <div
                                    class="absolute inset-x-0 bottom-0 p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex justify-center pb-6 bg-gradient-to-t from-black/50 to-transparent">
                                    <a href="{{ route('products.show', $product->slug ?? $product->id) }}"
                                        class="bg-white text-gray-900 px-6 py-2 rounded-full font-medium hover:bg-green-premium hover:text-white transition shadow-lg transform translate-y-4 group-hover:translate-y-0 duration-300">
                                        View Details
                                    </a>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6 text-center">
                                <div class="mb-2">
                                    @if($product->is_order_now_enabled)
                                        <span
                                            class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full uppercase tracking-wider font-bold">In
                                            Stock</span>
                                    @else
                                        <span
                                            class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full uppercase tracking-wider font-bold">Inquiry
                                            Only</span>
                                    @endif
                                </div>
                                <h3 class="text-lg font-serif font-bold text-gray-900 mb-2 truncate">{{ $product->name }}</h3>
                                <p class="text-green-premium font-bold text-xl">${{ number_format($product->price, 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12">
                            <p class="text-gray-500 text-lg">No products found matching your criteria.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-12">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection