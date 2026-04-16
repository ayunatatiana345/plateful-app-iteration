<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrivacySettingsUpdateRequest;
use Illuminate\Http\RedirectResponse;

class PrivacySettingsController extends Controller
{
    public function update(PrivacySettingsUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $user->update([
            'privacy_two_factor_enabled' => (bool) ($data['privacy_two_factor_enabled'] ?? false),
            'privacy_food_listing_visibility' => (string) ($data['privacy_food_listing_visibility'] ?? 'public'),
            'privacy_expiry_notifications' => (bool) ($data['privacy_expiry_notifications'] ?? false),
            'privacy_meal_plan_reminders' => (bool) ($data['privacy_meal_plan_reminders'] ?? false),
            'privacy_donation_updates' => (bool) ($data['privacy_donation_updates'] ?? false),
        ]);

        return back()->with('success', 'Privacy settings saved.');
    }
}
