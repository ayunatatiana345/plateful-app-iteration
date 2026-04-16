<aside
    class="hidden lg:flex lg:w-72 lg:flex-col lg:fixed lg:left-0 lg:top-0 lg:bottom-0 lg:z-30 lg:border-r lg:border-gray-200 lg:bg-white">
    <div class="flex h-16 items-center gap-3 px-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span
                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-gray-50 border border-gray-200 overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="Plateful" class="h-7 w-7 object-contain" />
            </span>
            <span class="text-base font-semibold text-gray-900">Plateful</span>
        </a>
    </div>

    @php
        $main = [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'grid'],
            ['label' => 'Browse Food Items', 'route' => 'browse.food-items', 'icon' => 'search'],
            ['label' => 'Inventory', 'route' => 'inventory.index', 'icon' => 'cube'],
            ['label' => 'Meal Planner', 'route' => 'meal-plans.index', 'icon' => 'calendar'],
            ['label' => 'Analytics', 'route' => 'analytics.index', 'icon' => 'chart'],
        ];

        $navItem = function (string $route) {
            $active = request()->routeIs($route);

            return 'flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium transition ' .
                ($active ? 'bg-green-50 text-green-700' : 'text-gray-700 hover:bg-gray-50');
        };

        $icon = function (string $name) {
            return match ($name) {
                'grid'
                    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h7v7H4V4zm9 0h7v7h-7V4zM4 13h7v7H4v-7zm9 0h7v7h-7v-7z"/></svg>',
                'search'
                    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>',
                'calendar'
                    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8 3v2m8-2v2M4 7h16M6 11h4m-4 4h4m4-4h4m-4 4h4M6 21h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                'users'
                    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 11a4 4 0 100-8 4 4 0 000 8z"/><path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 00-3-3.87"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 3.13a4 4 0 010 7.75"/></svg>',
                'chart'
                    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5"/><path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 15v-6"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 15V7"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-4"/></svg>',
                default
                    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 12h12"/></svg>',
            };
        };
    @endphp

    <nav class="flex-1 px-6 py-4">
        <div class="space-y-1">
            @foreach ($main as $item)
                @if (!Route::has($item['route']))
                    @continue
                @endif

                <a href="{{ route($item['route']) }}" class="{{ $navItem($item['route']) }}">
                    <span class="text-gray-500">{!! $icon($item['icon']) !!}</span>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </div>
    </nav>

    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center gap-3">
            <div
                class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[var(--primary)] text-[var(--text)] text-sm font-semibold ring-1 ring-black/5">
                {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <div class="truncate text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                <div class="truncate text-xs text-gray-500">
                    @if (!empty(auth()->user()->household_size))
                        Household size: {{ auth()->user()->household_size }}
                    @else
                        Household Account
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-3 space-y-1">
            @if (Route::has('profile.edit'))
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 {{ request()->routeIs('profile.*') ? 'bg-green-50 text-green-700' : '' }}">
                    <span class="text-gray-500">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                    </span>
                    <span>Profile and Privacy</span>
                </a>
            @endif
        </div>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit"
                class="w-full rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-left text-sm font-medium text-red-700 hover:bg-red-100 hover:border-red-300">
                Logout
            </button>
        </form>
    </div>
</aside>
