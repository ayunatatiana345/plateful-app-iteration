<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-semibold text-[var(--text)]">Dashboard</h1>
            <p class="text-sm text-[var(--text)]/70">Track inventory, reduce waste, and plan mindful meals.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-2xl border border-[var(--neutral)] p-4 bg-[var(--primary)]">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-sm font-medium text-[var(--text)]/70">Total Items</div>
                        <div class="mt-2 text-3xl font-semibold text-[var(--text)]">{{ $totalItems }}</div>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-white/40 ring-1 ring-black/5 flex items-center justify-center">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            class="h-5 w-5 text-[var(--text)]">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M20 7l-8 4-8-4m16 0l-8-4-8 4m16 0v10l-8 4-8-4V7" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-[var(--neutral)] p-4 bg-[var(--accent)]">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-sm font-medium text-[var(--text)]/70">Expiring Soon (≤3 days)</div>
                        <div class="mt-2 text-3xl font-semibold text-[var(--text)]">{{ $expiringSoon }}</div>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-white/40 ring-1 ring-black/5 flex items-center justify-center">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            class="h-5 w-5 text-[var(--text)]">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-[var(--neutral)] p-4 bg-[var(--secondary)]">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-sm font-medium text-[var(--text)]/70">Items Donated</div>
                        <div class="mt-2 text-3xl font-semibold text-[var(--text)]">{{ $itemsDonated }}</div>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-white/40 ring-1 ring-black/5 flex items-center justify-center">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            class="h-5 w-5 text-[var(--text)]">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 10v10a1 1 0 001 1h8a1 1 0 001-1V10M9 10V7a3 3 0 016 0v3M6 10h12" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-[var(--neutral)] p-4 bg-[var(--primary-dark)]">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-sm font-medium text-white/90">Meals Planned</div>
                        <div class="mt-2 text-3xl font-semibold text-white">{{ $mealsPlanned }}</div>
                    </div>
                    <div class="h-10 w-10 rounded-xl bg-white/20 ring-1 ring-white/30 flex items-center justify-center">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            class="h-5 w-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-2xl bg-white border border-[var(--neutral)] p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span
                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-[var(--primary)]/35 ring-1 ring-black/5">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                class="h-5 w-5 text-[var(--text)]">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 7v14m0-14a4 4 0 014-4h1a3 3 0 013 3v1a4 4 0 01-4 4h-4z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 7a4 4 0 00-4-4H7a3 3 0 00-3 3v1a4 4 0 004 4h4" />
                            </svg>
                        </span>
                        <h2 class="font-semibold text-[var(--text)]">Expiring & Expired</h2>
                    </div>
                    <a href="{{ route('inventory.index', ['status' => 'Expiring Soon']) }}"
                        class="text-sm font-medium text-[var(--primary-dark)] hover:text-[var(--text)] hover:underline">View
                        inventory</a>
                </div>

                @if ($recentExpiring->isEmpty())
                    <p class="mt-3 text-sm text-[var(--text)]/70">No expiring items. Great job!</p>
                @else
                    <div class="mt-3 divide-y"
                        style="border-color: color-mix(in srgb, var(--neutral) 70%, transparent);">
                        @foreach ($recentExpiring as $item)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-[var(--text)]">{{ $item->name }}</div>
                                    <div class="text-xs text-[var(--text)]/60">Category: {{ $item->category }} • Exp:
                                        {{ $item->expiration_date?->format('Y-m-d') }}</div>
                                </div>
                                <span class="text-xs font-medium px-2 py-1 rounded-full ring-1 ring-[var(--neutral)]"
                                    style="background: {{ $item->status === 'Expired' ? '#fee2e2' : 'var(--accent)' }}; color: {{ $item->status === 'Expired' ? '#b91c1c' : 'var(--text)' }};">
                                    {{ $item->status }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rounded-2xl bg-white border border-[var(--neutral)] p-4">
                <div class="flex items-center gap-2">
                    <span
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-[var(--secondary)] ring-1 ring-black/5">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            class="h-5 w-5 text-[var(--text)]">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </span>
                    <h2 class="font-semibold text-[var(--text)]">Quick Actions</h2>
                </div>
                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a href="{{ route('inventory.create') }}"
                        class="group rounded-xl border border-[var(--neutral)] bg-[var(--primary)] px-4 py-3 hover:bg-[var(--primary-dark)]">
                        <div class="flex items-start gap-3">
                            <span
                                class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/35 ring-1 ring-black/5">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    class="h-5 w-5 text-[var(--text)]">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </span>
                            <div>
                                <div class="font-medium text-[var(--text)]">Add Food Item</div>
                                <div class="text-sm text-[var(--text)]/70">Track a new purchase.</div>
                            </div>
                        </div>
                    </a>

                    @if (Route::has('meal-plans.index'))
                        <a href="{{ route('meal-plans.index') }}"
                            class="group rounded-xl border border-[var(--neutral)] bg-[var(--secondary)] px-4 py-3 hover:brightness-95">
                            <div class="flex items-start gap-3">
                                <span
                                    class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/35 ring-1 ring-black/5">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        class="h-5 w-5 text-[var(--text)]">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 7V3m8 4V3M5 11h14M7 21h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                <div>
                                    <div class="font-medium text-[var(--text)]">Plan Meals</div>
                                    <div class="text-sm text-[var(--text)]/70">Make a weekly plan.</div>
                                </div>
                            </div>
                        </a>
                    @endif

                    <a href="{{ route('inventory.donations.index') }}"
                        class="group rounded-xl border border-[var(--neutral)] bg-[var(--accent)] px-4 py-3 hover:brightness-95">
                        <div class="flex items-start gap-3">
                            <span
                                class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/35 ring-1 ring-black/5">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    class="h-5 w-5 text-[var(--text)]">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 10h18M7 10V7a5 5 0 0110 0v3M7 10v10a1 1 0 001 1h8a1 1 0 001-1V10" />
                                </svg>
                            </span>
                            <div>
                                <div class="font-medium text-[var(--text)]">Donation Listings</div>
                                <div class="text-sm text-[var(--text)]/70">Manage your shared food.</div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('browse.food-items', ['source' => 'donations']) }}"
                        class="group rounded-xl border border-[var(--neutral)] bg-[var(--bg)] px-4 py-3 hover:bg-[var(--neutral)]">
                        <div class="flex items-start gap-3">
                            <span
                                class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-xl bg-[var(--secondary)] ring-1 ring-black/5">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    class="h-5 w-5 text-[var(--text)]">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <div>
                                <div class="font-medium text-[var(--text)]">Browse Food</div>
                                <div class="text-sm text-[var(--text)]/70">Find available donations.</div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('analytics.index') }}"
                        class="group rounded-xl border border-[var(--neutral)] bg-[var(--bg)] px-4 py-3 hover:bg-[var(--neutral)]">
                        <div class="flex items-start gap-3">
                            <span
                                class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-xl bg-[var(--accent)] ring-1 ring-black/5">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    class="h-5 w-5 text-[var(--text)]">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 19V5m0 14h16m-16 0l5-6 4 4 7-9" />
                                </svg>
                            </span>
                            <div>
                                <div class="font-medium text-[var(--text)]">Track My Impact</div>
                                <div class="text-sm text-[var(--text)]/70">View reports and progress.</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-6 rounded-2xl border border-[var(--neutral)] p-4 bg-[var(--white)]">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-[#fee2e2] ring-1 ring-[#fecaca]">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            class="h-5 w-5 text-[#b91c1c]">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </span>
                    <h2 class="font-semibold text-[var(--text)]">Expired Items</h2>
                </div>
                <span class="text-sm text-[var(--text)]/70">{{ $expired }} expired</span>
            </div>
            <p class="mt-2 text-sm text-[var(--text)]/70">
                Tip: mark items you'd normally throw away as <span class="font-medium">wasted</span> in Analytics
                (coming next).
            </p>
        </div>
    </div>
</x-app-layout>
