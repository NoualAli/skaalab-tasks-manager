<?php

namespace App\Http\Requests\User;

use App\Rules\CurrentPassword;
use App\Rules\SamePassword;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
        return [
            'current_password' => ['required', new CurrentPassword],
            'password' => ['required', 'min:6', 'confirmed', new SamePassword],
        ];
    }
}