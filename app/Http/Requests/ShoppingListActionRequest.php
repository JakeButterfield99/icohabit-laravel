<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShoppingListActionRequest extends FormRequest
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
            'list_id' => 'required|integer|exists:shopping_lists,id',
            'operation' => 'required|string',
            'item_id' => 'sometimes|integer|exists:list_items,id',
            'item_name' => 'sometimes|string',
        ];
    }
}
