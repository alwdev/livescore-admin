<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false, sidebarCollapsed: false }" class="bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Livescore Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="font-sans antialiased text-gray-900">
    <!-- Header -->
    <header class="fixed top-0 left-0 w-full bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm z-50">
        <div class="flex justify-between items-center px-6 py-4 transition-all duration-300">

            {{-- Left: Logo --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="w-9 h-9 bg-gradient-to-brrounded-lg flex items-center justify-center shadow-md">
                    <span class="text-white text-base font-bold"> <img
                            src="{{ asset('storage/' . $siteSetting->logo) }}" alt="Application Logo"
                            class=" fill-current text-gray-500"></span>
                </div>
                <h1 class="hidden sm:block text-lg md:text-xl font-extrabold text-gray-900 tracking-tight">
                    Livescore <span class="text-indigo-600">Admin</span>
                </h1>
            </div>

            {{-- Center: Hamburger toggle --}}
            {{-- <div class="flex items-center justify-center flex-1">
                <!-- Toggle sidebar (mobile + desktop) -->
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-lg text-gray-700 hover:text-indigo-600 hover:bg-gray-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div> --}}

            {{-- Right: User info + Logout --}}
            <div class="flex items-center gap-4 flex-shrink-0">
                <div class="hidden md:flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        class="flex items-center gap-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm px-4 py-2 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </header>



    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed top-0 left-0 h-full bg-white border-r border-gray-200 shadow-xl
               transform md:translate-x-0 transition-all duration-300 ease-in-out z-30"
        :style="`width: ${sidebarCollapsed ? '80px' : '256px'}`">

        <div class="flex flex-col h-full">
            <!-- Logo Section -->
            <div class="flex items-center px-6 py-4 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-sm font-bold">⚽</span>
                    </div>
                    <h1 x-show="!sidebarCollapsed" x-transition
                        class="text-xl font-bold text-gray-900 tracking-tight whitespace-nowrap">
                        Livescore <span class="text-indigo-600">Admin</span>
                    </h1>
                </div>
            </div>

            <nav class="flex-1 p-6 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" x-tooltip="sidebarCollapsed ? 'Dashboard' : ''"
                    class="group flex items-center rounded-xl transition-all duration-200 {{ request()->is('admin') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}"
                    :class="sidebarCollapsed ? 'px-2 py-3 justify-center' : 'px-4 py-3 gap-3'">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ request()->is('admin') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-indigo-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition class="font-medium whitespace-nowrap">Dashboard</span>
                </a>

                <!-- Articles -->
                <a href="{{ route('admin.articles.index') }}" x-tooltip="sidebarCollapsed ? 'Articles' : ''"
                    class="group flex items-center rounded-xl transition-all duration-200 {{ request()->is('admin/articles*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}"
                    :class="sidebarCollapsed ? 'px-2 py-3 justify-center' : 'px-4 py-3 gap-3'">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ request()->is('admin/articles*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-indigo-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition class="font-medium whitespace-nowrap">Articles</span>
                </a>

                <!-- Leagues -->
                <a href="{{ route('admin.leagues.index') }}" x-tooltip="sidebarCollapsed ? 'จัดการลีก' : ''"
                    class="group flex items-center rounded-xl transition-all duration-200 {{ request()->is('admin/leagues*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}"
                    :class="sidebarCollapsed ? 'px-2 py-3 justify-center' : 'px-4 py-3 gap-3'">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ request()->is('admin/leagues*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-indigo-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition class="font-medium whitespace-nowrap">จัดการลีก</span>
                </a>

                <!-- Teams -->
                <a href="{{ route('admin.teams.index') }}" x-tooltip="sidebarCollapsed ? 'จัดการทีม' : ''"
                    class="group flex items-center rounded-xl transition-all duration-200 {{ request()->is('admin/teams*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}"
                    :class="sidebarCollapsed ? 'px-2 py-3 justify-center' : 'px-4 py-3 gap-3'">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ request()->is('admin/teams*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-indigo-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition
                        class="font-medium whitespace-nowrap">จัดการทีม</span>
                </a>

                <!-- Users -->
                <a href="{{ route('admin.users.index') }}" x-tooltip="sidebarCollapsed ? 'Users' : ''"
                    class="group flex items-center rounded-xl transition-all duration-200 {{ request()->is('admin/users*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}"
                    :class="sidebarCollapsed ? 'px-2 py-3 justify-center' : 'px-4 py-3 gap-3'">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ request()->is('admin/users*') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-indigo-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-.5a4 4 0 110-5.292M21 15H9v-1a4 4 0 118 0v1z" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition class="font-medium whitespace-nowrap">Users</span>
                </a>

                <!-- Settings -->
                <a href="{{ route('admin.settings.edit') }}" x-tooltip="sidebarCollapsed ? 'Settings' : ''"
                    class="group flex items-center rounded-xl transition-all duration-200 {{ request()->is('admin/settings') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}"
                    :class="sidebarCollapsed ? 'px-2 py-3 justify-center' : 'px-4 py-3 gap-3'">
                    <div
                        class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ request()->is('admin/settings') ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-indigo-100' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition
                        class="font-medium whitespace-nowrap">Settings</span>
                </a>
            </nav>

            <!-- Footer -->
            <div x-show="!sidebarCollapsed" x-transition class="border-t border-gray-200 p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-bold">⚽</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Livescore Admin</p>
                        <p class="text-xs text-gray-500">Version 1.0</p>
                    </div>
                </div>
                <p class="text-xs text-gray-400">© {{ date('Y') }} All rights reserved</p>
            </div>

            <!-- Collapsed Footer -->
            <div x-show="sidebarCollapsed" x-transition class="border-t border-gray-200 p-4 flex justify-center">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                    <span class="text-white text-sm font-bold">⚽</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="transition-all duration-300 pt-24 px-6 pb-10" :class="sidebarCollapsed ? 'md:ml-20' : 'md:ml-64'">
        <div class="max-w-7xl mx-auto">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">@yield('header', 'Dashboard')</h2>
                <p class="text-gray-600">@yield('description', 'Welcome to your admin dashboard')</p>
            </div>
            @yield('content')
        </div>
    </main>

    <!-- Overlay for mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"
        x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>
    @stack('scripts')
</body>

</html>
