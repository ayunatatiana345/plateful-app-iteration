<x-app-layout>
    <div class="max-w-7xl mx-auto" x-data="{ deleteModalOpen: false, deleteAction: '', convertModalOpen: false, convertAction: '' }">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-semibold text-gray-900">Food Inventory</h1>
                <p class="text-sm text-gray-600">Add, filter, and monitor expiry status.</p>
                <div class="mt-2">
                    <a href="{{ route('inventory.donations.index') }}"
                        class="inline-flex items-center rounded-xl bg-[var(--secondary)] px-3 py-1.5 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:brightness-95">
                        View donation listings
                    </a>
                </div>
            </div>
            <a href="{{ route('inventory.create') }}"
                class="inline-flex items-center rounded-xl bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">
                Add Item
            </a>
        </div>

        @php
            $chip = function (string $label, array $params, bool $active = false, string $leading = '') {
                $base =
                    'inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium ring-1 transition whitespace-nowrap';
                $cls = $active
                    ? 'bg-[var(--primary)]/35 text-[var(--text)] ring-[var(--primary-dark)]'
                    : 'bg-white text-gray-700 ring-gray-200 hover:bg-gray-50';

                $url = request()->fullUrlWithQuery($params);

                return '<a href="' .
                    e($url) .
                    '" class="' .
                    $base .
                    ' ' .
                    $cls .
                    '">' .
                    $leading .
                    '<span>' .
                    e($label) .
                    '</span></a>';
            };

            $statusDot = function (string $status) {
                return match ($status) {
                    'Expired' => '<span class="inline-flex h-2.5 w-2.5 rounded-full bg-red-500"></span>',
                    'Expiring Soon' => '<span class="inline-flex h-2.5 w-2.5 rounded-full bg-amber-500"></span>',
                    'Fresh' => '<span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>',
                    default => '<span class="inline-flex h-2.5 w-2.5 rounded-full bg-gray-400"></span>',
                };
            };

            $totalCount = (int) ($counts['all'] ?? $items->total());
        @endphp

        <div class="rounded-2xl bg-white border border-gray-200 p-4 mb-6">
            <form method="GET" class="mb-4">
                <label for="inventory_search" class="sr-only">Search food name</label>
                <div class="relative">
                    <input id="inventory_search" type="search" name="q" value="{{ $q }}"
                        placeholder="Search food name..."
                        class="w-full rounded-2xl border-gray-200 bg-white px-4 py-2.5 pl-10 text-sm focus:border-[var(--primary-dark)] focus:ring-[var(--primary-dark)]" />
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                @if (request()->except(['page']) !== [])
                    <div class="mt-3 flex items-center gap-2">
                        <a href="{{ route('inventory.index') }}"
                            class="text-sm font-medium text-gray-600 hover:text-gray-900 underline">Reset filters</a>
                    </div>
                @endif

                <input type="hidden" name="category" value="{{ $selectedCategory }}" />
                <input type="hidden" name="status" value="{{ $selectedStatus }}" />
            </form>

            <div class="flex flex-wrap items-center gap-3">
                {!! $chip(
                    'All (' . $totalCount . ')',
                    ['status' => '', 'category' => '', 'page' => null],
                    $selectedStatus === '' && $selectedCategory === '',
                ) !!}

                @foreach ($statuses as $s)
                    @php
                        $count = (int) ($counts['status'][$s] ?? 0);
                    @endphp
                    {!! $chip(
                        $s . ' (' . $count . ')',
                        ['status' => $s, 'page' => null],
                        $selectedStatus === $s,
                        $statusDot($s) . ' ',
                    ) !!}
                @endforeach

                @foreach ($categories as $cat)
                    {!! $chip($cat, ['category' => $cat, 'page' => null], $selectedCategory === $cat) !!}
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left font-medium px-4 py-3">Item</th>
                            <th class="text-left font-medium px-4 py-3">Category</th>
                            <th class="text-left font-medium px-4 py-3">Qty</th>
                            <th class="text-left font-medium px-4 py-3">Expires</th>
                            <th class="text-left font-medium px-4 py-3">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($items as $item)
                            <tr
                                class="{{ $item->used_at ? 'opacity-70' : ($item->status === 'Expired' ? 'bg-red-50' : ($item->status === 'Expiring Soon' ? 'bg-yellow-50' : '')) }}">
                                <td class="px-4 py-3">
                                    <div>
                                        <div class="font-medium">
                                            <span>{{ $item->name }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500">Purchased:
                                            {{ $item->purchase_date?->format('Y-m-d') ?? '-' }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">{{ $item->category }}</td>
                                <td class="px-4 py-3">{{ $item->quantity }} {{ $item->unit }}</td>
                                <td class="px-4 py-3">{{ $item->expiration_date?->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="text-xs font-medium px-2 py-1 rounded-full
                                        {{ $item->status === 'Expired' ? 'bg-red-100 text-red-700' : ($item->status === 'Expiring Soon' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    @if ($item->status === 'Expiring Soon')
                                        <button type="button"
                                            class="inline-flex items-center rounded-lg bg-[var(--primary)] px-3 py-1.5 text-xs font-semibold text-[var(--text)] hover:bg-[var(--primary-dark)]"
                                            @click="convertAction = '{{ route('inventory.donations.convert', $item) }}'; convertModalOpen = true">
                                            Convert to Donation
                                        </button>
                                        <span class="mx-2 text-gray-300">|</span>
                                    @endif

                                    <a href="{{ route('inventory.edit', $item) }}"
                                        class="inline-flex items-center rounded-lg bg-[var(--secondary)] px-3 py-1.5 text-xs font-semibold text-[var(--text)] ring-1 ring-black/5 hover:brightness-95">
                                        Edit
                                    </a>

                                    <button type="button"
                                        class="ml-2 inline-flex items-center rounded-lg bg-red-500 px-3 py-1.5 text-xs font-semibold text-white ring-1 ring-black/5 hover:bg-red-700"
                                        @click="deleteAction = '{{ route('inventory.destroy', $item) }}'; deleteModalOpen = true">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-600">No items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $items->links() }}
            </div>
        </div>

        <x-modal name="confirm-delete-inventory" :show="false" maxWidth="md">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-[var(--text)]">Delete item?</h2>
                <p class="mt-2 text-sm text-[var(--text-light)]">
                    This action can’t be undone. The item will be removed from your inventory.
                </p>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="button"
                        class="inline-flex items-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-[var(--neutral)] hover:bg-gray-50"
                        @click="deleteModalOpen = false; $dispatch('close-modal', 'confirm-delete-inventory')">
                        Cancel
                    </button>

                    <form method="POST" :action="deleteAction" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white ring-1 ring-black/5 hover:bg-red-700">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </x-modal>

        <x-modal name="confirm-convert-donation" :show="false" maxWidth="md">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-[var(--text)]">Convert to donation?</h2>
                <p class="mt-2 text-sm text-[var(--text-light)]">
                    Are you sure want to convert to donation?
                </p>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="button"
                        class="inline-flex items-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-[var(--neutral)] hover:bg-gray-50"
                        @click="convertModalOpen = false; $dispatch('close-modal', 'confirm-convert-donation')">
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
            x-effect="deleteModalOpen ? $dispatch('open-modal', 'confirm-delete-inventory') : $dispatch('close-modal', 'confirm-delete-inventory')">
        </div>
        <div
            x-effect="convertModalOpen ? $dispatch('open-modal', 'confirm-convert-donation') : $dispatch('close-modal', 'confirm-convert-donation')">
        </div>
    </div>
</x-app-layout>
