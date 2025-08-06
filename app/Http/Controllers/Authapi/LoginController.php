<?php

namespace App\Http\Controllers\Authapi;


use App\Models\UserAkseslh;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Authapi\LoginRequest;

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

    public function authenticate(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'email_pic'     => 'required|email',
            'password'      => 'required'
        ]);

        if ($validator->fails()) {
            # code...
            \Sentry\captureMessage('Validate Message: ' . $request->email_pic . ' ' . $validator->getMessageBag(), \Sentry\Severity::warning());
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        // Get all input
        $input = $validator->validated();

        try {

            $user = UserAkseslh::where([
                'email'              => $input['email_pic'],
            ])->first();

            if ($user) {

                if (!$user->password) {

                    \Sentry\captureMessage('Validate Message: ' . $request->email_pic . ' Akun dinonaktifkan', \Sentry\Severity::warning());
                    return $this->sendError(null, ['error' => [['Akun dinonaktifkan']]], 422);
                }

                if ($user->role_user == 'administrator') {
                    # code...
                    return $this->sendError(null, ['error' => [['Akun tidak ditemukan']]], 422);
                }

                if ($user->status_user == 'NON ACTIVE') {
                    # code...
                    \Sentry\captureMessage('Validate Message: ' . $request->email_pic . ' Akun dinonaktifkan', \Sentry\Severity::warning());
                    return $this->sendError(null, ['error' => [['Akun dinonaktifkan']]], 422);
                }

                if (Hash::check($input['password'], $user->password)) {

                    // Create token for user to access dashboard
                    $token = $user->createToken("auth")->plainTextToken;

                    if ($user->data_pic_kelompok_masyarakat) {
                        # code...
                        // Return token to frontend
                        return $this->sendSuccess([
                            'id'                    => $user->id,
                            'token'                     => $token,
                            'jenis_kelompok_masyarakat' => $user->data_pic_kelompok_masyarakat->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat ?? null,
                            'kelompok_masyarakat_id'    => $user->data_pic_kelompok_masyarakat->kelompok_masyarakat->id,
                            'kelompok_masyarakat'       => $user->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat,
                            'role_user'                 => $user->role_user,
                            'nama'                      => $user->data_pic_kelompok_masyarakat->nama_pic,

                        ]);
                    } else {

                        return $this->sendSuccess([
                            'id'                    => $user->id,
                            'token'                     => $token,
                            'jenis_kelompok_masyarakat' => $user->role_user == 'approver' ? 'Validator' : $user->role_user,
                            'kelompok_masyarakat_id'    => $user->role_user == 'approver' ? 'Validator' : $user->role_user,
                            'kelompok_masyarakat'       => $user->role_user == 'approver' ? 'Validator' : $user->role_user,
                            'role_user'                 => $user->role_user,
                            'nama'                      => $user->nama_pic ? $user->nama_pic : ($user->role_user == 'approver' ? 'Validator' : $user->role_user)

                        ]);
                    }
                }

                \Sentry\captureMessage('Validate Message: ' . $request->email_pic . ' Kata sandi salah', \Sentry\Severity::warning());
                return $this->sendError(null, ['error' => [['Email atau Sandi tidak sesuai']]], 422);
            }

            \Sentry\captureMessage('Validate Message: ' . $request->email_pic . ' Email Salah', \Sentry\Severity::warning());
            return $this->sendError(null, ['error' => [['Akun tidak ditemukan']]], 422);
        } catch (\Throwable $th) {

            // return error
            return $this->sendError(null, env('APP_ENV') == 'local' ? $th->getMessage() : 'Internal server error', 500);
        }
    }
}
