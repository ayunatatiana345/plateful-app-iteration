<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between mb-6">
            <div>
                <h1 class="text-3xl font-semibold text-[var(--text)]">Food Analytics</h1>
                <p class="mt-1 text-sm text-[var(--text-light)]">Track saved food, donations, and meal completion.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3 self-start">
                <form method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="hidden" name="mode" value="{{ $mode ?? 'weekly' }}" />

                    <select name="category"
                        class="appearance-none rounded-xl border border-[var(--neutral)] bg-white px-4 py-2 pr-10 text-sm leading-5 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                        <option value="">All Categories</option>
                        @foreach ($categories ?? [] as $cat)
                            <option value="{{ $cat }}" @selected(($category ?? '') === $cat)>{{ $cat }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit"
                        class="rounded-xl bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">
                        Apply
                    </button>

                    <a href="{{ route('analytics.index', ['mode' => $mode ?? 'weekly']) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-[var(--accent)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:brightness-95">
                        All Categories
                    </a>
                </form>

                <div class="inline-flex rounded-full bg-white ring-1 ring-[var(--neutral)] p-1">
                    <a href="{{ route('analytics.index', ['mode' => 'weekly', 'category' => $category ?? '']) }}"
                        class="px-4 py-2 text-sm font-semibold rounded-full transition {{ ($mode ?? 'weekly') === 'weekly' ? 'bg-[var(--primary)] text-[var(--text)]' : 'text-[var(--text-light)] hover:text-[var(--text)]' }}">
                        Weekly
                    </a>
                    <a href="{{ route('analytics.index', ['mode' => 'monthly', 'category' => $category ?? '']) }}"
                        class="px-4 py-2 text-sm font-semibold rounded-full transition {{ ($mode ?? 'weekly') === 'monthly' ? 'bg-[var(--primary)] text-[var(--text)]' : 'text-[var(--text-light)] hover:text-[var(--text)]' }}">
                        Monthly
                    </a>
                </div>
            </div>
        </div>

        @if (!($hasAnyData ?? true))
            <div class="rounded-2xl bg-white border border-[var(--neutral)] p-6 text-sm text-[var(--text-light)]">
                No food-saving data is found
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            {{-- Total Food Saved --}}
            <div class="rounded-2xl bg-white border border-[var(--neutral)] p-5">
                <div class="flex items-center justify-between">
                    <div class="text-sm font-semibold text-[var(--text)]">Total Food Saved</div>
                    <span
                        class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-[var(--primary)] ring-1 ring-black/5">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 8l5-5 5 5" />
                        </svg>
                    </span>
                </div>

                <div class="mt-4 text-4xl font-semibold text-[var(--primary-dark)]">{{ $savedCount }}</div>
                <div class="mt-1 text-sm text-[var(--text-light)]">used / claimed / shared items</div>

                <div class="mt-4 flex items-center justify-between text-xs text-[var(--text-light)]">
                    <span>{{ ($mode ?? 'weekly') === 'weekly' ? 'Weekly Goal' : 'Monthly Goal' }}</span>
                    <span class="font-semibold text-[var(--text)]">{{ $savedPct }}%</span>
                </div>
                <div class="mt-2 h-2 rounded-full bg-[var(--neutral)] overflow-hidden">
                    <div class="h-full rounded-full"
                        style="width: {{ $savedPct }}%; background: var(--primary-dark);"></div>
                </div>
            </div>

            {{-- Donations Made --}}
            <div class="rounded-2xl bg-white border border-[var(--neutral)] p-5">
                <div class="flex items-center justify-between">
                    <div class="text-sm font-semibold text-[var(--text)]">Donations Made</div>
                    <span
                        class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-[var(--secondary)] ring-1 ring-black/5">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 21s-7-4.35-7-11a4 4 0 007-2 4 4 0 007 2c0 6.65-7 11-7 11z" />
                        </svg>
                    </span>
                </div>

                <div class="mt-4 text-4xl font-semibold text-[#2563eb]">{{ $donationsMade }}</div>
                <div class="mt-1 text-sm text-[var(--text-light)]">items donated to community</div>

                <div class="mt-4 flex items-center justify-between text-xs text-[var(--text-light)]">
                    <span>{{ ($mode ?? 'weekly') === 'weekly' ? 'Weekly Goal' : 'Monthly Goal' }}</span>
                    <span class="font-semibold text-[var(--text)]">{{ $donationsPct }}%</span>
                </div>
                <div class="mt-2 h-2 rounded-full bg-[var(--neutral)] overflow-hidden">
                    <div class="h-full rounded-full" style="width: {{ $donationsPct }}%; background: #93c5fd;"></div>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="rounded-2xl bg-white border border-[var(--neutral)] p-5 lg:col-span-2">
                <div class="flex items-center justify-between">
                    <div class="text-sm font-semibold text-[var(--text)]">Activity</div>
                    <div class="flex items-center gap-3 text-xs text-[var(--text-light)]">
                        <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full"
                                style="background: var(--primary-dark);"></span>Saved</span>
                        <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full"
                                style="background: #93c5fd;"></span>Donated</span>
                    </div>
                </div>

                @if (($selectedDayLabel ?? null) !== null)
                    <div
                        class="mt-3 rounded-xl bg-[var(--bg)] px-4 py-3 text-sm text-[var(--text)] ring-1 ring-[var(--neutral)]">
                        Showing details for <span class="font-semibold">{{ $selectedDayLabel }}</span>
                        @if (($category ?? '') !== '')
                            <span class="text-[var(--text-light)]">· Category: {{ $category }}</span>
                        @endif
                        <a href="{{ route('analytics.index', ['mode' => $mode ?? 'weekly', 'category' => $category ?? '']) }}"
                            class="ml-2 text-[var(--primary-dark)] hover:underline">Clear</a>
                    </div>
                @endif

                <div class="mt-4">
                    <canvas id="weeklyActivityChart" height="170"></canvas>
                </div>
            </div>

            <div class="rounded-2xl bg-white border border-[var(--neutral)] p-5">
                <div class="text-sm font-semibold text-[var(--text)]">Top Categories (Saved)</div>

                <div class="mt-4 space-y-4">
                    @forelse($topCategories as $i => $row)
                        @php
                            $barColors = ['var(--primary-dark)', '#93c5fd', '#fca5a5', 'var(--neutral)'];
                            $bar = $barColors[$i % count($barColors)];
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <div class="font-medium text-[var(--text)]">{{ $row['category'] }}</div>
                                <div class="text-[var(--text-light)]">{{ $row['pct'] }}%</div>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-[var(--neutral)] overflow-hidden">
                                <div class="h-full rounded-full"
                                    style="width: {{ $row['pct'] }}%; background: {{ $bar }};"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-[var(--text-light)]">no food-saving data is found</div>
                    @endforelse
                </div>
            </div>
        </div>

        @if (($selectedDayLabel ?? null) !== null)
            <div class="mt-4 rounded-2xl bg-white border border-[var(--neutral)] p-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="text-sm font-semibold text-[var(--text)]">Daily Activity Details</div>
                        <div class="mt-1 text-sm text-[var(--text-light)]">A breakdown of actions recorded for the
                            selected day.
                        </div>
                    </div>
                    <a href="{{ route('analytics.index', ['mode' => $mode ?? 'weekly', 'category' => $category ?? '']) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-[var(--accent)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:brightness-95">
                        Reset Day
                    </a>
                </div>

                @if (($dayEvents ?? collect())->isEmpty())
                    <div class="mt-4 text-sm text-[var(--text-light)]">No events recorded for this day.</div>
                @else
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-[var(--text-light)]">
                                <tr>
                                    <th class="text-left font-medium py-2 pr-4">Time</th>
                                    <th class="text-left font-medium py-2 pr-4">Action</th>
                                    <th class="text-left font-medium py-2 pr-4">Item</th>
                                    <th class="text-left font-medium py-2">Category</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y"
                                style="border-color: color-mix(in srgb, var(--neutral) 70%, transparent);">
                                @foreach ($dayEvents as $e)
                                    <tr>
                                        <td class="py-2 pr-4 text-[var(--text)]">{{ $e['when'] ?? '-' }}</td>
                                        <td class="py-2 pr-4 text-[var(--text)]">{{ $e['action'] ?? '-' }}</td>
                                        <td class="py-2 pr-4 text-[var(--text)]">{{ $e['item'] ?? '-' }}</td>
                                        <td class="py-2 text-[var(--text)]">{{ $e['category'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            const labels = @json($labels);
            const dayKeys = @json($dayKeys ?? []);
            const savedByDay = @json($savedByDay);
            const donatedByDay = @json($donatedByDay);

            const chartEl = document.getElementById('weeklyActivityChart');

            const params = new URLSearchParams(window.location.search);
            const currentMode = params.get('mode') || @json($mode ?? 'weekly');
            const currentCategory = params.get('category') || @json($category ?? '');

            const chart = new Chart(chartEl, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                            label: 'Saved',
                            data: savedByDay,
                            backgroundColor: 'rgba(125, 196, 154, 0.55)',
                            borderRadius: 10,
                            borderSkipped: false,
                        },
                        {
                            label: 'Donated',
                            data: donatedByDay,
                            backgroundColor: 'rgba(147, 197, 253, 0.7)',
                            borderRadius: 10,
                            borderSkipped: false,
                        },
                    ]
                },
                options: {
                    responsive: true,
                    onClick: (evt, elements) => {
                        if (!elements || elements.length === 0) return;

                        const idx = elements[0].index;
                        const day = dayKeys[idx];
                        if (!day) return;

                        const next = new URLSearchParams();
                        next.set('mode', currentMode);
                        if (currentCategory) next.set('category', currentCategory);
                        next.set('day', day);

                        window.location.search = next.toString();
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                            },
                            grid: {
                                color: 'rgba(224, 224, 224, 0.7)'
                            }
                        }
                    }
                }
            });

            chartEl.classList.add('cursor-pointer');
        </script>
    @endpush
</x-app-layout>
