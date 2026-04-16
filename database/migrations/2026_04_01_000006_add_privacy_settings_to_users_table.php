<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('privacy_two_factor_enabled')->default(false)->after('remember_token');
            $table->string('privacy_food_listing_visibility', 20)->default('public')->after('privacy_two_factor_enabled');
            $table->boolean('privacy_expiry_notifications')->default(true)->after('privacy_food_listing_visibility');
            $table->boolean('privacy_meal_plan_reminders')->default(true)->after('privacy_expiry_notifications');
            $table->boolean('privacy_donation_updates')->default(true)->after('privacy_meal_plan_reminders');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'privacy_two_factor_enabled',
                'privacy_food_listing_visibility',
                'privacy_expiry_notifications',
                'privacy_meal_plan_reminders',
                'privacy_donation_updates',
            ]);
        });
    }
};
