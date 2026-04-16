<x-app-layout>
    <div class="max-w-7xl mx-auto" x-data="{ convertModalOpen: false, convertAction: '' }">
        <div class="mb-6">
            <h1 class="text-3xl font-semibold text-[var(--text)]">Browse Food</h1>
            <p class="mt-1 text-sm text-[var(--text)]/70">Browse items from your inventory or donations from other
                households.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 mb-6">
            <form method="GET" class="flex flex-wrap items-center gap-3">
                <select name="source"
                    class="appearance-none rounded-xl border border-[var(--neutral)] bg-white px-4 py-2 pr-10 text-sm leading-5 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                    <option value="inventory" @selected(($source ?? 'donations') === 'inventory')>Inventory only</option>
                    <option value="donations" @selected(($source ?? 'donations') === 'donations')>Donation listings</option>
                </select>

                <select name="category"
                    class="appearance-none rounded-xl border border-[var(--neutral)] bg-white px-4 py-2 pr-10 text-sm leading-5 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected($category === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>

                <select name="storage"
                    class="appearance-none rounded-xl border border-[var(--neutral)] bg-white px-4 py-2 pr-10 text-sm leading-5 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                    <option value="">Storage (All)</option>
                    @foreach ($storageOptions ?? [] as $opt)
                        <option value="{{ $opt }}" @selected(($storage ?? '') === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>

                <input type="date" name="expiry_max" value="{{ $expiryMax ?? '' }}"
                    class="appearance-none rounded-xl border border-[var(--neutral)] bg-white px-4 py-2 pr-10 text-sm leading-5 focus:border-[var(--primary)] focus:ring-[var(--primary)]" />

                <select name="sort"
                    class="appearance-none rounded-xl border border-[var(--neutral)] bg-white px-4 py-2 pr-10 text-sm leading-5 focus:border-[var(--primary)] focus:ring-[var(--primary)]">
                    <option value="expiry_soonest" @selected(($sort ?? '') === 'expiry_soonest')>Sort by: Expiry (Soonest)</option>
                    <option value="newest" @selected(($sort ?? '') === 'newest')>Sort by: Newest</option>
                </select>

                <div class="hidden sm:block">
                    <input type="search" name="q" value="{{ $q }}" placeholder="Search..."
                        class="rounded-xl border border-[var(--neutral)] bg-white px-4 py-2 text-sm focus:border-[var(--primary)] focus:ring-[var(--primary)]" />
                </div>

                <button type="submit"
                    class="rounded-xl bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">
                    Apply
                </button>

                <a href="{{ route('browse.food-items') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-[var(--accent)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:brightness-95">
                    Reset
                </a>
            </form>
        </div>

        @php
            $rows = ($source ?? 'donations') === 'inventory' ? $inventoryItems ?? null : $donations ?? null;
        @endphp

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            @if ($source === 'inventory')
                @forelse ($inventoryItems as $food)
                    @php
                        $exp = $food?->expiration_date;
                        $daysLeft = $exp
                            ? now()
                                ->startOfDay()
                                ->diffInDays($exp->copy()->startOfDay(), false)
                            : null;
                    @endphp

                    <div class="rounded-3xl bg-white border border-[var(--neutral)] p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <h3 class="truncate text-lg font-semibold text-[var(--text)]">{{ $food->name }}
                                    </h3>
                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-700">
                                        Inventory
                                    </span>
                                </div>
                                <div class="mt-1 text-sm text-[var(--text)]/70">
                                    {{ $food->category }} · {{ $food->quantity }} {{ $food->unit }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-700">
                            <div><span class="font-medium">Expiry:</span>
                                {{ $food->expiration_date?->format('Y-m-d') ?? '-' }}</div>
                            <div><span class="font-medium">Storage:</span> {{ $food->storage_location ?? '-' }}</div>
                            <div class="sm:col-span-2"><span class="font-medium">Notes:</span>
                                {{ $food->notes ?? '-' }}</div>
                        </div>

                        <div class="mt-5 space-y-2">
                            <a href="{{ route('browse.food-items.show', $food) }}"
                                class="block w-full text-center rounded-2xl bg-[var(--primary)] px-4 py-2.5 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">
                                View Details
                            </a>

                            <a href="{{ route('meal-plans.create', ['ingredients_used' => $food->name]) }}"
                                class="block w-full text-center rounded-2xl bg-[var(--secondary)] px-4 py-2.5 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:brightness-95">
                                Plan Meal
                            </a>

                            <form method="POST" action="{{ route('inventory.markUsed', $food) }}"
                                onsubmit="return confirm('Mark as used?');">
                                @csrf
                                <button type="submit"
                                    class="w-full rounded-2xl bg-[var(--accent)] px-4 py-2.5 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:brightness-95">
                                    Mark as Used
                                </button>
                            </form>

                            @if ($food->status === 'Expiring Soon')
                                <button type="button"
                                    class="block w-full text-center rounded-2xl bg-[var(--primary)] px-4 py-2.5 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]"
                                    @click="convertAction='{{ route('inventory.donations.convert', $food) }}'; convertModalOpen=true">
                                    Convert to Donation
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="md:col-span-2 rounded-2xl bg-white border border-[var(--neutral)] p-10 text-center text-gray-600">
                        No items found. Please adjust your filters.
                    </div>
                @endforelse
            @else
                @forelse ($donations as $donation)
                    @php
                        $food = $donation->foodItem;
                        $exp = $food?->expiration_date;
                        $daysLeft = $exp
                            ? now()
                                ->startOfDay()
                                ->diffInDays($exp->copy()->startOfDay(), false)
                            : null;
                    @endphp

                    <div class="rounded-3xl bg-white border border-[var(--neutral)] p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-3">
                                    <h3 class="truncate text-lg font-semibold text-[var(--text)]">
                                        {{ $food?->name ?? '—' }}</h3>
                                    <span
                                        class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        Available
                                    </span>
                                </div>
                                <div class="mt-1 text-sm text-[var(--text)]/70">
                                    {{ $food?->category ?? 'Uncategorized' }} · {{ $food?->quantity }}
                                    {{ $food?->unit }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-700">
                            <div><span class="font-medium">Expiry:</span>
                                {{ $food?->expiration_date?->format('Y-m-d') ?? '-' }}</div>
                            <div><span class="font-medium">Storage:</span> {{ $food?->storage_location ?? '-' }}</div>
                            <div><span class="font-medium">Pickup:</span> {{ $donation->pickup_location ?? '-' }}</div>
                            <div><span class="font-medium">Availability:</span> {{ $donation->availability ?? '-' }}
                            </div>
                            <div class="sm:col-span-2"><span class="font-medium">Contact:</span>
                                {{ $donation->donor?->name ?? 'Unknown' }}</div>
                        </div>

                        <div class="mt-5 space-y-2">
                            <form method="POST" action="{{ route('donations.claim', $donation) }}"
                                onsubmit="return confirm('Claim this item?')">
                                @csrf
                                <button type="submit"
                                    class="w-full rounded-2xl bg-[var(--primary)] px-4 py-2.5 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">
                                    Claim Donation
                                </button>
                            </form>

                            @if ($food)
                                <a href="{{ route('meal-plans.create', ['ingredients_used' => $food->name]) }}"
                                    class="block w-full text-center rounded-2xl bg-[var(--secondary)] px-4 py-2.5 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:brightness-95">
                                    Plan Meal
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="md:col-span-2 rounded-2xl bg-white border border-[var(--neutral)] p-10 text-center text-gray-600">
                        No items found. Please adjust your filters.
                    </div>
                @endforelse
            @endif
        </div>

        <div class="mt-6">
            {{ $rows?->links() }}
        </div>

        <x-modal name="confirm-convert-donation-browse" :show="false" maxWidth="md">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-[var(--text)]">Convert to donation?</h2>
                <p class="mt-2 text-sm text-[var(--text-light)]">
                    Are you sure want to convert to donation?
                </p>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="button"
                        class="inline-flex items-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-[var(--neutral)] hover:bg-gray-50"
                        @click="convertModalOpen=false; $dispatch('close-modal', 'confirm-convert-donation-browse')">
                        Cancel
                    </button>

                    <form method="POST" :action="convertAction" class="inline">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center rounded-xl bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">
                            Yes, convert
                        </button>
                    </form>
                </div>
            </div>
        </x-modal>

        <div
            x-effect="convertModalOpen ? $dispatch('open-modal', 'confirm-convert-donation-browse') : $dispatch('close-modal', 'confirm-convert-donation-browse')">
        </div>
    </div>
</x-app-layout>
