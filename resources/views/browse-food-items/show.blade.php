<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-[var(--text)]">{{ $item->name }}</h1>
                <p class="text-sm text-[var(--text)]/70">Item details.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('browse.food-items', ['source' => 'inventory']) }}"
                    class="inline-flex items-center justify-center rounded-xl bg-[var(--accent)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:brightness-95">
                    Back to Browse
                </a>

                <a href="{{ route('inventory.edit', $item) }}"
                    class="inline-flex items-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-[var(--neutral)] hover:bg-gray-50">
                    Edit
                </a>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-[var(--neutral)] p-6 shadow-sm">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <dt class="text-xs font-medium text-gray-500">Category</dt>
                    <dd class="mt-1 text-sm font-semibold text-[var(--text)]">{{ $item->category }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500">Quantity</dt>
                    <dd class="mt-1 text-sm font-semibold text-[var(--text)]">{{ $item->quantity }} {{ $item->unit }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500">Purchase Date</dt>
                    <dd class="mt-1 text-sm font-semibold text-[var(--text)]">
                        {{ $item->purchase_date?->format('Y-m-d') ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500">Expiration Date</dt>
                    <dd class="mt-1 text-sm font-semibold text-[var(--text)]">
                        {{ $item->expiration_date?->format('Y-m-d') ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500">Storage</dt>
                    <dd class="mt-1 text-sm font-semibold text-[var(--text)]">{{ $item->storage_location ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500">Notes</dt>
                    <dd class="mt-1 text-sm font-semibold text-[var(--text)]">{{ $item->notes ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span
                            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium
                            {{ $item->status === 'Expired' ? 'bg-red-100 text-red-700' : ($item->status === 'Expiring Soon' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                            {{ $item->status }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500">Used</dt>
                    <dd class="mt-1 text-sm font-semibold text-[var(--text)]">
                        {{ $item->used_at ? $item->used_at->format('Y-m-d H:i') : 'Not yet' }}
                    </dd>
                </div>
            </dl>

            <div class="mt-6 flex flex-wrap items-center justify-end gap-2">
                <a href="{{ route('meal-plans.create', ['ingredients_used' => $item->name]) }}"
                    class="inline-flex items-center rounded-xl bg-[var(--secondary)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:brightness-95">
                    Plan Meal
                </a>

                <form method="POST" action="{{ route('inventory.markUsed', $item) }}" class="inline"
                    onsubmit="return confirm('Mark as used?');">
                    @csrf
                    <button type="submit" {{ $item->used_at ? 'disabled' : '' }}
                        class="inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold ring-1 ring-black/5
                        {{ $item->used_at ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-[var(--accent)] text-[var(--text)] hover:brightness-95' }}">
                        Mark as Used
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
