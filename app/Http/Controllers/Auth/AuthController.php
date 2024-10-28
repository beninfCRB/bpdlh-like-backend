<?php

namespace App\Http\Controllers\Auth;

use App\Models\UserAkseslh;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    //
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'  => ['required'],
            'password'  => ['required'],
            'remember'  => 'nullable'
        ]);

        try {
            //code...
            $user = UserAkseslh::where('email', $credentials['email'])
                ->whereIn('role_user', ['approver', 'pmu-bpdlh'])
                ->first();

            if (empty($user)) {
                return back()->withErrors([
                    'email' => 'Akun tidak ditemukan',
                ])->onlyInput('email');
            }

            // crypt(hash('sha256', $password), $verification_code);
            if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
                # code...
                $request->session()->regenerate();

                return redirect()->intended('home');
            }

            // if (md5($credentials['password_new']) == $user->password_new) {
            //     # code...
            //     Auth::guard('web')->login($user);
            //     // $request->session()->regenerate();

            //     return redirect()->intended('home');
            // }

            return back()->withErrors([
                'username' => 'Akun tidak ditemukan',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', $th->getMessage());
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'     => ['required', 'email'],
            'password'  => ['required'],
        ]);

        if (Auth::attempt($credentials, isset($request->remember))) {
            $request->session()->regenerate();

            return redirect()->intended('home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
