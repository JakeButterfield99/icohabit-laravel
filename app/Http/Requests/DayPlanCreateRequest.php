<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DayPlanCreateRequest extends FormRequest
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
            'planner_id' => 'required|integer|exists:planners,id',
            'day' => ['required', 'string', Rule::in(['mon', 'tues', 'wed', 'thurs', 'fri', 'sat', 'sun'])],
        ];
    }
}
