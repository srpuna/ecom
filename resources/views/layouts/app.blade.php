<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings['site_name'] }} - Premium Ecommerce</title>
    @if($siteSettings['favicon'] && $siteSettings['favicon']->value)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $siteSettings['favicon']->value) }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cream: '#FDFBF7',
                        beige: '#F5F5DC',
                        gold: '#D4AF37',
                        'green-premium': '#2E594A',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    }
                }
            }
        }
    </script>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        h1,
        h2,
        h3,
        .serif {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body class="bg-cream text-gray-800 flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-cream sticky top-0 z-50 border-b border-gray-100">
        <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row md:justify-between md:items-center">
            <div class="flex items-center justify-between w-full md:w-auto">
                <!-- Logo / Store Name -->
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    @if($siteSettings['navbar_logo'] && $siteSettings['navbar_logo']->value)
                        <img src="{{ asset('storage/' . $siteSettings['navbar_logo']->value) }}" 
                             alt="{{ $siteSettings['site_name'] }}" 
                             class="h-12 w-auto object-contain">
                    @else
                        <span class="text-2xl font-bold font-serif text-green-premium tracking-wide">
                            {{ $siteSettings['site_name'] }}
                        </span>
                    @endif
                </a>
                <!-- Mobile menu button could go here if needed -->
            </div>

            <!-- Search, Cart, Profile -->
            <div class="flex items-center space-x-6">
                <!-- Search Bar (Visual only for now) -->
                <!-- Search Bar -->
                <div class="hidden md:block relative">
                    <form action="{{ route('home') }}" method="GET">
                        <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}"
                            class="bg-white border border-gray-200 rounded-full py-1 px-4 text-sm focus:outline-none focus:border-green-premium w-64">
                        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-green-premium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cart & Admin -->
            <div class="flex items-center justify-end space-x-6 w-full md:w-auto">
                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="relative text-gray-600 hover:text-green-premium">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <!-- Badge would go here -->
                </a>

                <!-- Admin Link -->
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-green-premium"
                    title="Admin Dashboard">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-premium text-green-700 p-4 container mx-auto mt-4"
                role="alert">
                <p class="font-bold">Success</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-green-premium text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    @if($siteSettings['footer_logo'] && $siteSettings['footer_logo']->value)
                        <img src="{{ asset('storage/' . $siteSettings['footer_logo']->value) }}" 
                             alt="{{ $siteSettings['site_name'] }}" 
                             class="h-16 w-auto object-contain mb-4">
                    @else
                        <h3 class="text-xl font-serif mb-4">{{ $siteSettings['site_name'] }}</h3>
                    @endif
                    <p class="text-gray-300 text-sm">Premium curated products for your lifestyle.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Shop</h4>
                    <ul class="space-y-2 text-gray-300 text-sm">
                        <li><a href="#" class="hover:text-gold">New Arrivals</a></li>
                        <li><a href="#" class="hover:text-gold">Best Sellers</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-300 text-sm">
                        <li><a href="#" class="hover:text-gold">Contact Us</a></li>
                        <li><a href="#" class="hover:text-gold">Shipping Policy</a></li>
                    </ul>
                </div>
                <!-- QR Code Column -->
                <div class="flex flex-col items-center md:items-end">
                    @if($siteSettings['footer_qr_code'] && $siteSettings['footer_qr_code']->value)
                        <div class="text-center md:text-right">
                            <h4 class="font-bold mb-4">Scan to Connect</h4>
                            <img src="{{ asset('storage/' . $siteSettings['footer_qr_code']->value) }}" 
                                 alt="QR Code" 
                                 class="w-32 h-32 object-contain bg-white p-2 rounded">
                        </div>
                    @endif
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-8 text-center text-gray-400 text-sm">
                &copy; 2026 {{ $siteSettings['site_name'] }}. All rights reserved.
            </div>
        </div>
    </footer>

</body>

</html>