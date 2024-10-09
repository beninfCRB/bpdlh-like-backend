<?php

namespace App\Http\Controllers\Api\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Services\Akseslh\LaporanKegiatanService;

class LaporanKegiatanController extends ApiController
{
    protected $laporanKegiatanService;

    public function __construct(
        LaporanKegiatanService $laporanKegiatanService,
        Request $request
    ) {
        $this->laporanKegiatanService    =   $laporanKegiatanService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->laporanKegiatanService->apiGetAll();

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

        $result = $this->laporanKegiatanService->apiLang($id, $lang);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function getDokumenLaporanKegiatan($id)
    {
        $result = $this->laporanKegiatanService->apiGetDokumenLaporanKegiatan($id, $this->request->user());

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function uploadDokumenLaporanKegiatan($id)
    {
        $validator = Validator::make($this->request->all(), [
            'file_dokumen'              => 'required|file|mimes:pdf',
            'jenis_dokumen'             => 'required',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input = $validator->validated();

        $input['user_akseslh']  = $this->request->user();

        $result = $this->laporanKegiatanService->apiUploadDokumenLaporanKegiatan($id, $input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function deleteDokumenLaporanKegiatan($id)
    {
        $result = $this->laporanKegiatanService->apiDeleteDokumenLaporanKegiatan($id);

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
