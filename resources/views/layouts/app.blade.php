<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuxeStore - Premium Ecommerce</title>
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
                <a href="{{ route('home') }}" class="text-2xl font-bold font-serif text-green-premium tracking-wide">
                    LuxeStore
                </a>
                <!-- Mobile menu button could go here if needed -->
            </div>

            <!-- Centered Search Bar -->
            <div class="flex justify-center w-full my-4 md:my-0 md:w-1/2">
                <div class="w-full md:w-3/4 lg:w-2/3 relative">
                    <input type="text" placeholder="Search..."
                        class="bg-cream border-2 border-gold rounded-full py-2 px-6 pr-12 text-base shadow focus:outline-none focus:border-green-premium transition w-full placeholder-gold font-serif text-green-premium">
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-transparent border-none shadow-none p-0 m-0 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-premium" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <ellipse cx="11" cy="11" rx="7" ry="7" stroke="currentColor"/>
                            <path d="M17.5 17.5L21 21" stroke="currentColor" stroke-linecap="round"/>
                        </svg>
                    </button>
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
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-serif mb-4">LuxeStore</h3>
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
        </div>
        <div class="mt-8 border-t border-gray-700 pt-8 text-center text-gray-400 text-sm">
            &copy; 2026 LuxeStore. All rights reserved.
        </div>
    </footer>

</body>

</html>