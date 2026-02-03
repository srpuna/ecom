<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ $siteSettings['site_name'] ?? 'Ecom' }}</title>
    @if(isset($siteSettings['favicon']) && $siteSettings['favicon']->value)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $siteSettings['favicon']->value) }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
            <div class="h-16 flex items-center justify-center border-b border-gray-200 px-4">
                @if(isset($siteSettings['navbar_logo']) && $siteSettings['navbar_logo']->value)
                    <img src="{{ asset('storage/' . $siteSettings['navbar_logo']->value) }}" 
                         alt="{{ $siteSettings['site_name'] ?? 'LuxeStore' }} Admin" 
                         class="h-10 w-auto object-contain">
                @else
                    <h1 class="text-2xl font-bold text-green-600">{{ $siteSettings['site_name'] ?? 'LuxeStore' }} Admin</h1>
                @endif
            </div>

            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center px-6 py-3 text-gray-600 hover:bg-green-50 hover:text-green-600 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-green-50 text-green-600 border-r-4 border-green-600' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories.index') }}"
                            class="flex items-center px-6 py-3 text-gray-600 hover:bg-green-50 hover:text-green-600 transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-green-50 text-green-600 border-r-4 border-green-600' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            Categories
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}"
                            class="flex items-center px-6 py-3 text-gray-600 hover:bg-green-50 hover:text-green-600 transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-green-50 text-green-600 border-r-4 border-green-600' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Products
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.inquiries.index') }}"
                            class="flex items-center px-6 py-3 text-gray-600 hover:bg-green-50 hover:text-green-600 transition-colors {{ request()->routeIs('admin.inquiries.*') ? 'bg-green-50 text-green-600 border-r-4 border-green-600' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            Inquiries
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.shipping.index') }}"
                            class="flex items-center px-6 py-3 text-gray-600 hover:bg-green-50 hover:text-green-600 transition-colors {{ request()->routeIs('admin.shipping.*') ? 'bg-green-50 text-green-600 border-r-4 border-green-600' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0">
                                </path>
                            </svg>
                            Shipping Settings
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings.index') }}"
                            class="flex items-center px-6 py-3 text-gray-600 hover:bg-green-50 hover:text-green-600 transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-green-50 text-green-600 border-r-4 border-green-600' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Site Settings
                        </a>
                    </li>
                    @if(auth()->user()->isSuperAdmin())
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center px-6 py-3 text-gray-600 hover:bg-green-50 hover:text-green-600 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-green-50 text-green-600 border-r-4 border-green-600' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            Admin Users
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.roles.index') }}"
                            class="flex items-center px-6 py-3 text-gray-600 hover:bg-green-50 hover:text-green-600 transition-colors {{ request()->routeIs('admin.roles.*') ? 'bg-green-50 text-green-600 border-r-4 border-green-600' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                            Roles & Permissions
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
                <div>
                    @yield('header')
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500">
                            @foreach(auth()->user()->roles as $role)
                                <span class="inline-block">{{ $role->display_name }}</span>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </div>
                    </div>
                    <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-red-600 transition-colors" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto bg-gray-50 p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 bg-red-100 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

</body>

</html>