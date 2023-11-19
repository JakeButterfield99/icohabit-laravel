<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DayPlanAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
            'plan_id' => 'required|integer|exists:day_plans,id',
            'time' => ['required', 'string', Rule::in(['breakfast', 'lunch', 'dinner'])],
        ];
    }
}
