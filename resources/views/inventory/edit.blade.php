<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Edit Food Item</h1>
            <p class="text-sm text-gray-600">Update quantity, dates, or category.</p>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 p-6">
            <form method="POST" action="{{ route('inventory.update', $item) }}" class="space-y-4" novalidate>
                @csrf
                @method('PUT')

                <div>
                    <label class="text-sm font-medium">Name</label>
                    <input name="name" value="{{ old('name', $item->name) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600" />
                </div>

                <div>
                    <label class="text-sm font-medium">Category</label>
                    <input name="category" value="{{ old('category', $item->category) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Quantity</label>
                        <input type="number" step="0.01" min="0" name="quantity"
                            value="{{ old('quantity', $item->quantity) }}"
                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Unit</label>
                        <input name="unit" value="{{ old('unit', $item->unit) }}"
                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Purchase Date</label>
                        <input type="date" name="purchase_date"
                            value="{{ old('purchase_date', optional($item->purchase_date)->format('Y-m-d')) }}"
                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Expiration Date</label>
                        <input type="date" name="expiration_date"
                            value="{{ old('expiration_date', optional($item->expiration_date)->format('Y-m-d')) }}"
                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Storage Location <span
                                class="text-xs text-gray-500">(optional)</span></label>
                        <input name="storage_location" value="{{ old('storage_location', $item->storage_location) }}"
                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600"
                            placeholder="e.g. Fridge / Freezer / Pantry" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Notes <span
                                class="text-xs text-gray-500">(optional)</span></label>
                        <input name="notes" value="{{ old('notes', $item->notes) }}"
                            class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600"
                            placeholder="e.g. Use for soup" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('inventory.index') }}"
                        class="rounded-xl border border-gray-200 px-4 py-2 text-sm hover:bg-gray-50">Cancel</a>
                    <button type="submit"
                        class="rounded-xl bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
