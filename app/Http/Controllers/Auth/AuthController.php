<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
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
            'username'      => ['required'],
            'password_new'  => ['required'],
            'remember'      => 'nullable'
        ]);

        try {
            //code...
            $user = User::where('username', $credentials['username'])->first();

            if (empty($user)) {
                return back()->withErrors([
                    'username' => 'Akun tidak ditemukan',
                ])->onlyInput('username');
            }


            if (md5($credentials['password_new']) == $user->password_new) {
                # code...
                Auth::guard('web')->login($user);
                // $request->session()->regenerate();

                return redirect()->intended('home');
            }

            return back()->withErrors([
                'username' => 'Akun tidak ditemukan',
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
