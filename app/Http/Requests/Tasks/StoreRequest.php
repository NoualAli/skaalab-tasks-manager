<?php

namespace App\Http\Requests\Tasks;

use App\Rules\MaxLengthQuill;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return isAbleTo('create_task');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255', 'unique:tasks'],
            'priority' => ['required', 'in:1,2,3'],
            'content' => ['nullable', new MaxLengthQuill(1000)],
            'deadline' => ['nullable', 'date', 'after_or_equal:' . today()->format('Y-m-d')],
            'assigned_to_id' => ['nullable', 'exists:users,id'],
        ];
    }

    public function messages()
    {
        return [
            'priority.in' => 'La valeur du champ :attribute doit être: Normal, Moyenne, Urgente'
        ];
    }
}
