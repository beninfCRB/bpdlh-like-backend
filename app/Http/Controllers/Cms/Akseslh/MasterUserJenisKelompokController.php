<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\UserAkseslhService;
use App\Services\Akseslh\JenisKelompokMasyarakatService;
use App\Services\Akseslh\MasterUserJenisKelompokService;

class MasterUserJenisKelompokController extends ApiController
{
    protected $masterUserJenisKelompokService;
    protected $jenisKelompokMasyarakatService;
    protected $userAkseslhService;

    public function __construct(
        MasterUserJenisKelompokService $masterUserJenisKelompokService,
        JenisKelompokMasyarakatService $jenisKelompokMasyarakatService,
        UserAkseslhService $userAkseslhService,
        Request $request
    ) {
        $this->masterUserJenisKelompokService   =   $masterUserJenisKelompokService;
        $this->jenisKelompokMasyarakatService   =   $jenisKelompokMasyarakatService;
        $this->userAkseslhService               =   $userAkseslhService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.master-user-jenis-kegiatan.index");
    }

    public function create()
    {
        $userAkseslh    = $this->userAkseslhService->getAllAttr()->data;
        $jenisKelompok  = $this->jenisKelompokService->getAllAttr()->data;
        return view("pages.akseslh.master-user-jenis-kegiatan.create", compact('userAkseslh', 'jenisKelompok'));
    }

    public function edit($id)
    {
        $data           =   $this->masterUserJenisKelompokService->getById($id)->data;
        $userAkseslh    = $this->userAkseslhService->getAllAttr()->data;
        $jenisKelompok  = $this->jenisKelompokService->getAllAttr()->data;
        return view("pages.akseslh.master-user-jenis-kegiatan.edit", compact('data', 'userAkseslh', 'jenisKelompok'));
    }

    public function show($id)
    {
        $data                       = $this->userAkseslhService->getById($id);
        $jenisKelompokMasyarakat    = $this->jenisKelompokMasyarakatService->apiGetAll()->data;
        return view("pages.akseslh.master-user-jenis-kelompok.show", compact('data', 'jenisKelompokMasyarakat'));
    }

    public function store(Request $request)
    {
        $checkData = $request->all();
        $input  =   $request->validate([
            'user_akseslh_id'       => ['required', 'exists:user_akseslhs,id', function ($attribute, $value, $fail) use ($checkData) {
                if (\DB::table('master_user_jenis_kelompoks')
                    ->where('user_akseslh_id', $value)
                    ->where('jenis_kelompok_masyarakat_id', $checkData['jenis_kelompok_masyarakat_id'])
                    ->exists()
                ) {
                    $fail('The combination of tematik and sub tematik already exists.');
                }
            }],
            'jenis_kelompok_masyarakat_id'   => ['required', 'exists:jenis_kelompok_masyarakats,id', function ($attribute, $value, $fail) use ($checkData) {
                if (\DB::table('master_user_jenis_kelompoks')
                    ->where('jenis_kelompok_masyarakat_id', $checkData['jenis_kelompok_masyarakat_id'])
                    ->where('user_akseslh_id', $value)
                    ->exists()
                ) {
                    $fail('The combination of tematii and sub tematik already exists.');
                }
            }],
        ]);

        $result =   $this->masterUserJenisKelompokService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function update($id, Request $request)
    {
        $checkData = $request->all();
        $checkData['id'] = $id;
        $input  =   $request->validate([
            'tematik_kegiatan_id'       => ['required', 'exists:tematik_kegiatans,id', function ($attribute, $value, $fail) use ($checkData) {
                if (\DB::table('master_sub_tematik_kegiatans')
                    ->where('tematik_kegiatan_id', $value)
                    ->where('sub_tematik_kegiatan_id', $checkData['sub_tematik_kegiatan_id'])
                    ->where('id', '<>', $checkData['id'])
                    ->exists()
                ) {
                    $fail('The combination of tematik and sub tematik already exists.');
                }
            }],
            'sub_tematik_kegiatan_id'   => ['required', 'exists:sub_tematik_kegiatans,id', function ($attribute, $value, $fail) use ($checkData) {
                if (\DB::table('master_sub_tematik_kegiatans')
                    ->where('tematik_kegiatan_id', $checkData['tematik_kegiatan_id'])
                    ->where('username', $value)
                    ->where('id', '<>', $checkData['id'])
                    ->exists()
                ) {
                    $fail('The combination of tematii and sub tematik already exists.');
                }
            }],
            'short_id'                  => 'required|numeric|min:1',
            'deskripsi_tematik'         => 'required|max:255',
        ]);

        $result =   $this->masterUserJenisKelompokService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->masterUserJenisKelompokService->delete($id);
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
