<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\AnalyticsLog;
use App\Models\Donation;
use App\Models\FoodItem;
use App\Models\MealPlan;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'household_size',
        'privacy_two_factor_enabled',
        'privacy_food_listing_visibility',
        'privacy_expiry_notifications',
        'privacy_meal_plan_reminders',
        'privacy_donation_updates',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'privacy_two_factor_enabled' => 'boolean',
            'privacy_expiry_notifications' => 'boolean',
            'privacy_meal_plan_reminders' => 'boolean',
            'privacy_donation_updates' => 'boolean',
        ];
    }

    public function foodItems(): HasMany
    {
        return $this->hasMany(FoodItem::class);
    }

    public function mealPlans(): HasMany
    {
        return $this->hasMany(MealPlan::class);
    }

    public function donationsMade(): HasMany
    {
        return $this->hasMany(Donation::class, 'donor_id');
    }

    public function donationsClaimed(): HasMany
    {
        return $this->hasMany(Donation::class, 'claimer_id');
    }

    public function analyticsLogs(): HasMany
    {
        return $this->hasMany(AnalyticsLog::class);
    }
}
