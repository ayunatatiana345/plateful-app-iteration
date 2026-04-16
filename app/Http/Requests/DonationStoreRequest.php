<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'food_item_id' => ['required', 'integer', 'exists:food_items,id'],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
