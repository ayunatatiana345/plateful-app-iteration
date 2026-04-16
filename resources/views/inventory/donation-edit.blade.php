<x-app-layout>
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Donation Listing</h1>
            <p class="text-sm text-gray-600">Update pickup details or notes for recipients.</p>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 p-6">
            <div class="mb-4">
                <div class="text-sm text-gray-500">Item</div>
                <div class="text-lg font-semibold text-gray-900">{{ $food?->name ?? '—' }}</div>
                <div class="text-sm text-gray-600">{{ $food?->category ?? 'Uncategorized' }} · Expires
                    {{ $food?->expiration_date?->format('Y-m-d') ?? '-' }}</div>
            </div>

            <form method="POST" action="{{ route('inventory.donations.update', $donation) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="food_item_id" value="{{ $donation->food_item_id }}" />

                <div>
                    <label class="text-sm font-medium">Pickup Location <span
                            class="text-xs text-gray-500">(optional)</span></label>
                    <input name="pickup_location" value="{{ old('pickup_location', $donation->pickup_location) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[var(--primary-dark)] focus:ring-[var(--primary-dark)]" />
                </div>

                <div>
                    <label class="text-sm font-medium">Availability <span
                            class="text-xs text-gray-500">(optional)</span></label>
                    <input name="availability" value="{{ old('availability', $donation->availability) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[var(--primary-dark)] focus:ring-[var(--primary-dark)]" />
                </div>

                <div>
                    <label class="text-sm font-medium">Notes for recipient <span
                            class="text-xs text-gray-500">(optional)</span></label>
                    <textarea name="description" rows="4"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-[var(--primary-dark)] focus:ring-[var(--primary-dark)]">{{ old('description', $donation->description) }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('inventory.donations.index') }}"
                        class="rounded-xl border border-gray-200 px-4 py-2 text-sm hover:bg-gray-50">Cancel</a>
                    <button
                        class="rounded-xl bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">Save</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
