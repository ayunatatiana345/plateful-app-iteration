<x-app-layout>
    <div class="max-w-3xl mx-auto" x-data="{ deleteModalOpen: false, convertModalOpen: false }">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">{{ $item->name }}</h1>
                <p class="text-sm text-gray-600">Inventory item details.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('inventory.edit', $item) }}"
                    class="inline-flex items-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-200 hover:bg-gray-50">
                    Edit
                </a>

                <button type="button"
                    class="inline-flex items-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-red-700 ring-1 ring-gray-200 hover:bg-gray-50"
                    @click="deleteModalOpen = true">
                    Delete
                </button>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 p-6">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <dt class="text-xs font-medium text-gray-500">Category</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $item->category }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500">Quantity</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $item->quantity }} {{ $item->unit }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500">Purchase Date</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $item->purchase_date?->format('Y-m-d') ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium text-gray-500">Expiration Date</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $item->expiration_date?->format('Y-m-d') ?? '-' }}
                    </dd>
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
                    <dd class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $item->used_at ? $item->used_at->format('Y-m-d H:i') : 'Not yet' }}
                    </dd>
                </div>
            </dl>

            <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
                <a href="{{ route('inventory.index') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-[var(--accent)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:brightness-95">
                    Back to inventory
                </a>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('meal-plans.create', ['ingredients_used' => $item->name]) }}"
                        class="inline-flex items-center rounded-xl bg-[var(--secondary)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:brightness-95">
                        Move to Meal Plan
                    </a>

                    @if ($item->status === 'Expiring Soon')
                        <button type="button"
                            class="inline-flex items-center rounded-xl bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]"
                            @click="convertModalOpen = true">
                            Convert to Donation
                        </button>
                    @endif

                    <form method="POST" action="{{ route('inventory.markUsed', $item) }}" class="inline">
                        @csrf
                        <button type="submit" {{ $item->used_at ? 'disabled' : '' }}
                            class="inline-flex items-center rounded-xl px-4 py-2 text-sm font-semibold ring-1 ring-black/5
                            {{ $item->used_at ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-[var(--primary)] text-[var(--text)] hover:bg-[var(--primary-dark)]' }}">
                            Mark as Used
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <x-modal name="confirm-delete-inventory-show" :show="false" maxWidth="md">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-[var(--text)]">Delete item?</h2>
                <p class="mt-2 text-sm text-[var(--text-light)]">
                    This action can’t be undone. The item will be removed from your inventory.
                </p>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="button"
                        class="inline-flex items-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-[var(--neutral)] hover:bg-gray-50"
                        @click="deleteModalOpen = false; $dispatch('close-modal', 'confirm-delete-inventory-show')">
                        Cancel
                    </button>

                    <form method="POST" action="{{ route('inventory.destroy', $item) }}" class="inline">
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

        <x-modal name="confirm-convert-donation-show" :show="false" maxWidth="md">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-[var(--text)]">Convert to donation?</h2>
                <p class="mt-2 text-sm text-[var(--text-light)]">
                    Are you sure want to convert to donation?
                </p>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="button"
                        class="inline-flex items-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-[var(--neutral)] hover:bg-gray-50"
                        @click="convertModalOpen=false; $dispatch('close-modal', 'confirm-convert-donation-show')">
                        Cancel
                    </button>

                    <form method="POST" action="{{ route('inventory.donations.convert', $item) }}" class="inline">
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
            x-effect="convertModalOpen ? $dispatch('open-modal', 'confirm-convert-donation-show') : $dispatch('close-modal', 'confirm-convert-donation-show')">
        </div>

        <div
            x-effect="deleteModalOpen ? $dispatch('open-modal', 'confirm-delete-inventory-show') : $dispatch('close-modal', 'confirm-delete-inventory-show')">
        </div>
    </div>
</x-app-layout>
