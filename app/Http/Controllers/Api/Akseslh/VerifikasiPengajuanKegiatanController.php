<?php

namespace App\Http\Controllers\Api\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Services\Akseslh\PengajuanKegiatanService;
use App\Services\Akseslh\VerifikasiPengajuanKegiatanService;

class VerifikasiPengajuanKegiatanController extends ApiController
{
    protected $verifikasiPengajuanKegiatanService;
    protected $pengajuanKegiatanService;

    public function __construct(
        VerifikasiPengajuanKegiatanService $verifikasiPengajuanKegiatanService,
        PengajuanKegiatanService $pengajuanKegiatanService,
        Request $request
    ) {
        $this->verifikasiPengajuanKegiatanService     =   $verifikasiPengajuanKegiatanService;
        $this->pengajuanKegiatanService             =   $pengajuanKegiatanService;
        parent::__construct($request);
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $input = request()->query('flag');
        $result = $this->verifikasiPengajuanKegiatanService->getAllAttr($input);

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
        $input = [
            'pengajuan_id'  => $id
        ];

        $result = $this->verifikasiPengajuanKegiatanService->apiGetBydId($input['pengajuan_id']);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pengajuan_id'      => 'required',
            'catatan_log'       => 'nullable',
            'paket_kegiatan_id' => 'required',
            'status'            => 'required'
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input = $validator->validated();

        $result = $this->verifikasiPengajuanKegiatanService->update($input['pengajuan_id'], $input);

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
