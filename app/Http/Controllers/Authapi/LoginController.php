<?php

namespace App\Http\Controllers\Authapi;


use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Authapi\LoginRequest;
use App\Models\UserAkseslh;

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

            $user = UserAkseslh::where([
                'email'              => $input['email_pic'],
            ])->first();

            if ($user) {

                if (!$user->password) {

                    return $this->sendError(null, "account is not active yet");
                }

                if ($user->status_user == 'NON ACTIVE') {
                    # code...
                    return $this->sendError(null, "account is not active yet");
                }

                if (Hash::check($input['password'], $user->password)) {

                    // Create token for user to access dashboard
                    $token = $user->createToken("auth")->plainTextToken;

                    // Return token to frontend
                    return $this->sendSuccess([
                        'token'                     => $token,
                        'jenis_kelompok_masyarakat' => $user->data_pic_kelompok_masyarakat->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat,
                        'kelompok_masyarakat_id'    => $user->data_pic_kelompok_masyarakat->kelompok_masyarakat->id,
                        'kelompok_masyarakat'       => $user->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat,
                        'role_user'                 => $user->role_user,

                    ]);
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
