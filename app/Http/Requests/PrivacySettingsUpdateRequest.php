<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrivacySettingsUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 2FA is UI-only for now, but we persist preference.
            'privacy_two_factor_enabled' => ['nullable', 'boolean'],

            'privacy_food_listing_visibility' => ['required', 'in:public,private'],

            'privacy_expiry_notifications' => ['nullable', 'boolean'],
            'privacy_meal_plan_reminders' => ['nullable', 'boolean'],
            'privacy_donation_updates' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // HTML unchecked checkboxes are not sent – normalize to false.
        $this->merge([
            'privacy_two_factor_enabled' => $this->boolean('privacy_two_factor_enabled'),
            'privacy_expiry_notifications' => $this->boolean('privacy_expiry_notifications'),
            'privacy_meal_plan_reminders' => $this->boolean('privacy_meal_plan_reminders'),
            'privacy_donation_updates' => $this->boolean('privacy_donation_updates'),
        ]);
    }
}
