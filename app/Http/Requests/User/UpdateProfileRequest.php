<?php

namespace App\Http\Requests\User;

use App\Rules\IsAlgerianPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->id == request()->user;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = auth()->user()->id;

        return [
            // 'username' => ['required', 'string', 'max:30', 'unique:users,username,' . $id . ',id'],
            'email' => ['required', 'email:filter', 'unique:users,email,' . $id . ',id', 'max:100'],
            'first_name' => ['nullable', 'string', 'max:50'],
            'last_name' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', new IsAlgerianPhoneNumber],
        ];
    }
}
