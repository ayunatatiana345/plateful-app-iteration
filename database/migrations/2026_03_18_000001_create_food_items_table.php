<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('name');
            $table->string('category')->index();

            $table->decimal('quantity', 10, 2)->default(1);
            $table->string('unit', 50)->default('pcs');

            $table->date('purchase_date')->nullable();
            $table->date('expiration_date')->index();

            // Fresh | Expiring Soon | Expired
            $table->string('status', 30)->index();

            $table->timestamps();

            $table->index(['user_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_items');
    }
};
