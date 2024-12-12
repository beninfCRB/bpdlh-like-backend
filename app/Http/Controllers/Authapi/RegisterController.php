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
use App\Http\Requests\Authapi\Register2Request;
use App\Models\DataPicKelompokMasyarakat;
use Illuminate\Support\Facades\Validator;
use App\Notifications\RegisterNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Authapi\RegisterRequest;
use App\Services\Akseslh\KelompokMasyarakatService;
use DateTime;
use App\Models\File as FileTable;
use App\Models\KelompokMasyarakat;
use App\Services\FileUploadService;

class RegisterController extends ApiController
{
    protected $emailPhpService;
    protected $kelompokMasyarakatService;
    protected $fileUploadService;
    protected $fileTable;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        EmailPhpService $emailPhpService,
        KelompokMasyarakatService $kelompokMasyarakatService,
        FileUploadService $fileUploadService,
        FileTable $fileTable
    ) {
        $this->middleware('guest');
        $this->emailPhpService = $emailPhpService;
        $this->kelompokMasyarakatService = $kelompokMasyarakatService;
        $this->fileUploadService            =   $fileUploadService;
        $this->fileTable                    =   $fileTable;
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

    public function register_2(Request $request): \Illuminate\Http\JsonResponse
    {
        // Menambahkan custom rule untuk mengecek apakah inputan sama dengan "undefined"
        Validator::extend('not_undefined', function ($attribute, $value, $parameters, $validator) {
            return $value !== 'undefined'; // Mengembalikan false jika nilai "undefined"
        });

        $validator = Validator::make($request->all(), [
            'jenis_kelompok_masyarakat_id'      => 'required|exists:jenis_kelompok_masyarakats,id',
            'kelompok_masyarakat'               => 'required|not_undefined',
            'provinsi_kelompok_masyarakat_id'   => 'required',
            'kabupaten_kelompok_masyarakat_id'  => 'required',
            'kecamatan_kelompok_masyarakat_id'  => 'required',
            'kelurahan_kelompok_masyarakat_id'  => 'required',
            'profil_kelompok'                   => 'required|file|mimes:pdf,doc,docx|max:10192',
            'foto_ktp'                          => 'required|file|mimes:png,jpg,jpeg|max:2048',
            'foto_selfie'                       => 'required|file|mimes:png,jpg,jpeg|max:2048',
            'nama_pic'                          => 'required|max:255|string',
            // 'nomor_identitas_pic'            => 'required|string|min:16|max:16|unique:data_pic_kelompok_masyarakats,nomor_identitas_pic',
            'nomor_identitas_pic'               => ['required', 'string', 'min:16', 'max:16', \Illuminate\Validation\Rule::unique('data_pic_kelompok_masyarakats', 'nomor_identitas_pic')->whereNull('deleted_at')],
            'nomor_npwp_pic'            => 'nullable',
            'alamat_pic'                => 'required|string|max:255',
            'provinsi_pic'              => 'required',
            'kabupaten_pic'             => 'required',
            'kecamatan_pic'             => 'required',
            'kelurahan_pic'             => 'required',
            'tempat_lahir'              => 'required',
            'tanggal_lahir'             => 'required|date',
            'agama_id'                  => 'required|exists:agamas,id',
            'status_perkawinan_id'      => 'required|exists:status_pernikahans,id',
            'nama_gadis_ibu_kandung'    => 'required',
            'jenis_pekerjaan_id'        => 'required|exists:jenis_pekerjaans,id',
            'nohp_pic'                  => ['required', \Illuminate\Validation\Rule::unique('data_pic_kelompok_masyarakats', 'nohp_pic')->whereNull('deleted_at')],
            // 'nohp_pic'                  => 'required|unique:data_pic_kelompok_masyarakats,nohp_pic',
            // 'email_pic'                 => 'required|email|unique:data_pic_kelompok_masyarakats,email_pic|unique:user_akseslhs,email',
            'email_pic'                 => ['required', 'email', \Illuminate\Validation\Rule::unique('data_pic_kelompok_masyarakats', 'email_pic')->whereNull('deleted_at')],
            'kode_aktivasi'             => 'required',
        ]);

        if ($validator->fails()) {
            # code...
            \Sentry\captureMessage('Validate Message: ' . $request->email_pic . ' ' . $validator->getMessageBag(), \Sentry\Severity::warning());
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        // Begin db transaction
        \DB::beginTransaction();

        $record = \DB::table('users_verify_tokens')
            ->where('user_email', $request->email_pic)
            ->where('token', $request->kode_aktivasi)
            ->first();

        if (!$record) {
            return $this->sendError(null, 'Token tidak valid.', 422);
        } elseif (Carbon::parse($record->expired_at)->isPast()) {
            return $this->sendError(null, 'Token sudah kedaluwarsa.', 422);
        }

        // Get all input
        $input = $validator->validated();
        // $input = $request->validated();

        $check_kelompok_masyarakat = \DB::table('kelompok_masyarakats')
            ->where('id', $input['kelompok_masyarakat'])
            ->first();

        if (!$check_kelompok_masyarakat) {
            # code...

            $check_kelompok_2 = \DB::table('kelompok_masyarakats')
                ->where('kelompok_masyarakat', $input['kelompok_masyarakat'])
                ->where('provinsi_kelompok_masyarakat_id', $input['provinsi_kelompok_masyarakat_id'])
                ->where('kabupaten_kelompok_masyarakat_id', $input['kabupaten_kelompok_masyarakat_id'])
                ->where('kecamatan_kelompok_masyarakat_id', $input['kecamatan_kelompok_masyarakat_id'])
                ->where('kelurahan_kelompok_masyarakat_id', $input['kelurahan_kelompok_masyarakat_id'])
                ->first();

            if ($check_kelompok_2) {
                # code...
                $input['kelompok_masyarakat'] = $check_kelompok_2->id;
            } else {

                $kelompok_masyarakat = KelompokMasyarakat::create([
                    'jenis_kelompok_masyarakat_id'      =>  $input['jenis_kelompok_masyarakat_id'],
                    'kelompok_masyarakat'               =>  $input['kelompok_masyarakat'],
                    'provinsi_kelompok_masyarakat_id'   =>  $input['provinsi_kelompok_masyarakat_id'],
                    'kabupaten_kelompok_masyarakat_id'  =>  $input['kabupaten_kelompok_masyarakat_id'],
                    'kecamatan_kelompok_masyarakat_id'  =>  $input['kecamatan_kelompok_masyarakat_id'],
                    'kelurahan_kelompok_masyarakat_id'  =>  $input['kelurahan_kelompok_masyarakat_id'],
                    'flag'                              => 1,
                ]);

                $input['kelompok_masyarakat'] = $kelompok_masyarakat->id;
            }
        }

        // Make default password for first login
        $default_password =
            crypt($input['email_pic'] . Carbon::now()->format('d M Y H:i:s'), $input['email_pic']);

        try {

            $user = DataPicKelompokMasyarakat::create([
                'kelompok_masyarakat_id'    => $input['kelompok_masyarakat'],
                'nama_pic'                  => $input['nama_pic'],
                'jenis_identitas_pic'       => 'KTP',
                'nomor_identitas_pic'       => $input['nomor_identitas_pic'],
                'nomor_npwp_pic'            => $input['nomor_npwp_pic'] ?? null,
                'email_pic'                 => $input['email_pic'],
                'nohp_pic'                  => $input['nohp_pic'],
                'alamat_pic'                => $input['alamat_pic'],
                'provinsi_pic'              => $input['provinsi_pic'],
                'kabupaten_pic'             => $input['kabupaten_pic'],
                'kecamatan_pic'             => $input['kecamatan_pic'],
                'kelurahan_pic'             => $input['kelurahan_pic'],
                'flag' => 1,
                'tempat_lahir'              => $input['tempat_lahir'],
                'tanggal_lahir'             => $input['tanggal_lahir'],
                'agama_id'                  => $input['agama_id'],
                'status_perkawinan_id'      => $input['status_perkawinan_id'],
                'nama_gadis_ibu_kandung'    => $input['nama_gadis_ibu_kandung'],
                'jenis_pekerjaan_id'        => $input['jenis_pekerjaan_id'],
            ]);

            $user_akseslh = UserAkseslh::create([
                'data_pic_kelompok_masyarakat_id'   => $user->id,
                'nama_pic'                          => $input['nama_pic'],
                'email'                             => $input['email_pic'],
                'password'                          => Hash::make($default_password),
                'status_user'                       => 'ACTIVE',
                'role_user'                         => 'maker',
                'flag'                              => 1,
            ]);

            //Send email notification
            // Notification::send($user->user_akseslh, new RegisterNotification($default_password));
            $this->emailPhpService->sendEmail($input['email_pic'], 'Register Notification', $user, $default_password);

            // Create token for user to access dashboard
            // $token = $user->user_akseslh->createToken("auth")->plainTextToken;

            // Save document 
            if (isset($input['profil_kelompok']) && $input['profil_kelompok']->getClientOriginalExtension() == 'pdf') {
                // upload document
                $upload_profile_kelompok = $this->fileUploadService->handleFile($input['profil_kelompok'])->saveToDb('profil_kelompok');
            }

            if (!empty($upload_profile_kelompok)) {
                $document = $this->fileTable->newQuery()->find($upload_profile_kelompok->id);
                $document->update([
                    'fileable_type' => get_class($user),
                    'fileable_id'   => $user->id,
                ]);
            }

            // Save document 
            $upload_foto_ktp = $this->fileUploadService->handleImage($input['foto_ktp'])->saveToDb('foto_ktp');

            if (!empty($upload_foto_ktp)) {
                $document = $this->fileTable->newQuery()->find($upload_foto_ktp->id);
                $document->update([
                    'fileable_type' => get_class($user),
                    'fileable_id'   => $user->id,
                ]);
            }

            // Save document 
            $upload_foto_selfie = $this->fileUploadService->handleImage($input['foto_selfie'])->saveToDb('foto_selfie');

            if (!empty($upload_foto_selfie)) {
                $document = $this->fileTable->newQuery()->find($upload_foto_selfie->id);
                $document->update([
                    'fileable_type' => get_class($user),
                    'fileable_id'   => $user->id,
                ]);
            }

            // Commit Change
            \DB::commit();

            // Return token to frontend
            return $this->sendSuccess([
                'message'                     => "Proses Registrasi Berhasil, Silahkan periksa email anda",
            ]);
        } catch (\Throwable $th) {

            \DB::rollBack(); // rollback the changes

            // return error
            return $this->sendError(null, env('APP_ENV') == 'local' ? $th->getMessage() : 'Internal server error', 500);
        }
    }

    public function getKodeAktivasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'email_pic'  => 'required|email|unique:data_pic_kelompok_masyarakats,email_pic|unique:user_akseslhs,email',
            'email_pic' => ['required', 'email', \Illuminate\Validation\Rule::unique('data_pic_kelompok_masyarakats', 'email_pic')->whereNull('deleted_at')]
        ]);

        if ($validator->fails()) {
            # code...
            \Sentry\captureMessage('Validate Message: ' . $request->email_pic . ' ' . $validator->getMessageBag(), \Sentry\Severity::warning());
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
                return $this->sendSuccess(null, 'Kode verifikasi sudah terkirim ke email');
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
