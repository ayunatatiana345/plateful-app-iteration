<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationQuickStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'food_item_id' => ['required', 'integer', 'exists:food_items,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'pickup_location' => ['nullable', 'string', 'max:255'],
            'availability' => ['nullable', 'string', 'max:255'],
        ];
    }
}
