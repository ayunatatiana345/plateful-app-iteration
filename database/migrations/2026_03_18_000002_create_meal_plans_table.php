<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_plans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('meal_name');
            $table->date('planned_date')->index();

            // planned | completed
            $table->string('status', 20)->default('planned')->index();

            // Store as JSON array of strings (ingredient names).
            $table->json('ingredients_used')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'planned_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_plans');
    }
};
