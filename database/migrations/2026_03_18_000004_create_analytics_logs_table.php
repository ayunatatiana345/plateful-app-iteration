<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('food_item_id')->nullable()->constrained('food_items')->nullOnDelete();

            // inventory_added|inventory_updated|inventory_deleted|consumed|wasted|donated|donation_claimed|meal_planned|meal_completed
            $table->string('action_type', 50)->index();

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_logs');
    }
};
