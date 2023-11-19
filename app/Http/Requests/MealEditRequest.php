<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealEditRequest extends FormRequest
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
            'data' => 'required|json',
        ];
    }
}
