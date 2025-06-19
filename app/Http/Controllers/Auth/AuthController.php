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
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'     => ['required', 'email'],
            'password'  => ['required'],
            'remember'  => 'nullable'
        ]);

        try {
            $user = UserAkseslh::where('email', $credentials['email'])
                ->whereNotIn('role_user', ['maker'])
                ->first();

            if (empty($user)) {
                return back()->withErrors([
                    'email' => 'Akun tidak ditemukan',
                ])->onlyInput('email');
            }

            // crypt(hash('sha256', $password), $verification_code);
            if (Auth::attempt($credentials)) {
                # code...
                $request->session()->regenerate();

                return redirect()->intended('home');
            }

            return back()->withErrors([
                'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
            ])->onlyInput('email');
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
