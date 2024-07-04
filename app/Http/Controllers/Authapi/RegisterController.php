<?php

namespace App\Http\Controllers\Authapi;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AkseslhUserEksternal;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\Notifications\RegisterNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Authapi\RegisterRequest;

class RegisterController extends ApiController
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

    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        // Get all input
        $input = $request->all();

        // Make default password for first login
        $default_password =
            crypt($input['email_user_eksternal'] . Carbon::now()->format('d M Y H:i:s'), $input['email_user_eksternal']);

        // Begin db transaction
        \DB::beginTransaction();
        try {
            //Insert data user to database
            $user = AkseslhUserEksternal::create([
                'akseslh_kelompok_masyarakat_id'    => $input['akseslh_kelompok_masyarakat_id'],
                'email_user_eksternal'              => $input['email_user_eksternal'],
                'password_user_eksternal'           => Hash::make($default_password),
                'nama_user_eksternal'               => $input['nama_user_eksternal'],
                'jenis_identitas_user_eksternal'    => $input['jenis_identitas_user_eksternal'],
                'nomor_identitas_user_eksternal'    => $input['nomor_identitas_user_eksternal'],
                'nomor_hp_user_eksternal'           => $input['nomor_hp_user_eksternal'],
            ]);

            // Create token for user to access dashboard
            $token = $user->createToken("auth")->plainTextToken;

            // Send default database to email user
            Notification::route('mail', $input['email_user_eksternal'])->notify(new RegisterNotification($default_password));

            \DB::commit(); // commit the changes

            // Return token to frontend
            return $this->sendSuccess(['token' => $token]);
        } catch (\Throwable $th) {

            \DB::rollBack(); // rollback the changes

            // return error
            return $this->sendError(null, env('APP_ENV') == 'local' ? $th->getMessage() : 'Internal server error', 500);
        }
    }
}
