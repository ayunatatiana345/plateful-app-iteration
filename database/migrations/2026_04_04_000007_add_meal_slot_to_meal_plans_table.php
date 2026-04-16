<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meal_plans', function (Blueprint $table) {
            if (! Schema::hasColumn('meal_plans', 'meal_slot')) {
                $table->string('meal_slot', 20)->default('dinner')->after('planned_date')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('meal_plans', function (Blueprint $table) {
            if (Schema::hasColumn('meal_plans', 'meal_slot')) {
                $table->dropColumn('meal_slot');
            }
        });
    }
};
