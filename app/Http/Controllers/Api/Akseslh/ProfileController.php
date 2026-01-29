<?php

namespace App\Http\Controllers\Api\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\ProfilePicService;
use App\Services\Akseslh\ProfileService;
use Illuminate\Support\Facades\Validator;

class ProfileController extends ApiController
{
    protected $profileService;
    protected $profilePicService;

    public function __construct(
        ProfileService $profileService,
        ProfilePicService $profilePicService,
        Request $request
    ) {
        parent::__construct($request);
        $this->profileService    =   $profileService;
        $this->profilePicService =   $profilePicService;
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->profileService->apiGetAll();

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function show($id, Request $request)
    {
        $input['user'] = $request->user();

        $result = $this->profileService->apiGetById($id, $input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function updateProfilePic($id, Request $request)
    {
        // Menambahkan custom rule untuk mengecek apakah inputan sama dengan "undefined"
        Validator::extend('not_undefined', function ($attribute, $value, $parameters, $validator) {
            return $value !== 'undefined'; // Mengembalikan false jika nilai "undefined"
        });

        $validator = Validator::make($request->all(), [
            'jenis_kelompok_masyarakat'         => 'nullable|not_undefined',
            'jenis_kelompok_masyarakat_id'      => 'nullable|exists:jenis_kelompok_masyarakats,id',
            'kelompok_masyarakat'               => 'nullable|not_undefined',
            'kelompok_masyarakat_id'            => 'nullable|exists:kelompok_masyarakats,id',
            'provinsi_kelompok_masyarakat_id'   => 'nullable',
            'kabupaten_kelompok_masyarakat_id'  => 'nullable',
            'kecamatan_kelompok_masyarakat_id'  => 'nullable',
            'kelurahan_kelompok_masyarakat_id'  => 'nullable',
            'profil_kelompok'                   => 'nullable|file|mimes:pdf,doc,docx|max:10192',
            'foto_ktp'                          => 'nullable|file|mimes:png,jpg,jpeg|max:10192',
            'nama_pic'                          => 'nullable|max:255|string',
            'jenis_identitas_pic'               => 'nullable|in:KTP,SIM,KARTU MAHASISWA',
            'nomor_identitas_pic'               => ['nullable', 'string', 'min:16', 'max:16', \Illuminate\Validation\Rule::unique('data_pic_kelompok_masyarakats', 'nomor_identitas_pic')->ignore($id)->whereNull('deleted_at')],
            'nomor_npwp_pic'                    => 'nullable',
            'alamat_pic'                        => 'nullable|string|max:255',
            'provinsi_pic'                      => 'nullable',
            'kabupaten_pic'                     => 'nullable',
            'kecamatan_pic'                     => 'nullable',
            'kelurahan_pic'                     => 'nullable',
            'tempat_lahir'                      => 'nullable',
            'tanggal_lahir'                     => 'nullable|date',
            'agama_id'                          => 'nullable|exists:agamas,id',
            'status_perkawinan_id'              => 'nullable|exists:status_pernikahans,id',
            'jenis_pekerjaan_id'                => 'nullable|exists:jenis_pekerjaans,id',
            'pendidikan_id'                     => 'nullable|exists:pendidikans,id',
            'nohp_pic'                          => ['nullable', \Illuminate\Validation\Rule::unique('data_pic_kelompok_masyarakats', 'nohp_pic')->ignore($id)->whereNull('deleted_at')],
            'email_pic'                         => ['nullable', 'email', \Illuminate\Validation\Rule::unique('data_pic_kelompok_masyarakats', 'email_pic')->ignore($id)->whereNull('deleted_at')],
            'nama_kontak_darurat'               => 'nullable|string',
            'nomor_kontak_darurat'              => ['nullable', 'different:nohp_pic', \Illuminate\Validation\Rule::unique('data_pic_kelompok_masyarakats', 'nomor_kontak_darurat')->whereNull('deleted_at')->ignore($id)],
            'alamat_kontak_darurat'             => 'nullable|string',
            'jenis_kelamin'                     => 'nullable|in:laki-laki,perempuan|not_undefined',
        ], [
            'kelompok_masyarakat.not_undefined'         => ':attribute tidak valid',
            'jenis_kelompok_masyarakat.not_undefined'   => ':attribute tidak valid',
        ]);

        if ($validator->fails()) {
            return $this->sendError($request->all(), $validator->getMessageBag(), 422);
        }

        $input = $validator->validated();

        if (isset($request->profil_kelompok)) {
            # code...
            $input['profil_kelompok'] = $request->file('profil_kelompok');
        }

        if (isset($request->foto_ktp)) {
            # code...
            $input['foto_ktp'] = $request->file('foto_ktp');
        }

        $input['data_pic_kelompok_masyarakat_id'] = $id;

        $result =   $this->profilePicService->create($input);

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function checkProfile(Request $request)
    {
        $input['user'] = $request->user();

        $result = $this->profileService->check_profile($input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function destroy($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'pengajuan_kegiatan_id' => 'required',
            'catatan_log'         => 'required',
        ]);

        if ($validator->fails()) {
            # code...
            \Sentry\captureMessage('Validate Message: ' . $request->user()->email_pic . ' ' . json_encode($validator->errors()->all()), \Sentry\Severity::warning());
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input          = $validator->validated();

        $input['user'] = $request->user();

        $result =   $this->profileService->delete_profile($id, $input);
        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }
}
