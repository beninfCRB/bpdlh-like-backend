<?php

namespace App\Http\Controllers\Api\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Services\Akseslh\PengajuanKegiatanService;
use App\Services\Akseslh\ValidasiPengajuanKegiatanService;

class ValidasiPengajuanKegiatanController extends ApiController
{
    protected $validasiPengajuanKegiatanService;
    protected $pengajuanKegiatanService;

    public function __construct(
        ValidasiPengajuanKegiatanService $validasiPengajuanKegiatanService,
        PengajuanKegiatanService $pengajuanKegiatanService,
        Request $request
    ) {
        $this->validasiPengajuanKegiatanService     =   $validasiPengajuanKegiatanService;
        $this->pengajuanKegiatanService             =   $pengajuanKegiatanService;
        parent::__construct($request);
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $result = $this->validasiPengajuanKegiatanService->getAllAttr();

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function show($id)
    {
        $input['pengajuan_id'] = $id;

        $result = $this->pengajuanKegiatanService->apiGetBydId($input['pengajuan_id']);

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
            'catatan_log'               => 'required',
            'status'                    => 'required'
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input = $validator->validated();

        $result = $this->validasiPengajuanKegiatanService->update($id, $input);

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
