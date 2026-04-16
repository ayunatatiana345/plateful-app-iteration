<?php

namespace Database\Factories;

use App\Models\FoodItem;
use App\Services\FoodStatusService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FoodItem>
 */
class FoodItemFactory extends Factory
{
    protected $model = FoodItem::class;

    public function definition(): array
    {
        $purchase = $this->faker->dateTimeBetween('-14 days', 'now');
        $expiry = $this->faker->dateTimeBetween('-3 days', '+14 days');

        $expirationDate = \Carbon\Carbon::parse($expiry)->toDateString();

        // Compute status similarly to FoodStatusService (simple rule)
        $today = \Carbon\Carbon::today();
        $exp = \Carbon\Carbon::parse($expirationDate)->startOfDay();

        $status = FoodStatusService::STATUS_FRESH;
        if ($exp->lt($today)) {
            $status = FoodStatusService::STATUS_EXPIRED;
        } elseif ($today->diffInDays($exp, false) <= 3) {
            $status = FoodStatusService::STATUS_EXPIRING_SOON;
        }

        return [
            'user_id' => null, // set in seeder
            'name' => ucfirst($this->faker->words($this->faker->numberBetween(1, 3), true)),
            'category' => $this->faker->randomElement(['Vegetables', 'Fruits', 'Meat', 'Dairy', 'Grains', 'Snacks', 'Beverages']),
            'storage_location' => $this->faker->optional()->randomElement(['Fridge', 'Freezer', 'Pantry']),
            'notes' => $this->faker->optional()->sentence(8),
            'quantity' => $this->faker->randomFloat(2, 1, 5),
            'unit' => $this->faker->randomElement(['pcs', 'kg', 'g', 'L', 'ml', 'pack']),
            'purchase_date' => \Carbon\Carbon::parse($purchase)->toDateString(),
            'expiration_date' => $expirationDate,
            'status' => $status,
        ];
    }
}
