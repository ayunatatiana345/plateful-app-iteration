<?php

namespace Database\Factories;

use App\Models\AnalyticsLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnalyticsLog>
 */
class AnalyticsLogFactory extends Factory
{
    protected $model = AnalyticsLog::class;

    public function definition(): array
    {
        return [
            'user_id' => null, // set in seeder
            'food_item_id' => null,
            'action_type' => $this->faker->randomElement([
                'inventory_added',
                'inventory_updated',
                'inventory_deleted',
                'meal_planned',
                'meal_completed',
                'donated',
                'donation_claimed',
            ]),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => now(),
        ];
    }
}
