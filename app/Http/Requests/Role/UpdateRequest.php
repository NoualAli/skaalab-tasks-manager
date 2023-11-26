<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return isAbleTo('edit_role') && hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = request()->role->id;

        return [
            'name' => ['required', 'unique:roles,name,' . $id . ',id', 'string', 'max:50'],
            'code' => ['required', 'unique:roles,code,' . $id . ',id', 'string', 'max:10'],
        ];
    }
}
