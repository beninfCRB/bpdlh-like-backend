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
            'judul_pengajuan_kegiatan'  => 'required|string|max:500',
            'provinsi_kegiatan'         => 'required',
            'kabupaten_kegiatan'        => 'required',
            'kecamatan_kegiatan'        => 'required',
            'kelurahan_kegiatan'        => 'required',
            'alamat_kegiatan'           => 'required',
            'tanggal_kegiatan'          => 'required|date',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input = $validator->validated();
        $input["user_eksternal_id"] = $request->user()->id;

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
