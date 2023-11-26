<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\VerifyEmailException;
use App\Http\Controllers\Controller;
use App\Models\Login;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Attempt to log the user into the application.
     */
    protected function attemptLogin(Request $request): bool
    {
        $token = $this->guard()->attempt($this->credentials($request));
        if (!$token) {
            return false;
        }
        $user = $this->guard()->user();
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return false;
        }
        $this->guard()->setToken($token);
        return true;
    }

    /**
     * Send the response after the user was authenticated.
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        $token = (string) $this->guard()->getToken();
        $expiration = $this->guard()->getPayload()->get('exp');
        $loginInformations = $this->storeLoginInformations($request, auth()->user());

        return response()->json([
            'user' => auth()->user(),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration - time(),
            'login_informations' => $loginInformations,
        ]);
    }

    /**
     * Get the failed login response instance.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $user = $this->guard()->user();

        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            throw VerifyEmailException::forUser($user);
        }

        throw ValidationException::withMessages([
            'authLogin' => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $this->updateLastLoginInformations($request, auth()->user());
        $this->guard()->logout();

        return response()->json(null, 204);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return filter_var(request()->authLogin, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'authLogin' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $data = $request->only('authLogin', 'password');
        $data[$this->username()] = $data['authLogin'];
        unset($data['authLogin']);
        return $data;
    }

    /**
     * Store login informations
     *
     * @param Request $request
     * @param User $user
     *
     * @return \App\Models\Login
     */
    protected function storeLoginInformations(Request $request, User $user)
    {
        try {
            return Login::firstOrCreate([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'last_activity' => now(),
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Update last login informations
     *
     * @param Request $request
     * @param User $user
     *
     * @return bool
     */
    protected function updateLastLoginInformations(Request $request, User $user)
    {
        try {
            return Login::where('user_id', $user->id)
                ->where('ip_address', $request->ip())
                ->where('user_agent', $request->userAgent())
                ->orderBy('created_at', 'DESC')
                ->firstOrFail()->update([
                    'last_activity' => now(),
                ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
