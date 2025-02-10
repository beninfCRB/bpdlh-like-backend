<?php

namespace App\Http\Controllers\Authapi;

use App\Models\DataPicKelompokMasyarakat;
use App\Models\UserAkseslh;
use App\Http\Controllers\ApiController;
use App\Services\EmailPhpService;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class PasswordController extends ApiController
{
    protected $emailPhpService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EmailPhpService $emailPhpService)
    {
        $this->middleware('guest');
        $this->emailPhpService = $emailPhpService;
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'                   => 'required|exists:user_akseslhs,email',
        ]);

        if ($validator->fails()) {
            # code...
            \Sentry\captureMessage('Validate Message: ' . $request->email . ' ' . $validator->getMessageBag(), \Sentry\Severity::warning());
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        // Get all input
        $input = $validator->validated();

        try {
            //code...
            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                # code...
                return $this->sendSuccess(null, 'Success send link');
            } else {
                return $this->sendError(null, 'Error send link', 422);
            }
        } catch (\Exception $th) {
            //throw $th;
            return $this->sendError(null, $th->getMessage(), 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            # code...
            \Sentry\captureMessage('Validate Message: ' . $request->email . ' ' . $validator->getMessageBag(), \Sentry\Severity::warning());
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        \DB::beginTransaction();

        try {
            //code...
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (UserAkseslh $user, string $password) {
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ]);

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            // $token->delete();

            if ($status === Password::PASSWORD_RESET) {
                # code...

                // Commit Change
                \DB::commit();
                return $this->sendSuccess(null, 'Success Change Password');
            } else {
                \DB::rollBack();
                return $this->sendError(null, 'Error Change Password');
            }
        } catch (\Throwable $th) {
            //throw $th;

            \DB::rollBack(); // rollback the changes

            // return error
            return $this->sendError(null, env('APP_ENV') == 'local' ? $th->getMessage() : 'Internal server error', 500);
        }
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'kelompok_masyarakat_id'      => 'required|exists:kelompok_masyarakats,id',
            'email_pic'                   => 'required|email',
            'nama_pic'                    => 'required|string',
            'jenis_identitas_pic'         => 'required',
            'nomor_identitas_pic'         => 'required',
            'nohp_pic'                    => 'required'
        ], [
            'kelompok_masyarakat_id.exists' => 'User not found'
        ]);

        if ($validator->fails()) {
            # code...
            \Sentry\captureMessage('Validate Message: ' . $request->email_pic . ' ' . $validator->getMessageBag(), \Sentry\Severity::warning());
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        // Get all input
        $input = $validator->validated();

        // Make default password for first login
        $default_password =
            crypt($request->email_pic . Carbon::now()->format('d M Y H:i:s'), $request->email_pic);

        // Begin db transaction
        \DB::beginTransaction();

        try {

            $user = DataPicKelompokMasyarakat::where('kelompok_masyarakat_id', $input['kelompok_masyarakat_id'])
                ->where(function ($query) use ($input) {
                    $query
                        ->where('nohp_pic', $input['nohp_pic'])
                        ->orWhere('email_pic', $input['email_pic']);
                })->first();

            if ($user) {

                if ($user->user_akseslh->status_user == 'ACTIVE') return $this->sendError(null, 'Already active');

                // Change user status to active
                $user->email_pic = $request->email_pic;
                $user->save();

                $user->user_akseslh->email          = $request->email_pic;
                $user->user_akseslh->password       = Hash::make($default_password);
                $user->user_akseslh->status_user    = 'ACTIVE';
                $user->user_akseslh->save();

                //Send email notification
                // Notification::send($user->user_akseslh, new RegisterNotification($default_password));
                $this->emailPhpService->sendEmail($input['email_pic'], 'Register Notification', $user, $default_password);

                // Create token for user to access dashboard
                $token = $user->user_akseslh->createToken("auth")->plainTextToken;

                // Commit Change
                \DB::commit();

                // Return token to frontend
                return $this->sendSuccess([
                    'token'                     => $token,
                    'jenis_kelompok_masyarakat' => $user->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat,
                    'kelompok_masyarakat_id'    => $user->kelompok_masyarakat->id,
                    'kelompok_masyarakat'       => $user->kelompok_masyarakat->kelompok_masyarakat,
                    'role_user'                 => $user->user_akseslh->role_user,
                ]);
            } else {
                \DB::rollBack();

                return $this->sendError(null, "Mohon maaf. Kelompok anda belum terdaftar sebagai calon penerima. Untuk informasi lebih lanjut hubungi layanandanamasyarakat@bpdlh.id");
            }
        } catch (\Throwable $th) {

            \DB::rollBack(); // rollback the changes

            // return error
            return $this->sendError(null, env('APP_ENV') == 'local' ? $th->getMessage() : 'Internal server error', 500);
        }
    }
}
