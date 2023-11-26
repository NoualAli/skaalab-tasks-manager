<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\SamePassword;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'min:6', 'confirmed', new SamePassword],
        ];
    }
    /**
     * Get the response for a successful password reset.
     */
    protected function sendResetResponse(Request $request, string $response)
    {
        return ['status' => trans($response)];
    }

    /**
     * Get the response for a failed password reset.
     */
    protected function sendResetFailedResponse(Request $request, string $response)
    {
        return response()->json(['email' => trans($response)], 400);
    }
}