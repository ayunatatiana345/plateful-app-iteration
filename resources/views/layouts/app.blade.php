<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Plateful') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[var(--bg)] text-[var(--text)]" x-data="{ mobileSidebarOpen: false }">
    <div class="min-h-screen lg:flex">
        <div class="hidden lg:block lg:w-72 lg:shrink-0"></div>
        @include('partials.sidebar')

        <div class="min-h-screen flex-1">
            <header class="sticky top-0 z-20 border-b border-[var(--neutral)] bg-[var(--secondary)]/70 backdrop-blur">
                <div class="w-full px-4 md:px-6">
                    <div class="flex h-16 items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <button type="button"
                                class="lg:hidden inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/45 ring-1 ring-[var(--neutral)] hover:bg-[var(--accent)]/60"
                                @click="mobileSidebarOpen = true" aria-label="Open menu">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>

                            @php
                                $pageTitle = match (true) {
                                    request()->routeIs('dashboard') => 'Dashboard',
                                    request()->routeIs('notifications.*') => 'Notifications',
                                    request()->routeIs('inventory.*') => 'Food Inventory',
                                    request()->routeIs('browse.food-items*') => 'Browse Food',
                                    request()->routeIs('meal-plans.*') => 'Meal Planning',
                                    request()->routeIs('recipes.*') => 'Recipes',
                                    request()->routeIs('analytics.*') => 'Analytics',
                                    request()->routeIs('profile.*') => 'Settings & Privacy',
                                    default => 'Plateful',
                                };
                            @endphp

                            <h1 class="text-lg md:text-xl font-semibold tracking-tight text-[var(--text)]">
                                {{ $pageTitle }}
                            </h1>
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('notifications.index') }}"
                                class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/45 text-[var(--text)] hover:bg-[var(--accent)]/70 ring-1 ring-[var(--neutral)]"
                                aria-label="Notifications" title="Notifications">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 006 0" />
                                </svg>

                                @isset($notificationCount)
                                    @if ($notificationCount > 0)
                                        <span
                                            class="absolute -top-1 -right-1 inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 text-xs font-semibold text-white">
                                            {{ $notificationCount }}
                                        </span>
                                    @endif
                                @endisset
                            </a>

                            <div class="hidden md:flex items-center gap-2">
                                <div
                                    class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-[var(--primary)] text-[var(--text)] ring-1 ring-black/5 text-sm font-semibold">
                                    {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            </div>

                            <div class="md:hidden">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button
                                            class="inline-flex items-center justify-center h-9 w-9 rounded-full bg-[var(--primary)] text-[var(--text)] ring-1 ring-black/5 text-sm font-semibold">
                                            {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>

                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                                Log Out
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:hidden border-t border-[var(--neutral)]">
                    <div class="mx-auto max-w-7xl px-4 py-3">
                        <div class="flex flex-wrap gap-2">
                            @php
                                $mobileNav = [
                                    ['label' => 'Dashboard', 'route' => 'dashboard'],
                                    ['label' => 'Inventory', 'route' => 'inventory.index'],
                                    ['label' => 'Meal Plan', 'route' => 'meal-plans.index', 'optional' => true],
                                    ['label' => 'Recipes', 'route' => 'recipes.index', 'optional' => true],
                                    ['label' => 'Analytics', 'route' => 'analytics.index', 'optional' => true],
                                ];
                            @endphp

                            @foreach ($mobileNav as $item)
                                @if (!empty($item['optional']) && !Route::has($item['route']))
                                    @continue
                                @endif

                                <a href="{{ route($item['route']) }}"
                                    class="rounded-xl px-3 py-2 text-sm font-medium transition
                                    {{ request()->routeIs($item['route']) ? 'bg-[var(--accent)]/70 text-[var(--text)] ring-1 ring-[var(--neutral)]' : 'bg-white/35 text-[var(--text)] hover:bg-[var(--accent)]/70 ring-1 ring-[var(--neutral)]' }}">
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </header>

            <div x-show="mobileSidebarOpen" x-cloak class="lg:hidden fixed inset-0 z-40" role="dialog"
                aria-modal="true">
                <div class="absolute inset-0 bg-black/35" @click="mobileSidebarOpen = false"></div>

                <div class="absolute inset-y-0 left-0 w-72 max-w-[80vw] bg-white shadow-xl">
                    <div class="flex h-16 items-center justify-between px-4 border-b border-[var(--neutral)]">
                        <div class="font-semibold text-[var(--text)]">Menu</div>
                        <button type="button"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/45 ring-1 ring-[var(--neutral)] hover:bg-[var(--accent)]/60"
                            @click="mobileSidebarOpen = false" aria-label="Close menu">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="h-[calc(100vh-4rem)] overflow-y-auto p-4">
                        @php
                            $mobileSidebarNav = [
                                ['label' => 'Dashboard', 'route' => 'dashboard'],
                                ['label' => 'Food Inventory', 'route' => 'inventory.index'],
                                ['label' => 'Meal Planning', 'route' => 'meal-plans.index', 'optional' => true],
                                ['label' => 'Food Analytics', 'route' => 'analytics.index', 'optional' => true],
                                ['label' => 'Settings & Privacy', 'route' => 'profile.edit'],
                            ];
                        @endphp

                        <div class="space-y-2">
                            @foreach ($mobileSidebarNav as $item)
                                @if (!empty($item['optional']) && !Route::has($item['route']))
                                    @continue
                                @endif

                                <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                                    class="block rounded-xl px-3 py-2 text-sm font-medium ring-1 ring-[var(--neutral)] transition {{ request()->routeIs($item['route']) ? 'bg-[var(--accent)]/70 text-[var(--text)]' : 'bg-white/35 text-[var(--text)] hover:bg-[var(--accent)]/60' }}"
                                    @click="mobileSidebarOpen = false">
                                    {{ $item['label'] }}
                                </a>
                            @endforeach

                            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left rounded-xl px-3 py-2 text-sm font-medium ring-1 ring-[var(--neutral)] bg-white/35 text-[var(--text)] hover:bg-[var(--accent)]/60">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <main class="w-full p-4 md:p-6">
                <div class="mx-auto max-w-7xl">
                    @include('partials.flash')
                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </main>

            @stack('scripts')
        </div>
    </div>
</body>

</html>
