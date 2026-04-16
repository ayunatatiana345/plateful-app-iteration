<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealPlanStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'meal_name' => ['required', 'string', 'max:255'],
            'planned_date' => ['required', 'date'],
            'meal_slot' => ['required', 'in:breakfast,lunch,dinner,snack'],
            'status' => ['nullable', 'in:planned,completed'],
            'ingredients_used' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
