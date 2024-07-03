<?php

namespace App\Http\Controllers\Authapi;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authapi\RegisterRequest;
use App\Models\AkseslhUserEksternal;
use App\Notifications\RegisterNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

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
        return $this->sendSuccess(
            Hash::make('rifqiarnoldy@gmail.com'),
            "Uye tone"
        );
        $input = $request->all();

        \DB::beginTransaction();
        try {
            //code...
            $user = AkseslhUserEksternal::find('5a0137ef-0c6f-483c-b8be-590d921ec87e');
            // $user = AkseslhUserEksternal::create([
            //     'akseslh_kelompok_masyarakat_id'    => $input['akseslh_kelompok_masyarakat_id'],
            //     'email_user_eksternal'              => $input['email_user_eksternal'],
            //     // 'password_user_eksternal'           => $input['password_user_eksternal'],
            //     'password_user_eksternal'           => Hash::make('1234567890'),
            //     'nama_user_eksternal'               => $input['nama_user_eksternal'],
            //     'jenis_identitas_user_eksternal'    => $input['jenis_identitas_user_eksternal'],
            //     'nomor_identitas_user_eksternal'    => $input['nomor_identitas_user_eksternal'],
            //     'nomor_hp_user_eksternal'           => $input['nomor_hp_user_eksternal'],
            // ]);
            // $data = \DB::table('akseslh_user_eksternals')->insert([
            //     'id_kelompok_masyarakat'            => $input['id_kelompok_masyarakat'],
            //     'email_user_eksternal'              => $input['email_user_eksternal'],
            //     'password_user_eksternal'           => $input['password_user_eksternal'],
            //     'nama_user_eksternal'               => $input['nama_user_eksternal'],
            //     'jenis_identitas_user_eksternal'    => $input['jenis_identitas_user_eksternal'],
            //     'nomor_identitas_user_eksternal'    => $input['nomor_identitas_user_eksternal'],
            //     'nomor_hp_user_eksternal'           => $input['nomor_hp_user_eksternal'],
            //     'created_at'    => Carbon::now(),
            //     'updated_at'    => Carbon::now(),
            // ]);

            Notification::route('mail', 'email@example.com')->notify(new RegisterNotification());
            // Notification::send($user, new RegisterNotification());

            \DB::commit(); // commit the changes
            return $this->sendSuccess($user);
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, env('APP_ENV') == 'local' ? $th->getMessage() : 'Internal server error', 500);
        }
    }
}
