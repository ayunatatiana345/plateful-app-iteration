<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Donation;
use App\Models\FoodItem;

echo 'food_items_total=' . FoodItem::count() . PHP_EOL;
echo 'donations_total=' . Donation::count() . PHP_EOL;

$last = Donation::query()->latest()->first();

if (!$last) {
    echo "no donations rows" . PHP_EOL;
    exit(0);
}

echo 'last_donation_id=' . $last->id . PHP_EOL;
echo 'donor_id=' . $last->donor_id . PHP_EOL;
echo 'food_item_id=' . $last->food_item_id . PHP_EOL;
echo 'status=' . $last->status . PHP_EOL;
