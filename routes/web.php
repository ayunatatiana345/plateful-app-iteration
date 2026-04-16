<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BrowseFoodItemsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\MealPlanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
});

Route::middleware(['auth', 'login.otp'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/browse-food-items', [BrowseFoodItemsController::class, 'index'])->name('browse.food-items');

    Route::get('/browse-food-items/{foodItem}', [BrowseFoodItemsController::class, 'show'])
        ->name('browse.food-items.show');

    // Inventory donation listings (must be defined before /inventory/{inventory} to avoid route shadowing)
    Route::get('/inventory/donations', [\App\Http\Controllers\InventoryDonationController::class, 'index'])
        ->name('inventory.donations.index');

    Route::post('/inventory/donations', [\App\Http\Controllers\InventoryDonationController::class, 'store'])
        ->name('inventory.donations.store');

    Route::get('/inventory/donations/{donation}/edit', [\App\Http\Controllers\InventoryDonationController::class, 'edit'])
        ->name('inventory.donations.edit');

    Route::put('/inventory/donations/{donation}', [\App\Http\Controllers\InventoryDonationController::class, 'update'])
        ->name('inventory.donations.update');

    Route::delete('/inventory/donations/{donation}', [\App\Http\Controllers\InventoryDonationController::class, 'destroy'])
        ->name('inventory.donations.destroy');

    // Convert inventory item to donation (confirmed via modal in UI)
    Route::post('/inventory/{inventory}/convert-to-donation', [\App\Http\Controllers\InventoryDonationController::class, 'quickConvert'])
        ->name('inventory.donations.convert');

    Route::resource('inventory', FoodItemController::class)
        ->parameters(['inventory' => 'inventory'])
        ->except(['show']);

    // (Details page disabled) Access item details only via Browse Food.

    Route::post('/inventory/{inventory}/mark-used', [FoodItemController::class, 'markUsed'])
        ->name('inventory.markUsed');

    Route::resource('meal-plans', MealPlanController::class)
        ->parameters(['meal-plans' => 'meal_plan'])
        ->except(['show']);

    // Donations (deprecated pages removed; keep only endpoints used by Inventory + Browse Food)
    Route::post('/donations/quick', [DonationController::class, 'quickStore'])->name('donations.quickStore');
    Route::post('/donations/{donation}/claim', [DonationController::class, 'claim'])->name('donations.claim');

    // Recipes (TheMealDB)
    Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
    Route::get('/recipes/{idMeal}', [RecipeController::class, 'show'])->name('recipes.show');

    // Analytics dashboard
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Privacy settings
    Route::patch('/profile/privacy', [\App\Http\Controllers\PrivacySettingsController::class, 'update'])
        ->name('profile.privacy.update');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{key}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{key}/mark-unread', [NotificationController::class, 'markUnread'])->name('notifications.markUnread');
    Route::post('/notifications/{key}/dismiss', [NotificationController::class, 'dismiss'])->name('notifications.dismiss');
});

require __DIR__ . '/auth.php';
