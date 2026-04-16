<?php

namespace Database\Seeders;

use App\Models\AnalyticsLog;
use App\Models\Donation;
use App\Models\FoodItem;
use App\Models\MealPlan;
use App\Models\User;
use App\Services\FoodStatusService;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- Users (realistic demo accounts) ---
        $users = collect([
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ],
            [
                'name' => 'Alya Putri',
                'email' => 'alya@example.com',
            ],
            [
                'name' => 'Bima Pratama',
                'email' => 'bima@example.com',
            ],
            [
                'name' => 'Citra Santoso',
                'email' => 'citra@example.com',
            ],
            [
                'name' => 'Dimas Saputra',
                'email' => 'dimas@example.com',
            ],
            [
                'name' => 'Nadia Rahma',
                'email' => 'nadia@example.com',
            ],
        ])->map(function (array $u) {
            return User::query()->firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make('password'),
                ]
            );
        });

        // --- Inventory (real example items) ---
        $today = Carbon::today();

        $inventoryByEmail = [
            'test@example.com' => [
                // Expiring soon / expired / fresh mix
                ['name' => 'Eggs', 'category' => 'Dairy', 'quantity' => 10, 'unit' => 'pcs', 'purchase' => $today->copy()->subDays(5), 'expire' => $today->copy()->addDays(2)],
                ['name' => 'Milk', 'category' => 'Dairy', 'quantity' => 1, 'unit' => 'L', 'purchase' => $today->copy()->subDays(3), 'expire' => $today->copy()->addDays(4)],
                ['name' => 'Spinach', 'category' => 'Vegetables', 'quantity' => 2, 'unit' => 'pack', 'purchase' => $today->copy()->subDays(2), 'expire' => $today->copy()->addDays(1)],
                ['name' => 'Chicken Breast', 'category' => 'Meat', 'quantity' => 1, 'unit' => 'kg', 'purchase' => $today->copy()->subDays(1), 'expire' => $today->copy()->addDays(3)],
                ['name' => 'Tomatoes', 'category' => 'Vegetables', 'quantity' => 6, 'unit' => 'pcs', 'purchase' => $today->copy()->subDays(4), 'expire' => $today->copy()->addDays(3)],
                ['name' => 'Bananas', 'category' => 'Fruits', 'quantity' => 6, 'unit' => 'pcs', 'purchase' => $today->copy()->subDays(6), 'expire' => $today->copy()->subDays(1)],
                ['name' => 'Rice', 'category' => 'Grains', 'quantity' => 2, 'unit' => 'kg', 'purchase' => $today->copy()->subDays(30), 'expire' => $today->copy()->addDays(365)],
                ['name' => 'Bread', 'category' => 'Grains', 'quantity' => 1, 'unit' => 'pack', 'purchase' => $today->copy()->subDays(2), 'expire' => $today->copy()->addDays(2)],
                ['name' => 'Cheese', 'category' => 'Dairy', 'quantity' => 250, 'unit' => 'g', 'purchase' => $today->copy()->subDays(10), 'expire' => $today->copy()->addDays(7)],
                ['name' => 'Yogurt', 'category' => 'Dairy', 'quantity' => 2, 'unit' => 'pcs', 'purchase' => $today->copy()->subDays(7), 'expire' => $today->copy()->addDays(1)],
            ],
            'alya@example.com' => [
                ['name' => 'Carrots', 'category' => 'Vegetables', 'quantity' => 500, 'unit' => 'g', 'purchase' => $today->copy()->subDays(3), 'expire' => $today->copy()->addDays(6)],
                ['name' => 'Apples', 'category' => 'Fruits', 'quantity' => 6, 'unit' => 'pcs', 'purchase' => $today->copy()->subDays(4), 'expire' => $today->copy()->addDays(9)],
                ['name' => 'Tofu', 'category' => 'Protein', 'quantity' => 2, 'unit' => 'pcs', 'purchase' => $today->copy()->subDays(1), 'expire' => $today->copy()->addDays(3)],
                ['name' => 'Chili Sauce', 'category' => 'Sauce', 'quantity' => 1, 'unit' => 'bottle', 'purchase' => $today->copy()->subDays(20), 'expire' => $today->copy()->addDays(120)],
            ],
            'bima@example.com' => [
                ['name' => 'Instant Noodles', 'category' => 'Snacks', 'quantity' => 5, 'unit' => 'pack', 'purchase' => $today->copy()->subDays(15), 'expire' => $today->copy()->addDays(200)],
                ['name' => 'Ground Beef', 'category' => 'Meat', 'quantity' => 500, 'unit' => 'g', 'purchase' => $today->copy()->subDays(2), 'expire' => $today->copy()->addDays(1)],
                ['name' => 'Lettuce', 'category' => 'Vegetables', 'quantity' => 1, 'unit' => 'pcs', 'purchase' => $today->copy()->subDays(2), 'expire' => $today->copy()->addDays(2)],
            ],
            'citra@example.com' => [
                ['name' => 'Oranges', 'category' => 'Fruits', 'quantity' => 8, 'unit' => 'pcs', 'purchase' => $today->copy()->subDays(3), 'expire' => $today->copy()->addDays(10)],
                ['name' => 'Butter', 'category' => 'Dairy', 'quantity' => 200, 'unit' => 'g', 'purchase' => $today->copy()->subDays(12), 'expire' => $today->copy()->addDays(20)],
            ],
            'dimas@example.com' => [
                ['name' => 'Salmon Fillet', 'category' => 'Meat', 'quantity' => 2, 'unit' => 'pcs', 'purchase' => $today->copy()->subDays(1), 'expire' => $today->copy()->addDays(2)],
                ['name' => 'Broccoli', 'category' => 'Vegetables', 'quantity' => 1, 'unit' => 'pcs', 'purchase' => $today->copy()->subDays(2), 'expire' => $today->copy()->addDays(3)],
            ],
            'nadia@example.com' => [
                ['name' => 'Oat Milk', 'category' => 'Beverages', 'quantity' => 1, 'unit' => 'L', 'purchase' => $today->copy()->subDays(6), 'expire' => $today->copy()->addDays(5)],
                ['name' => 'Strawberries', 'category' => 'Fruits', 'quantity' => 2, 'unit' => 'pack', 'purchase' => $today->copy()->subDays(2), 'expire' => $today->copy()->addDays(1)],
            ],
        ];

        $foodItemsByUserId = [];

        foreach ($users as $user) {
            $items = $inventoryByEmail[$user->email] ?? [];

            $foodItemsByUserId[$user->id] = collect($items)->map(function (array $i) use ($user, $today) {
                $exp = Carbon::parse($i['expire'])->startOfDay();
                $status = FoodStatusService::STATUS_FRESH;

                if ($exp->lt($today)) {
                    $status = FoodStatusService::STATUS_EXPIRED;
                } elseif ($today->diffInDays($exp, false) <= 3) {
                    $status = FoodStatusService::STATUS_EXPIRING_SOON;
                }

                return FoodItem::query()->create([
                    'user_id' => $user->id,
                    'name' => $i['name'],
                    'category' => $i['category'],
                    'quantity' => $i['quantity'],
                    'unit' => $i['unit'],
                    'purchase_date' => Carbon::parse($i['purchase'])->toDateString(),
                    'expiration_date' => Carbon::parse($i['expire'])->toDateString(),
                    'status' => $status,
                ]);
            });
        }

        // --- Meal plans (week plan distributed evenly) ---
        $testUser = $users->firstWhere('email', 'test@example.com');

        if ($testUser) {
            $weekStart = Carbon::today()->startOfWeek(Carbon::MONDAY);

            $inventoryNames = ($foodItemsByUserId[$testUser->id] ?? collect())
                ->pluck('name')
                ->map(fn($n) => trim((string) $n))
                ->filter()
                ->values();

            $pickIngredients = function (int $count) use ($inventoryNames): array {
                if ($inventoryNames->isEmpty()) {
                    return [];
                }

                return $inventoryNames
                    ->shuffle()
                    ->take(max(1, $count))
                    ->values()
                    ->all();
            };

            // Seed a more even distribution: 2 meals/day (lunch + dinner) across the week,
            // plus a few breakfasts/snacks to avoid empty slots.
            $slotsByDay = [
                'mon' => ['lunch', 'dinner'],
                'tue' => ['lunch', 'dinner'],
                'wed' => ['lunch', 'dinner'],
                'thu' => ['lunch', 'dinner'],
                'fri' => ['lunch', 'dinner'],
                'sat' => ['breakfast', 'dinner'],
                'sun' => ['breakfast', 'snack'],
            ];

            $slotTitles = [
                'breakfast' => ['Quick Breakfast', 'Oat Bowl', 'Egg Toast', 'Fruit & Yogurt'],
                'lunch' => ['Lunch Bowl', 'Fresh Salad', 'Quick Wrap', 'Rice Plate'],
                'dinner' => ['Stir Fry', 'Simple Soup', 'Pan Meal', 'Home Dinner'],
                'snack' => ['Snack Box', 'Light Bites', 'Mini Plate', 'Quick Snack'],
            ];

            $dayIndex = 0;
            foreach ($slotsByDay as $dow => $slots) {
                $date = $weekStart->copy()->addDays($dayIndex)->toDateString();

                foreach ($slots as $slot) {
                    $title = collect($slotTitles[$slot] ?? ['Meal'])->random();

                    MealPlan::query()->create([
                        'user_id' => $testUser->id,
                        'meal_name' => $title,
                        'planned_date' => $date,
                        'meal_slot' => $slot,
                        'status' => (random_int(1, 10) <= 2) ? 'completed' : 'planned',
                        'ingredients_used' => $pickIngredients(random_int(2, 5)) ?: null,
                    ]);
                }

                $dayIndex++;
            }
        }

        // --- Donations (real examples) ---
        $aly = $users->firstWhere('email', 'alya@example.com');
        $bima = $users->firstWhere('email', 'bima@example.com');
        $citra = $users->firstWhere('email', 'citra@example.com');
        $dimas = $users->firstWhere('email', 'dimas@example.com');
        $nadia = $users->firstWhere('email', 'nadia@example.com');

        $testItems = $foodItemsByUserId[$testUser->id] ?? collect();
        $spinach = $testItems->firstWhere('name', 'Spinach');
        $bread = $testItems->firstWhere('name', 'Bread');

        if ($spinach) {
            Donation::query()->firstOrCreate(
                ['food_item_id' => $spinach->id, 'status' => 'available'],
                [
                    'donor_id' => $testUser->id,
                    'claimer_id' => null,
                    'description' => 'Extra spinach pack (still fresh). Pickup today or tomorrow.',
                    'pickup_location' => 'Front gate',
                    'availability' => 'Today 18:00–20:00',
                    'status' => 'available',
                ]
            );
        }

        if ($bread) {
            Donation::query()->firstOrCreate(
                ['food_item_id' => $bread->id, 'status' => 'claimed'],
                [
                    'donor_id' => $testUser->id,
                    'claimer_id' => $aly?->id,
                    'description' => 'Unopened bread pack. Claimed by Alya.',
                    'pickup_location' => 'Lobby',
                    'availability' => 'Tomorrow 09:00–12:00',
                    'status' => 'claimed',
                ]
            );

            // If it has been claimed, consider it "used" (given away) in the donor inventory
            $bread->forceFill(['used_at' => $today->copy()->subDays(1)->addHours(17)])->save();
        }

        // Seed SOME available donations from other households so Browse Food is never empty
        $seedAvailableDonationForUser = function (?User $donor, string $itemName, string $pickup, string $availability) use ($foodItemsByUserId) {
            if (!$donor) {
                return;
            }

            $items = $foodItemsByUserId[$donor->id] ?? collect();
            /** @var \App\Models\FoodItem|null $item */
            $item = $items->firstWhere('name', $itemName);

            if (!$item) {
                return;
            }

            if ($item->status === FoodStatusService::STATUS_EXPIRED || $item->used_at) {
                return;
            }

            Donation::query()->firstOrCreate(
                ['food_item_id' => $item->id, 'status' => 'available'],
                [
                    'donor_id' => $donor->id,
                    'claimer_id' => null,
                    'description' => 'Available for donation.',
                    'pickup_location' => $pickup,
                    'availability' => $availability,
                    'status' => 'available',
                ]
            );
        };

        $seedAvailableDonationForUser($aly, 'Tofu', 'Apartment lobby', 'Today 18:00–20:00');
        $seedAvailableDonationForUser($bima, 'Lettuce', 'Front gate', 'Tomorrow 09:00–12:00');
        $seedAvailableDonationForUser($citra, 'Oranges', 'Security post', 'Anytime');
        $seedAvailableDonationForUser($dimas, 'Broccoli', 'Lobby', 'Weekend only');
        $seedAvailableDonationForUser($nadia, 'Strawberries', 'Front gate', 'Today 17:00–19:00');

        // --- Analytics logs (realistic sample events) ---
        $sampleLogs = [
            // Inventory actions
            ['user_id' => $testUser->id, 'food_item_id' => $spinach?->id, 'action_type' => 'inventory_added', 'created_at' => $today->copy()->subDays(5)->addHours(9)],
            ['user_id' => $testUser->id, 'food_item_id' => $bread?->id, 'action_type' => 'inventory_added', 'created_at' => $today->copy()->subDays(2)->addHours(10)],

            // Meal plans
            ['user_id' => $testUser->id, 'food_item_id' => null, 'action_type' => 'meal_planned', 'created_at' => $today->copy()->subDays(1)->addHours(18)],
            ['user_id' => $testUser->id, 'food_item_id' => null, 'action_type' => 'meal_completed', 'created_at' => $today->copy()->subDays(1)->addHours(20)],

            // Donations
            ['user_id' => $testUser->id, 'food_item_id' => $spinach?->id, 'action_type' => 'donated', 'created_at' => $today->copy()->subDays(1)->addHours(16)],
            ['user_id' => $aly?->id, 'food_item_id' => $bread?->id, 'action_type' => 'donation_claimed', 'created_at' => $today->copy()->subDays(1)->addHours(17)],

            // Other users (a few examples)
            ['user_id' => $aly?->id, 'food_item_id' => null, 'action_type' => 'meal_planned', 'created_at' => $today->copy()->subDays(2)->addHours(19)],
            ['user_id' => $bima?->id, 'food_item_id' => null, 'action_type' => 'meal_planned', 'created_at' => $today->copy()->subDays(3)->addHours(12)],
        ];

        foreach ($sampleLogs as $log) {
            if (empty($log['user_id'])) {
                continue;
            }

            AnalyticsLog::query()->create([
                'user_id' => $log['user_id'],
                'food_item_id' => $log['food_item_id'] ?? null,
                'action_type' => $log['action_type'],
                'created_at' => $log['created_at'],
                'updated_at' => $log['created_at'],
            ]);
        }
    }
}
