<?php

namespace App\Http\Controllers\Api\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Services\Akseslh\IndikatorLaporanKegiatanService;

class IndikatorLaporanKegiatanController extends ApiController
{
    protected $indikatorLaporanService;

    public function __construct(
        IndikatorLaporanKegiatanService $indikatorLaporanService,
        Request $request
    ) {
        $this->indikatorLaporanService    =   $indikatorLaporanService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->indikatorLaporanService->apiGetAll();

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

        $result = $this->indikatorLaporanService->apiLang($id, $lang);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'master_data_indikator_laporan_id'  => 'required|exists:master_data_indikator_laporans,id',
            'pengajuan_kegiatan_id'             => 'required|exists:pengajuan_kegiatans,id',
            'nilai_laporan'                     => 'required',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input          = $validator->validated();

        $result = $this->indikatorLaporanService->create($input);

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
