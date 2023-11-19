<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskAddRequest extends FormRequest
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
            'rota_id' => 'required|integer|exists:rotas,id',
            'task_id' => 'required|integer|exists:tasks,id',
            'time' => 'required|integer',
            'due_by' => 'required|date',
        ];
    }
}
