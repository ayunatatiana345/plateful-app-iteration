<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Edit Meal Plan</h1>
            <p class="text-sm text-gray-600">Update meal details or mark as completed.</p>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 p-6">
            <form method="POST" action="{{ route('meal-plans.update', $plan) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-sm font-medium">Meal name</label>
                    <input name="meal_name" value="{{ old('meal_name', $plan->meal_name) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600"
                        required />
                </div>

                <div>
                    <label class="text-sm font-medium">Planned date</label>
                    <input type="date" name="planned_date"
                        value="{{ old('planned_date', optional($plan->planned_date)->format('Y-m-d')) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600"
                        required />
                </div>

                <div>
                    <label class="text-sm font-medium">Ingredients used</label>
                    <input name="ingredients_used" value="{{ old('ingredients_used', $ingredientsText) }}"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600" />
                    <p class="mt-1 text-xs text-gray-500">Separate ingredients with commas.</p>
                </div>

                <div>
                    <label class="text-sm font-medium">Status</label>
                    <select name="status"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600"
                        required>
                        <option value="planned" @selected(old('status', $plan->status) === 'planned')>Planned</option>
                        <option value="completed" @selected(old('status', $plan->status) === 'completed')>Completed</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium">Meal slot</label>
                    <select name="meal_slot"
                        class="mt-1 w-full rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600"
                        required>
                        <option value="breakfast" @selected(old('meal_slot', $plan->meal_slot ?? 'dinner') === 'breakfast')>Breakfast</option>
                        <option value="lunch" @selected(old('meal_slot', $plan->meal_slot ?? 'dinner') === 'lunch')>Lunch</option>
                        <option value="dinner" @selected(old('meal_slot', $plan->meal_slot ?? 'dinner') === 'dinner')>Dinner</option>
                        <option value="snack" @selected(old('meal_slot', $plan->meal_slot ?? 'dinner') === 'snack')>Snacks</option>
                    </select>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                    <form method="POST" action="{{ route('meal-plans.destroy', $plan) }}"
                        onsubmit="return confirm('Delete this meal plan?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                            Delete
                        </button>
                    </form>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('meal-plans.index') }}"
                            class="rounded-xl border border-gray-200 px-4 py-2 text-sm hover:bg-gray-50">Cancel</a>

                        <button type="submit"
                            class="rounded-xl bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-[var(--text)] ring-1 ring-black/5 hover:bg-[var(--primary-dark)]">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
