<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventCreateRequest extends FormRequest
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
            'calendar_id' => 'required|integer|exists:calendars,id',
            'name' => 'required|string',
            'datetime' => 'required|date',
            'type' => 'required|string',
            'location_line_1' => 'string',
            'location_line_2' => 'string',
            'location_city' => 'string',
            'location_county' => 'string',
            'location_postcode' => 'string',
        ];
    }
}
