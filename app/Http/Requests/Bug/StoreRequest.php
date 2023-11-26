<?php

namespace App\Http\Requests\Bug;

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
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => ['required', 'in:1,2,3,4,5,6,7'],
            'priority' => ['required', 'in:1,2,3'],
            'description' => ['required', 'max:2000'],
            'media' => ['nullable', 'array'],
            'media.*' => ['required', 'exists:media,id'],
        ];
    }
}
