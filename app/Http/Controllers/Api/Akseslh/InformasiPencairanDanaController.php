<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\InformasiPencairanDanaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InformasiPencairanDanaController extends ApiController
{
    protected $InformasiPencairanDanaService;

    public function __construct(
        InformasiPencairanDanaService $InformasiPencairanDanaService,
        Request $request
    ) {
        $this->InformasiPencairanDanaService    =   $InformasiPencairanDanaService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->InformasiPencairanDanaService->apiGetAll();

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

        $result = $this->InformasiPencairanDanaService->apiLang($id, $lang);

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
            'master_data_bank_id'               => 'required|exists:master_data_banks,id',
            'log_tahapan_pengajuan_kegiatan_id' => 'required|exists:log_tahapan_pengajuan_kegiatans,id',
            'nama_cabang'                       => 'required',
            'jenis_rekening'                    => 'required',
            'nama_pemilik_rekening'             => 'required',
            'nomor_rekening'                    => 'required'
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input          = $validator->validated();

        $result = $this->InformasiPencairanDanaService->create($input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }
    public function update($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'master_data_bank_id'               => 'required|exists:master_data_banks,id',
            'log_tahapan_pengajuan_kegiatan_id' => 'required|exists:log_tahapan_pengajuan_kegiatans,id',
            'nama_cabang'                       => 'required',
            'jenis_rekening'                    => 'required',
            'nama_pemilik_rekening'             => 'required',
            'nomor_rekening'                    => 'required'
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input          = $validator->validated();

        $result = $this->InformasiPencairanDanaService->update($id, $input);

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
