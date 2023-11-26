<?php

namespace App\Http\Requests\Auth;

use App\Rules\IsAlgerianPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'username' => ['required', 'string', 'max:20', 'unique:users'],
            'email' => ['required', 'email:filter', 'unique:users', 'max:100'],
            'first_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', new IsAlgerianPhoneNumber],
            'gender' => ['required', 'boolean'],
            'birthday' => ['nullable', 'date'],
            'password' => ['required', 'min:6', 'confirmed'],
        ];
    }
}