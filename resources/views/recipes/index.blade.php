<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Recipes</h1>
                <p class="text-sm text-gray-600">Search recipes from TheMealDB to help plan meals and use ingredients you
                    already have.</p>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('recipes.index') }}" class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="q" value="{{ $q }}"
                    placeholder="Search e.g. chicken, pasta, soup"
                    class="flex-1 rounded-xl border-gray-200 focus:border-green-600 focus:ring-green-600" />
                <button class="rounded-xl bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700"
                    type="submit">
                    Search
                </button>
            </form>

            @if ($q === '')
                <p class="mt-4 text-sm text-gray-600">Tip: start by searching a main ingredient you want to use up.</p>
            @endif

            @if ($error)
                <p class="mt-4 text-sm text-red-600">{{ $error }}</p>
            @endif
        </div>

        @if ($q !== '')
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($meals as $meal)
                    <a href="{{ route('recipes.show', $meal['idMeal']) }}"
                        class="group rounded-2xl bg-white border border-gray-200 overflow-hidden hover:shadow-sm transition">
                        <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                            @if (!empty($meal['strMealThumb']))
                                <img src="{{ $meal['strMealThumb'] }}" alt="{{ $meal['strMeal'] }}"
                                    class="w-full h-full object-cover group-hover:scale-[1.02] transition" />
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="font-semibold text-gray-900">{{ $meal['strMeal'] }}</div>
                            <div class="text-sm text-gray-600">{{ $meal['strCategory'] ?? '—' }} ·
                                {{ $meal['strArea'] ?? '—' }}</div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-600">No recipes found for "{{ $q }}".
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</x-app-layout>
