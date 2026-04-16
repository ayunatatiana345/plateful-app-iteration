<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('donor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('claimer_id')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('food_item_id')->constrained('food_items')->cascadeOnDelete();

            $table->text('description')->nullable();

            // available | claimed
            $table->string('status', 20)->default('available')->index();

            $table->timestamps();

            $table->index(['donor_id', 'status']);
            $table->index(['claimer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
