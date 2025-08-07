<?php

namespace App\Http\Controllers\Api\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\ProfileService;
use Illuminate\Support\Facades\Validator;

class ProfileController extends ApiController
{
    protected $profileService;

    public function __construct(
        ProfileService $profileService,
        Request $request
    ) {
        $this->profileService    =   $profileService;
        parent::__construct($request);
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
            'jenis_kelompok_masyarakat_id'      => 'required|exists:jenis_kelompok_masyarakats,id',
            'kelompok_masyarakat'               => 'required|not_undefined',
            'provinsi_kelompok_masyarakat_id'   => 'required',
            'kabupaten_kelompok_masyarakat_id'  => 'required',
            'kecamatan_kelompok_masyarakat_id'  => 'required',
            'kelurahan_kelompok_masyarakat_id'  => 'required',
            'profil_kelompok'                   => 'nullable|file|mimes:pdf,doc,docx|max:10192',
            'foto_ktp'                          => 'nullable|file|mimes:png,jpg,jpeg|max:10192',
            'nama_pic'                          => 'required|max:255|string',
            'nomor_identitas_pic'               => ['required', 'string', 'min:16', 'max:16', \Illuminate\Validation\Rule::unique('data_pic_kelompok_masyarakats', 'nomor_identitas_pic')->ignore($id)->whereNull('deleted_at')],
            'nomor_npwp_pic'                    => 'nullable',
            'alamat_pic'                        => 'required|string|max:255',
            'provinsi_pic'                      => 'required',
            'kabupaten_pic'                     => 'required',
            'kecamatan_pic'                     => 'required',
            'kelurahan_pic'                     => 'required',
            'tempat_lahir'                      => 'required',
            'tanggal_lahir'                     => 'required|date',
            'agama_id'                          => 'required|exists:agamas,id',
            'status_perkawinan_id'              => 'required|exists:status_pernikahans,id',
            'jenis_pekerjaan_id'                => 'required|exists:jenis_pekerjaans,id',
            'pendidikan'                        => 'required|exists:pendidikans,id',
            'nohp_pic'                          => ['required', \Illuminate\Validation\Rule::unique('data_pic_kelompok_masyarakats', 'nohp_pic')->ignore($id)->whereNull('deleted_at')],
            'email_pic'                         => ['required', 'email', \Illuminate\Validation\Rule::unique('data_pic_kelompok_masyarakats', 'email_pic')->ignore($id)->whereNull('deleted_at')],
            'jenis_kelamin'                     => 'required|in:laki-laki,perempuan|not_undefined',
        ], [
            'kelompok_masyarakat.not_undefined' => ':attribute tidak valid',
        ]);

        if ($validator->fails()) {
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        return $this->sendSuccess(null, 'Permintaan perubahan profil berhasil dikirim', 200);
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
