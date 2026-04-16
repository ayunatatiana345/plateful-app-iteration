<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('recipes.index', ['q' => request()->query('q')]) }}"
                class="text-sm text-gray-600 hover:text-gray-900">&larr; Back to search</a>
        </div>

        @if ($error)
            <div class="rounded-2xl bg-white border border-gray-200 p-6">
                <p class="text-red-600">{{ $error }}</p>
            </div>
        @elseif (!$meal)
            <div class="rounded-2xl bg-white border border-gray-200 p-6">
                <p class="text-gray-600">Recipe not found.</p>
            </div>
        @else
            <div class="rounded-2xl bg-white border border-gray-200 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="bg-gray-100">
                        @if (!empty($meal['strMealThumb']))
                            <img src="{{ $meal['strMealThumb'] }}" alt="{{ $meal['strMeal'] }}"
                                class="w-full h-full object-cover" />
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h1 class="text-2xl font-semibold text-gray-900">{{ $meal['strMeal'] }}</h1>
                                <p class="mt-2 text-sm text-gray-600">
                                    {{ $meal['strCategory'] ?? '—' }} · {{ $meal['strArea'] ?? '—' }}
                                </p>
                            </div>

                            <a href="{{ route('meal-plans.create', [
                                'meal_name' => $meal['strMeal'] ?? '',
                                'planned_date' => now()->toDateString(),
                                'ingredients_used' => collect(range(1, 20))->map(fn($i) => trim((string) ($meal['strIngredient' . $i] ?? '')))->filter()->values()->implode(', '),
                            ]) }}"
                                class="shrink-0 inline-flex items-center rounded-xl bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                                Add to Meal Plan
                            </a>
                        </div>

                        <div class="mt-5">
                            <h2 class="text-sm font-semibold text-gray-900">Ingredients</h2>
                            <ul class="mt-2 list-disc list-inside text-sm text-gray-700 space-y-1">
                                @for ($i = 1; $i <= 20; $i++)
                                    @php
                                        $ing = trim((string) ($meal['strIngredient' . $i] ?? ''));
                                        $msr = trim((string) ($meal['strMeasure' . $i] ?? ''));
                                    @endphp
                                    @if ($ing !== '')
                                        <li>{{ $ing }}{{ $msr !== '' ? ' — ' . $msr : '' }}</li>
                                    @endif
                                @endfor
                            </ul>
                        </div>

                        @if (!empty($meal['strSource']) || !empty($meal['strYoutube']))
                            <div class="mt-5 flex flex-wrap gap-2">
                                @if (!empty($meal['strSource']))
                                    <a href="{{ $meal['strSource'] }}" target="_blank" rel="noreferrer"
                                        class="rounded-xl border border-gray-200 px-3 py-2 text-sm hover:bg-gray-50">Source</a>
                                @endif
                                @if (!empty($meal['strYoutube']))
                                    <a href="{{ $meal['strYoutube'] }}" target="_blank" rel="noreferrer"
                                        class="rounded-xl border border-gray-200 px-3 py-2 text-sm hover:bg-gray-50">YouTube</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-100 p-6">
                    <h2 class="text-sm font-semibold text-gray-900">Instructions</h2>
                    <div class="mt-2 prose max-w-none prose-sm">
                        {!! nl2br(e($meal['strInstructions'] ?? '')) !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
