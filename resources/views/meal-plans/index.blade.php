<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-semibold tracking-tight text-[var(--text)]">Meal Planning</h1>
                <p class="mt-1 text-sm text-[var(--text-light)]">Plan your weekly meals based on your current food
                    inventory.</p>
            </div>

            <a href="{{ route('meal-plans.create') }}"
                class="inline-flex items-center rounded-xl bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">
                Add Meal
            </a>
        </div>

        <div class="rounded-3xl border border-[var(--neutral)] bg-white p-6 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="text-lg font-semibold text-[var(--text)]">
                    Week of {{ $weekStart->format('d F') }} — {{ $weekEnd->format('d F Y') }}
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('meal-plans.index', ['week_start' => $weekStart->copy()->subWeek()->toDateString()]) }}"
                        class="rounded-xl border border-[var(--neutral)] bg-[var(--bg)] px-3 py-2 text-sm text-[var(--text)] hover:bg-white">Prev</a>
                    <a href="{{ route('meal-plans.index') }}"
                        class="rounded-xl border border-[var(--neutral)] bg-[var(--bg)] px-3 py-2 text-sm text-[var(--text)] hover:bg-white">This
                        week</a>
                    <a href="{{ route('meal-plans.index', ['week_start' => $weekStart->copy()->addWeek()->toDateString()]) }}"
                        class="rounded-xl border border-[var(--neutral)] bg-[var(--bg)] px-3 py-2 text-sm text-[var(--text)] hover:bg-white">Next</a>
                </div>
            </div>

            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3">
                @for ($i = 0; $i < 7; $i++)
                    @php
                        $date = $weekStart->copy()->addDays($i);
                        $dateKey = $date->toDateString();
                        $items = $plans->get($dateKey, collect());
                    @endphp

                    <div class="rounded-2xl border border-[var(--primary-dark)] bg-[var(--primary)] p-4 shadow-sm"
                        style="background: color-mix(in srgb, var(--primary) 35%, white);">
                        <div class="text-center">
                            <div class="text-xs font-semibold tracking-wide text-[var(--text-light)] uppercase">
                                {{ strtoupper($date->format('D')) }}
                            </div>
                            <div class="mt-1 text-xl font-semibold text-[var(--text)]">{{ $date->format('j') }}</div>
                        </div>

                        <div class="mt-3 space-y-2">
                            @forelse ($items as $plan)
                                @php
                                    $slot = $plan->meal_slot ?? null;

                                    $slotChip = match ($slot) {
                                        'breakfast', 'lunch' => 'bg-[var(--secondary)]',
                                        'dinner', 'snack' => 'bg-[var(--accent)]',
                                        default => 'bg-[var(--accent)]',
                                    };

                                    $chipBg = $plan->status === 'completed' ? 'bg-[var(--secondary)]' : $slotChip;

                                    $slotLabel = match ($slot) {
                                        'breakfast' => 'Breakfast',
                                        'lunch' => 'Lunch',
                                        'dinner' => 'Dinner',
                                        'snack' => 'Snack',
                                        default => null,
                                    };
                                @endphp

                                <a href="{{ route('meal-plans.edit', $plan) }}"
                                    class="block rounded-xl px-3 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/10 hover:brightness-95 {{ $chipBg }}">
                                    @if ($slotLabel)
                                        <span
                                            class="text-xs font-semibold text-[var(--text-light)]">{{ $slotLabel }}</span>
                                        <span class="mx-1 text-[var(--text-light)]">·</span>
                                    @endif
                                    {{ $plan->meal_name }}
                                </a>
                            @empty
                                <a href="{{ route('meal-plans.create', ['planned_date' => $dateKey]) }}"
                                    class="block rounded-xl px-3 py-2 text-sm text-center font-semibold text-[var(--text)] bg-white ring-1 ring-black/10 hover:bg-[var(--bg)]">
                                    + Add meal
                                </a>
                            @endforelse

                            @if ($items->isNotEmpty())
                                <a href="{{ route('meal-plans.create', ['planned_date' => $dateKey]) }}"
                                    class="block rounded-xl px-3 py-2 text-xs text-center font-semibold text-[var(--text)] bg-white ring-1 ring-black/10 hover:bg-[var(--bg)]">
                                    + Add meal
                                </a>
                            @endif
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        @php
            $reserved = [];
            foreach ($plans as $dayPlans) {
                foreach ($dayPlans as $p) {
                    foreach ($p->ingredients_used ?? [] as $ing) {
                        $ing = trim((string) $ing);
                        if ($ing === '') {
                            continue;
                        }
                        $key = mb_strtolower($ing);
                        $reserved[$key] = ($reserved[$key] ?? 0) + 1;
                    }
                }
            }
            $reservedList = collect($reserved)
                ->map(fn($count, $name) => ['name' => $name, 'count' => $count])
                ->sortBy('name')
                ->values();
        @endphp

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-3xl border border-[var(--neutral)] bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-[var(--text)]">Suggested Recipes</h2>
                        <p class="mt-1 text-sm text-[var(--text-light)]">Based on your inventory</p>
                    </div>
                </div>

                @if (!empty($suggestedRecipes) && $suggestedRecipes->isNotEmpty())
                    <div class="mt-4 grid grid-cols-1 gap-3">
                        @foreach ($suggestedRecipes as $recipe)
                            <a href="{{ route('meal-plans.create', [
                                'meal_name' => $recipe['title'],
                                'ingredients_used' => collect($recipe['ingredients'] ?? [])->implode(', '),
                            ]) }}"
                                class="block rounded-2xl border border-[var(--primary-dark)] p-4 shadow-sm hover:brightness-95"
                                style="background: color-mix(in srgb, var(--primary) 35%, white);">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="text-sm font-semibold text-[var(--text)]">{{ $recipe['title'] }}
                                        </div>
                                        <div class="mt-1 text-sm text-[var(--text-light)]">
                                            {{ $recipe['description'] ?? 'Suggested based on your inventory.' }}
                                        </div>
                                    </div>

                                    <span
                                        class="shrink-0 inline-flex items-center rounded-xl bg-[var(--secondary)] px-3 py-1.5 text-xs font-semibold text-[var(--text)] ring-1 ring-black/10">
                                        Add
                                    </span>
                                </div>

                                @if (!empty($recipe['ingredients']))
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach (collect($recipe['ingredients'])->take(8) as $name)
                                            <span
                                                class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ring-1 ring-black/10 bg-[var(--accent)] text-[var(--text)]">
                                                {{ $name }}
                                            </span>
                                        @endforeach
                                        @if (collect($recipe['ingredients'])->count() > 8)
                                            <span class="text-xs font-semibold text-[var(--text-light)]">+
                                                {{ collect($recipe['ingredients'])->count() - 8 }} more</span>
                                        @endif
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>

                    <p class="mt-4 text-xs text-[var(--text-light)]">
                        Tips: click a recipe to auto-fill the Meal Plan form. You can edit the details before saving.
                    </p>
                @else
                    <div class="mt-4 rounded-2xl border border-[var(--primary-dark)] p-4 text-sm text-[var(--text-light)] shadow-sm"
                        style="background: color-mix(in srgb, var(--primary) 20%, white);">
                        Add some inventory items to see suggestions.
                    </div>
                @endif
            </div>

            <div class="rounded-3xl border border-[var(--neutral)] bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-[var(--text)]">Reserved Ingredients</h2>
                        <p class="mt-1 text-sm text-[var(--text-light)]">Ingredients used in this week's plans</p>
                    </div>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse ($reservedList as $row)
                        <div class="flex items-center justify-between gap-3 rounded-2xl px-4 py-3 ring-1 ring-black/10"
                            style="background: color-mix(in srgb, var(--primary) 25%, white);">
                            <div class="text-sm font-semibold text-[var(--text)]">{{ $row['name'] }}</div>
                            <div class="text-xs font-semibold text-[var(--text-light)]">× {{ $row['count'] }}</div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-[var(--primary-dark)] p-4 text-sm text-[var(--text-light)] shadow-sm"
                            style="background: color-mix(in srgb, var(--primary) 20%, white);">
                            No ingredients reserved yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
