<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Create Meal Plan</h1>
            <p class="text-sm text-gray-600">Plan a meal for a specific day of the week.</p>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 p-6">
            <form method="POST" action="{{ route('meal-plans.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="text-sm font-medium">Meal name</label>
                    <input name="meal_name" value="{{ old('meal_name', $prefillMealName ?? '') }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600"
                        required />
                </div>

                <div>
                    <label class="text-sm font-medium">Planned date</label>
                    <input type="date" name="planned_date" value="{{ old('planned_date', $plannedDate) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600"
                        required />
                </div>

                <div>
                    <label class="text-sm font-medium">Ingredients used</label>
                    <input name="ingredients_used" value="{{ old('ingredients_used', $prefillIngredientsUsed ?? '') }}"
                        placeholder="e.g. eggs, rice, spinach"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600" />
                    <p class="mt-1 text-xs text-gray-500">Separate ingredients with commas.</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Status</label>
                    <select name="status"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600">
                        <option value="planned" @selected(old('status', 'planned') === 'planned')>Planned</option>
                        <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Meal slot</label>
                    <select name="meal_slot"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600"
                        required>
                        <option value="breakfast" @selected(old('meal_slot') === 'breakfast')>Breakfast</option>
                        <option value="lunch" @selected(old('meal_slot') === 'lunch')>Lunch</option>
                        <option value="dinner" @selected(old('meal_slot', 'dinner') === 'dinner')>Dinner</option>
                        <option value="snack" @selected(old('meal_slot') === 'snack')>Snacks</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('meal-plans.index') }}"
                        class="rounded-xl border border-gray-200 px-4 py-2 text-sm hover:bg-gray-50">Cancel</a>
                    <button type="submit"
                        class="rounded-xl bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">Save</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
