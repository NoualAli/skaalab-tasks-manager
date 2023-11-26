<?php

namespace App\Http\Requests\User;

use App\Rules\IsAlgerianPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return isAbleTo('edit_user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = request()->user->id;
        return [
            'username' => ['required', 'string', 'max:30', 'unique:users,username,' . $id . ',id'],
            'email' => ['required', 'email:filter', 'unique:users,email,' . $id . ',id', 'max:100'],
            'first_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', new IsAlgerianPhoneNumber],
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
