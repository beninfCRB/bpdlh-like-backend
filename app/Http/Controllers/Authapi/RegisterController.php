<?php

namespace App\Http\Controllers\Authapi;

use Carbon\Carbon;
use App\Models\UserAkseslh;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\UserEksternal;
use App\Services\EmailPhpService;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\Models\DataPicKelompokMasyarakat;
use Illuminate\Support\Facades\Validator;
use App\Notifications\RegisterNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Authapi\RegisterRequest;
use DateTime;

class RegisterController extends ApiController
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

    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
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
                $user->user_akseslh->role_user      = 'maker';
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
                    'nama'                      => $user->nama_pic,
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

    public function register_2() {}

    public function getKodeAktivasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_pic'  => 'required|email|unique:data_pic_kelompok_masyarakats,email_pic|unique:user_akseslhs,email',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        // Get all input
        $input = $validator->validated();

        // Make default password for first login
        $token =
            crypt($request->email_pic . Carbon::now()->format('d M Y H:i:s'), rand(1, 100));

        // Membuat objek DateTime dengan waktu sekarang
        $date = new DateTime();

        // Menambahkan 30 menit
        $date->modify('+30 minutes');

        // Prepare data
        $data = [
            'id'            => Str::uuid(),
            'user_email'    => $input['email_pic'],
            'token'         => $token,
            'expired_at'    => $date->format('Y-m-d H:i:s'),
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ];

        // Begin db transaction
        \DB::beginTransaction();

        try {

            $insert = \DB::table('users_verify_tokens')->insert($data);

            if ($insert) {

                //Send email notification
                $this->emailPhpService->getTokenAktivasi($input['email_pic'], 'Token Verifikasi Email', $token);

                // Commit Change
                \DB::commit();

                // Return token to frontend
                return $this->sendSuccess();
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
