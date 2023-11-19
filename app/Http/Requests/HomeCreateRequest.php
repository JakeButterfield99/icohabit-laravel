<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomeCreateRequest extends FormRequest
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
            'name'              => 'required|string',
            'address_line_1'    => 'string',
            'address_line_2'    => 'string',
            'address_city'      => 'string',
            'address_county'    => 'string',
            'address_postcode'  => 'string',
        ];
    }
}
