<?php

namespace App\Http\Controllers\Authapi;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserEksternal;
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
        // Get all input
        $input = $request->all();

        try {

            $user = UserEksternal::where([
                'email_user_eksternal'              => $input['email_user_eksternal'],
            ])->first();

            if ($user) {

                if (!$user->password_user_eksternal) {

                    return $this->sendError(null, "account is not active yet");
                }

                if (Hash::check($input['password_user_eksternal'], $user->password_user_eksternal)) {

                    // Create token for user to access dashboard
                    $token = $user->createToken("auth")->plainTextToken;

                    // Return token to frontend
                    return $this->sendSuccess(['token' => $token]);
                }

                return $this->sendError(null, "Credential not match");
            }

            return $this->sendError(null, "User not found");
        } catch (\Throwable $th) {

            // return error
            return $this->sendError(null, env('APP_ENV') == 'local' ? $th->getMessage() : 'Internal server error', 500);
        }
    }
}
