<?php

namespace App\Http\Controllers\Authapi;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authapi\RegisterRequest;
use App\Models\AkseslhUserEksternal;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
        $input = $request->all();

        \DB::beginTransaction();
        try {
            //code...
            $data = \DB::table('akseslh_user_eksternals')->insert([
                'id_kelompok_masyarakat'            => $input['id_kelompok_masyarakat'],
                'email_user_eksternal'              => $input['email_user_eksternal'],
                'password_user_eksternal'           => $input['password_user_eksternal'],
                'nama_user_eksternal'               => $input['nama_user_eksternal'],
                'jenis_identitas_user_eksternal'    => $input['jenis_identitas_user_eksternal'],
                'nomor_identitas_user_eksternal'    => $input['nomor_identitas_user_eksternal'],
                'nomor_hp_user_eksternal'           => $input['nomor_hp_user_eksternal'],
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);



            \DB::commit(); // commit the changes
            return $this->sendSuccess($data);
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, env('APP_ENV') == 'local' ? $th->getMessage() : 'Internal server error', 500);
        }
    }
}
