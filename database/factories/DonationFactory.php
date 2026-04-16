<?php

namespace Database\Factories;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Donation>
 */
class DonationFactory extends Factory
{
    protected $model = Donation::class;

    public function definition(): array
    {
        return [
            'donor_id' => null, // set in seeder
            'claimer_id' => null,
            'food_item_id' => null, // set in seeder
            'description' => $this->faker->optional()->sentence(12),
            'pickup_location' => $this->faker->optional()->streetAddress(),
            'availability' => $this->faker->optional()->randomElement([
                'Today 18:00–20:00',
                'Tomorrow 09:00–12:00',
                'Weekend only',
                'Anytime',
            ]),
            'status' => $this->faker->randomElement(['available', 'claimed']),
        ];
    }
}
