<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Services\Akseslh\PengajuanKegiatanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengajuanKegiatanController extends ApiController
{
    protected $pengajuanKegiatanService;

    public function __construct(
        PengajuanKegiatanService $pengajuanKegiatanService,
        Request $request
    ) {
        $this->pengajuanKegiatanService    =   $pengajuanKegiatanService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->pengajuanKegiatanService->apiGetAll();

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function show($id, Request $request)
    {
        $lang           = $request->input('lang')  ?: 'ID';

        $result = $this->pengajuanKegiatanService->apiLang($id, $lang);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'paket_kegiatan_id'         => 'required|exists:paket_kegiatans,id',
            // 'paket_kegiatan_id'         => 'required',
            'judul_pengajuan_kegiatan'  => 'required|string|max:500',
            'provinsi_kegiatan'         => 'required',
            'kabupaten_kegiatan'        => 'required',
            'kecamatan_kegiatan'        => 'required',
            'kelurahan_kegiatan'        => 'required',
            'alamat_kegiatan'           => 'required',
            'tanggal_kegiatan'          => 'required',
            'waktu_kegiatan'            => 'required',
            'proposal_kegiatan'         => 'required',
            'tujuan_kegiatan'           => 'required',
            'ruang_lingkup_kegiatan'    => 'required',
            // 'lampiran'                  => 'required',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input          = $validator->validated();

        $tanggalArray   = explode(" - ", $input["tanggal_kegiatan"]);
        $waktuArray     = explode(" - ", $input["waktu_kegiatan"]);

        //add new key for required field in table
        $input["user_eksternal_id"] = $request->user()->id;
        $input["tanggal_mulai_kegiatan"]    = $tanggalArray[0];
        $input["tanggal_akhir_kegiatan"]    = $tanggalArray[1];
        $input["time_mulai_kegiatan"]      = $waktuArray[0];
        $input["time_akhir_kegiatan"]      = $waktuArray[1];

        //eliminate unnecessary key 
        unset($input["tanggal_kegiatan"]);
        unset($input["waktu_kegiatan"]);

        $result = $this->pengajuanKegiatanService->create($input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }
}
