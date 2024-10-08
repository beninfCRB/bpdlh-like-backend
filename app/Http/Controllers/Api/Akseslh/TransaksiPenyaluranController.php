<?php

namespace App\Http\Controllers\Api\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\PengajuanKegiatanService;
use Illuminate\Support\Facades\Validator;
use App\Services\Akseslh\TransaksiPenyaluranService;

class TransaksiPenyaluranController extends ApiController
{
    protected $transaksiPenyaluranService;
    protected $pengajuanKegiatanService;

    public function __construct(
        TransaksiPenyaluranService $transaksiPenyaluranService,
        PengajuanKegiatanService $pengajuanKegiatanService,
        Request $request
    ) {
        $this->transaksiPenyaluranService    =   $transaksiPenyaluranService;
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

    public function getPengajuanKegiatan(): \Illuminate\Http\JsonResponse
    {
        $result = $this->transaksiPenyaluranService->apiGetPengajuanKegiatan();

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

        $result = $this->pencairanDanaService->apiLang($id, $lang);

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
            'master_data_bank_id'       => 'required|exists:master_data_banks,id',
            'pengajuan_kegiatan_id'     => 'required|exists:pengajuan_kegiatans,id',
            'nomor_rekening'            => 'required',
            'nama_pemilik_rekening'     => 'required',
            'nilai_penyaluran'          => 'required',
            'tanggal_penyaluran'        => 'required',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input              = $validator->validated();

        $input['username']  = $request->user()->id;

        $result = $this->transaksiPenyaluranService->create($input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }
}
