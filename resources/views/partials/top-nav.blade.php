@php
    $nav = [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
        ],
        [
            'label' => 'Inventory',
            'route' => 'inventory.index',
        ],
        [
            'label' => 'Meal Plan',
            'route' => 'meal-plans.index',
            'optional' => true,
        ],
        [
            'label' => 'Recipes',
            'route' => 'recipes.index',
            'optional' => true,
        ],
        [
            'label' => 'Analytics',
            'route' => 'analytics.index',
            'optional' => true,
        ],
    ];
@endphp

<div class="hidden md:flex items-center gap-1">
    @foreach ($nav as $item)
        @if (!empty($item['optional']) && !Route::has($item['route']))
            @continue
        @endif

        <a href="{{ route($item['route']) }}"
            class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium transition ring-1 ring-transparent
                {{ request()->routeIs($item['route']) ? 'bg-[var(--accent)]/70 text-[var(--text)] ring-[var(--neutral)]' : 'text-[var(--text)] hover:bg-[var(--accent)]/60 hover:ring-[var(--neutral)]' }}">
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach
</div>
