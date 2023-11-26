<?php

namespace App\Http\Controllers\Api\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(UpdatePasswordRequest $request)
    {
        $user = auth()->user();
        try {
            $user->password = Hash::make($request->password);
            if (request()->has('mustChangePassword') && request()->mustChangePassword) {
                $user->must_change_password = false;
            }
            $user->save();

            return response()->json([
                'message' => UPDATE_PASSWORD_SUCCESS,
                'status' => true
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ], 500);
        }
    }
}