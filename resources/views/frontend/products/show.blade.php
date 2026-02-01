@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-12">

        <div class="flex flex-col md:flex-row gap-12 bg-white p-8 rounded-2xl shadow-sm">
            <!-- Image Section -->
            <div class="md:w-1/2">
                <!-- Main Image Display -->
                <!-- Main Image Display -->
                <div class="relative z-10">
                    <div id="imageContainer" class="rounded-xl overflow-hidden mb-4 bg-gray-50 h-[50vh] md:h-[90vh] flex items-center justify-center relative cursor-crosshair">
                        @if($product->main_image)
                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}"
                                class="max-h-full max-w-full object-contain" id="mainProductImage">
                        @else
                            <span class="text-gray-400 text-lg">No Image</span>
                        @endif
                    </div>
                
                    <!-- Zoom Result Container (Side View) -->
                    <div id="zoomResult" class="hidden fixed md:absolute left-0 md:left-[105%] top-0 md:top-0 w-full md:w-[500px] h-[500px] bg-white border border-gray-200 shadow-2xl z-50 rounded-lg overflow-hidden"></div>
                </div>

                <script>
                    const container = document.getElementById('imageContainer');
                    const img = document.getElementById('mainProductImage');
                    const result = document.getElementById('zoomResult');

                    if (container && img && result) {
                        container.addEventListener('mousemove', (e) => {
                            const rect = container.getBoundingClientRect();
                            const x = e.clientX - rect.left;
                            const y = e.clientY - rect.top;
                            
                            // Check if cursor is within the container
                            if (x < 0 || x > rect.width || y < 0 || y > rect.height) {
                                result.classList.add('hidden');
                                return;
                            }

                            // Show result
                            result.classList.remove('hidden');
                            
                            // Set background image
                            result.style.backgroundImage = `url('${img.src}')`;
                            
                            // Calculate zoom ratios. 
                            // We want '100%' zoom or magnified. Let's assume 2x magnification.
                            // If we want "native" size, we would check img.naturalWidth.
                            // But consistency suggests a fixed multiple.
                            
                            const cx = result.offsetWidth / result.offsetWidth * 2.5; // Zoom factor
                            const cy = result.offsetHeight / result.offsetHeight * 2.5;

                            result.style.backgroundSize = (rect.width * cx) + "px " + (rect.height * cy) + "px";
                            
                            // Move background
                            // Formula: -(x * cx - resultWidth / 2) ? No, standard lens formula
                            // bgPosX = (x / width * 100)%
                            // But for exact pixel match:
                            
                            const fx = (x / rect.width) * 100;
                            const fy = (y / rect.height) * 100;
                            
                            result.style.backgroundPosition = `${fx}% ${fy}%`;
                        });

                        container.addEventListener('mouseleave', () => {
                            result.classList.add('hidden');
                        });
                    }
                </script>
                
                <!-- Horizontal Scrollable Thumbnails -->
                @if($product->images && count($product->images) > 0)
                    <div class="relative">
                        <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100">
                            <!-- Main image thumbnail -->
                            @if($product->main_image)
                                <img src="{{ $product->main_image }}" 
                                    onclick="document.getElementById('mainProductImage').src='{{ $product->main_image }}'"
                                    class="h-20 w-20 object-cover rounded-lg border-2 border-green-premium cursor-pointer hover:opacity-75 transition flex-shrink-0"
                                    alt="Main">
                            @endif
                            <!-- Additional images -->
                            @foreach($product->images as $image)
                                <img src="{{ $image }}" 
                                    onclick="document.getElementById('mainProductImage').src='{{ $image }}'"
                                    class="h-20 w-20 object-cover rounded-lg border-2 border-gray-200 cursor-pointer hover:border-green-premium hover:opacity-75 transition flex-shrink-0"
                                    alt="Product image">
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Details Section -->
            <div class="md:w-1/2">
                <div class="mb-4">
                    <span
                        class="text-gray-500 uppercase tracking-widest text-sm font-semibold">{{ $product->category->name ?? 'Uncategorized' }}</span>
                </div>

                <h1 class="text-4xl font-serif font-bold text-gray-900 mb-4">{{ $product->name }}</h1>

                <div class="text-3xl font-bold text-green-premium mb-6">
                    @if($product->discount_price)
                        <span class="text-gray-400 line-through text-xl mr-2">${{ number_format($product->price, 2) }}</span>
                        <span>${{ number_format($product->discount_price, 2) }}</span>
                    @else
                        <span>${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                @if($product->description)
                    <div class="prose text-gray-600 mb-8 max-w-none">
                        <p>{{ $product->description }}</p>
                    </div>
                @endif

                <div class="bg-gray-50 border border-gray-100 rounded-lg p-6 mb-8">
                    <h3 class="font-serif font-bold text-lg mb-4 text-gray-800">Product Specifications</h3>
                    <dl class="grid grid-cols-2 gap-x-4 gap-y-4 text-sm">
                        <dt class="text-gray-500">Dimensions (L x W x H)</dt>
                        <dd class="font-medium text-gray-900">{{ $product->formatted_length }}cm x {{ $product->formatted_width }}cm x
                            {{ $product->formatted_height }}cm</dd>

                        <dt class="text-gray-500">Weight</dt>
                        <dd class="font-medium text-gray-900">{{ $product->formatted_weight }} kg</dd>

                        <dt class="text-gray-500">Material</dt>
                        <dd class="font-medium text-gray-900">{{ $product->material ?? 'N/A' }}</dd>

                        <dt class="text-gray-500">SKU</dt>
                        <dd class="font-medium text-gray-900">{{ $product->sku ?? 'N/A' }}</dd>
                    </dl>
                </div>

                <div class="flex flex-col gap-4">
                    {{-- Order Now Button --}}
                    @if($product->is_order_now_enabled)
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <div class="flex items-center gap-4 mb-4">
                                <label class="font-medium text-gray-700">Quantity:</label>
                                <input type="number" name="quantity" value="{{ $product->min_quantity }}"
                                    min="{{ $product->min_quantity }}" class="w-20 border rounded p-2 text-center">
                            </div>
                            <button type="submit"
                                class="w-full bg-green-premium text-white text-lg font-bold py-4 rounded-full shadow-lg hover:bg-green-800 transition transform hover:-translate-y-1">
                                Add to Cart
                            </button>
                            <p class="text-xs text-center text-gray-500 mt-2">Free shipping calculation at checkout.</p>
                        </form>
                    @endif

                    {{-- Inquiry Button / Form Toggle --}}
                    <div x-data="{ open: false }" class="mt-4">
                        <button @click="open = !open"
                            class="w-full bg-cream border-2 border-green-premium text-green-premium font-bold py-3 rounded-full hover:bg-green-premium hover:text-white transition">
                            Make an Inquiry
                        </button>

                        <div x-show="open" class="mt-6 border-t pt-6" x-transition>
                            <h3 class="font-serif font-bold text-xl mb-4 text-center">Interested? Send us an inquiry</h3>
                            <form action="{{ route('inquiry.store', $product) }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <input type="text" name="name" placeholder="Full Name" required
                                        class="w-full p-3 bg-gray-50 border rounded-lg focus:ring-green-premium focus:border-green-premium">
                                    <input type="email" name="email" placeholder="Email Address" required
                                        class="w-full p-3 bg-gray-50 border rounded-lg focus:ring-green-premium focus:border-green-premium">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <input type="text" name="phone" placeholder="Phone Number" required
                                        class="w-full p-3 bg-gray-50 border rounded-lg focus:ring-green-premium focus:border-green-premium">
                                    <input type="text" name="country" placeholder="Country" required
                                        class="w-full p-3 bg-gray-50 border rounded-lg focus:ring-green-premium focus:border-green-premium">
                                </div>
                                <input type="text" name="address_line" placeholder="Address Line" required
                                    class="w-full p-3 bg-gray-50 border rounded-lg focus:ring-green-premium focus:border-green-premium">
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" name="city" placeholder="City"
                                        class="w-full p-3 bg-gray-50 border rounded-lg focus:ring-green-premium focus:border-green-premium">
                                    <input type="text" name="zip_code" placeholder="Zip Code"
                                        class="w-full p-3 bg-gray-50 border rounded-lg focus:ring-green-premium focus:border-green-premium">
                                </div>
                                <textarea name="message" rows="3" placeholder="I am interested in this product..."
                                    class="w-full p-3 bg-gray-50 border rounded-lg focus:ring-green-premium focus:border-green-premium"></textarea>

                                <button type="submit"
                                    class="w-full bg-gray-800 text-white font-bold py-3 rounded-lg hover:bg-black transition">Send
                                    Inquiry</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Long Description Section -->
        @if($product->long_description)
            <div class="mt-12 bg-white p-8 rounded-2xl shadow-sm">
                <h2 class="text-3xl font-serif font-bold text-gray-900 mb-6 border-b pb-4">Product Details</h2>
                <div class="prose prose-lg max-w-none text-gray-700">
                    {!! nl2br(e($product->long_description)) !!}
                </div>
            </div>
        @endif

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-20">
                <h2 class="text-3xl font-serif font-bold text-gray-900 mb-8 border-b pb-4">You May Also Like</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedProducts as $relProduct)
                        <!-- Simple Card -->
                        <a href="{{ route('products.show', $relProduct->slug ?? $relProduct->id) }}" class="group block">
                            <div class="bg-gray-100 aspect-[3/4] rounded-lg overflow-hidden mb-4">
                                <img src="{{ $relProduct->main_image }}" alt="" class="w-full h-full object-cover">
                            </div>
                            <h3 class="font-bold text-lg group-hover:text-green-premium">{{ $relProduct->name }}</h3>
                            <div class="text-green-premium font-bold">
                                @if($relProduct->discount_price)
                                    <span class="text-gray-400 line-through text-sm mr-2">${{ number_format($relProduct->price, 2) }}</span>
                                    <span>${{ number_format($relProduct->discount_price, 2) }}</span>
                                @else
                                    <span>${{ number_format($relProduct->price, 2) }}</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
    {{-- Alpine JS for interaction --}}
    <script src="//unpkg.com/alpinejs" defer></script>
@endsection