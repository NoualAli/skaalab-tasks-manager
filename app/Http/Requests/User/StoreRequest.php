<?php

namespace App\Http\Requests\User;

use App\Rules\IsAlgerianPhoneNumber;
use App\Rules\UniqueUserRole;
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
        return isAbleTo('create_user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required', 'string', 'max:30', 'unique:users'],
            'email' => ['required', 'email:filter', 'unique:users', 'max:100'],
            'first_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', new IsAlgerianPhoneNumber],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'exists:roles,id'],
            'is_active' => ['required', 'boolean'],
            'gender' => ['required', 'integer', 'in:1,2'],
        ];
    }

    public function messages()
    {
        return [
            'gender.in' => 'Le champ :attribute doit être Homme ou Femme'
        ];
    }
}
