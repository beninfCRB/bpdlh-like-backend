<?php

namespace App\Http\Controllers\Authapi;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AkseslhUserEksternal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Authapi\LoginRequest;
use App\Notifications\RegisterNotification;
use Illuminate\Support\Facades\Notification;

class LoginController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function authenticate(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->only('email_user_eksternal', 'password_user_eksternal');
        dd(Auth::guard('akseslh')->attempt($credentials));
        if (Auth::guard('akseslh')->attempt($credentials)) {
            # code...
        }

        return $this->sendSuccess();
    }
}
