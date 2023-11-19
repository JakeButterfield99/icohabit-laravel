<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomeUpdateRequest extends FormRequest
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
            'name'              => 'string|nullable',
            'address_line_1'    => 'string|nullable',
            'address_line_2'    => 'string|nullable',
            'address_city'      => 'string|nullable',
            'address_county'    => 'string|nullable',
            'address_postcode'  => 'string|nullable',
        ];
    }
}
