<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'meal_id' => 'required|integer|exists:meals,id',
            'ingredient_id' => 'required|integer|exists:ingredients,id',
            'amount' => 'required|integer',
            'unit' => 'required|string',
        ];
    }
}
