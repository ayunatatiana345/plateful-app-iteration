<?php

namespace Database\Factories;

use App\Models\FoodItem;
use App\Models\MealPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MealPlan>
 */
class MealPlanFactory extends Factory
{
    protected $model = MealPlan::class;

    public function definition(): array
    {
        return [
            'user_id' => null, // set in seeder
            'meal_name' => ucfirst($this->faker->words($this->faker->numberBetween(1, 3), true)),
            'planned_date' => \Carbon\Carbon::now()->addDays($this->faker->numberBetween(-3, 7))->toDateString(),
            'meal_slot' => $this->faker->randomElement(['breakfast', 'lunch', 'dinner', 'snack']),
            'status' => $this->faker->randomElement(['planned', 'completed']),
            // Ingredients will be overridden in seeder when user_id is known.
            'ingredients_used' => null,
        ];
    }

    public function withIngredientsFromInventory(int $userId, int $max = 5): static
    {
        return $this->state(function () use ($userId, $max) {
            $names = FoodItem::query()
                ->where('user_id', $userId)
                ->inRandomOrder()
                ->limit(max(1, $max))
                ->pluck('name')
                ->map(fn($n) => trim((string) $n))
                ->filter()
                ->values()
                ->all();

            return [
                'ingredients_used' => $names === [] ? null : $names,
            ];
        });
    }
}
