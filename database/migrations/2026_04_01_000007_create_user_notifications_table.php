<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // food_expiring | food_expired | donation_posted | donation_claimed | meal_reminder
            $table->string('type', 50)->index();

            $table->string('title');
            $table->text('message')->nullable();

            // Link to relevant screen
            $table->string('action_label')->nullable();
            $table->string('action_url')->nullable();

            // Used for de-duping (e.g. "food:123:expiring")
            $table->string('dedupe_key')->nullable()->index();

            $table->timestamp('read_at')->nullable()->index();
            $table->timestamp('dismissed_at')->nullable()->index();

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->unique(['user_id', 'dedupe_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
