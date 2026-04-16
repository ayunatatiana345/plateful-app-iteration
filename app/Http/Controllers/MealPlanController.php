<?php

namespace App\Http\Controllers;

use App\Http\Requests\MealPlanStoreRequest;
use App\Http\Requests\MealPlanUpdateRequest;
use App\Models\AnalyticsLog;
use App\Models\FoodItem;
use App\Models\MealPlan;
use App\Services\FoodStatusService;
use App\Services\SuggestedRecipeService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MealPlanController extends Controller
{
    public function index(Request $request, SuggestedRecipeService $suggestedRecipeService): View
    {
        $userId = $request->user()->id;

        $weekStart = $request->query('week_start')
            ? Carbon::parse($request->query('week_start'))->startOfDay()
            : Carbon::now()->startOfWeek(Carbon::MONDAY);

        $weekEnd = (clone $weekStart)->addDays(6)->endOfDay();

        $slotOrder = "CASE meal_slot WHEN 'breakfast' THEN 1 WHEN 'lunch' THEN 2 WHEN 'dinner' THEN 3 WHEN 'snack' THEN 4 ELSE 99 END";

        $plans = MealPlan::query()
            ->where('user_id', $userId)
            ->whereBetween('planned_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->orderBy('planned_date')
            ->orderByRaw($slotOrder)
            ->orderBy('meal_name')
            ->get()
            ->groupBy(fn(MealPlan $p) => $p->planned_date->toDateString());

        $inventory = FoodItem::query()
            ->where('user_id', $userId)
            ->where('status', '!=', FoodStatusService::STATUS_EXPIRED)
            ->orderBy('expiration_date')
            ->limit(50)
            ->get();

        $suggestedRecipes = $suggestedRecipeService->suggestFromInventory($inventory, 12);

        return view('meal-plans.index', [
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'plans' => $plans,
            'suggestedRecipes' => $suggestedRecipes,
        ]);
    }

    public function create(Request $request): View
    {
        $plannedDate = $request->query('planned_date')
            ? Carbon::parse($request->query('planned_date'))->toDateString()
            : Carbon::now()->toDateString();

        return view('meal-plans.create', [
            'plannedDate' => $plannedDate,
            'prefillMealName' => trim((string) $request->query('meal_name', '')),
            'prefillIngredientsUsed' => trim((string) $request->query('ingredients_used', '')),
        ]);
    }

    public function store(MealPlanStoreRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $ingredients = $this->parseIngredients($data['ingredients_used'] ?? null);

        $plan = MealPlan::query()->create([
            'user_id' => $user->id,
            'meal_name' => $data['meal_name'],
            'planned_date' => $data['planned_date'],
            'meal_slot' => $data['meal_slot'],
            'status' => $data['status'] ?? 'planned',
            'ingredients_used' => $ingredients,
        ]);

        AnalyticsLog::query()->create([
            'user_id' => $user->id,
            'food_item_id' => null,
            'action_type' => 'meal_planned',
        ]);

        return redirect()
            ->route('meal-plans.index', ['week_start' => Carbon::parse($plan->planned_date)->startOfWeek(Carbon::MONDAY)->toDateString()])
            ->with('success', 'Meal plan created.');
    }

    public function edit(Request $request, MealPlan $meal_plan): View
    {
        abort_unless($meal_plan->user_id === $request->user()->id, 403);

        return view('meal-plans.edit', [
            'plan' => $meal_plan,
            'ingredientsText' => implode(", ", $meal_plan->ingredients_used ?? []),
        ]);
    }

    public function update(MealPlanUpdateRequest $request, MealPlan $meal_plan): RedirectResponse
    {
        abort_unless($meal_plan->user_id === $request->user()->id, 403);

        $data = $request->validated();

        $meal_plan->update([
            'meal_name' => $data['meal_name'],
            'planned_date' => $data['planned_date'],
            'meal_slot' => $data['meal_slot'],
            'status' => $data['status'],
            'ingredients_used' => $this->parseIngredients($data['ingredients_used'] ?? null),
        ]);

        if ($data['status'] === 'completed') {
            AnalyticsLog::query()->create([
                'user_id' => $request->user()->id,
                'food_item_id' => null,
                'action_type' => 'meal_completed',
            ]);
        }

        return redirect()
            ->route('meal-plans.index', ['week_start' => Carbon::parse($meal_plan->planned_date)->startOfWeek(Carbon::MONDAY)->toDateString()])
            ->with('success', 'Meal plan updated.');
    }

    public function destroy(Request $request, MealPlan $meal_plan): RedirectResponse
    {
        abort_unless($meal_plan->user_id === $request->user()->id, 403);

        $weekStart = Carbon::parse($meal_plan->planned_date)->startOfWeek(Carbon::MONDAY)->toDateString();

        $meal_plan->delete();

        return redirect()
            ->route('meal-plans.index', ['week_start' => $weekStart])
            ->with('success', 'Meal plan deleted.');
    }

    private function parseIngredients(?string $ingredientsText): ?array
    {
        $ingredientsText = $ingredientsText ? trim($ingredientsText) : '';

        if ($ingredientsText === '') {
            return null;
        }

        $parts = preg_split('/\s*,\s*/', $ingredientsText) ?: [];

        $parts = array_values(array_filter(array_map(static fn($p) => trim((string) $p), $parts), static fn($p) => $p !== ''));

        return $parts === [] ? null : $parts;
    }
}
