<x-app-layout>
    <div class="max-w-7xl mx-auto" x-data="{ removeModalOpen: false, removeAction: '' }">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Donation Listings</h1>
                <p class="text-sm text-gray-600">Items you marked for donation. You can edit details or remove listings.
                </p>
            </div>
            <a href="{{ route('inventory.index') }}"
                class="inline-flex items-center justify-center rounded-xl bg-[var(--accent)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:brightness-95">Back</a>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left font-medium px-4 py-3">Item</th>
                            <th class="text-left font-medium px-4 py-3">Expires</th>
                            <th class="text-left font-medium px-4 py-3">Pickup Location</th>
                            <th class="text-left font-medium px-4 py-3">Availability</th>
                            <th class="text-left font-medium px-4 py-3">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($donations as $donation)
                            @php
                                $food = $donation->foodItem;
                                $needsDetails = empty($donation->pickup_location) || empty($donation->availability);
                            @endphp

                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ $food?->name ?? '—' }}</div>
                                    <div class="text-xs text-gray-500">{{ $food?->category ?? 'Uncategorized' }}</div>
                                </td>
                                <td class="px-4 py-3">{{ $food?->expiration_date?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $donation->pickup_location ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $donation->availability ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span
                                        class="text-xs font-medium px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">{{ ucfirst($donation->status) }}</span>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    @if ($needsDetails)
                                        <a href="{{ route('inventory.donations.edit', $donation) }}"
                                            class="inline-flex items-center justify-center rounded-xl bg-[var(--primary)] px-3 py-1.5 text-xs font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">
                                            Complete details
                                        </a>
                                    @else
                                        <a href="{{ route('inventory.donations.edit', $donation) }}"
                                            class="inline-flex items-center justify-center rounded-xl bg-[var(--secondary)] px-3 py-1.5 text-xs font-semibold text-[var(--text)] ring-1 ring-black/5 hover:brightness-95">Edit</a>
                                    @endif

                                    <button type="button"
                                        class="ml-2 inline-flex items-center justify-center rounded-xl bg-red-600 px-3 py-1.5 text-xs font-semibold text-white ring-1 ring-black/5 hover:bg-red-700"
                                        @click="removeAction='{{ route('inventory.donations.destroy', $donation) }}'; removeModalOpen=true">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-600">No donation listings yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4">
                {{ $donations->links() }}
            </div>
        </div>

        <x-modal name="confirm-remove-donation" :show="false" maxWidth="md">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-[var(--text)]">Remove donation listing?</h2>
                <p class="mt-2 text-sm text-[var(--text-light)]">
                    This will remove the listing from Donation Listings.
                </p>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="button"
                        class="inline-flex items-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-[var(--neutral)] hover:bg-gray-50"
                        @click="removeModalOpen=false; $dispatch('close-modal', 'confirm-remove-donation')">
                        Cancel
                    </button>

                    <form method="POST" :action="removeAction" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white ring-1 ring-black/5 hover:bg-red-700">
                            Remove
                        </button>
                    </form>
                </div>
            </div>
        </x-modal>

        <div
            x-effect="removeModalOpen ? $dispatch('open-modal', 'confirm-remove-donation') : $dispatch('close-modal', 'confirm-remove-donation')">
        </div>
    </div>
</x-app-layout>
