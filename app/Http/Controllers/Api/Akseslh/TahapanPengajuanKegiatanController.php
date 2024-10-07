<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\TahapanPengajuanKegiatanService;
use Illuminate\Http\Request;

class TahapanPengajuanKegiatanController extends ApiController
{
    protected $tahapanPengajuanKegiatanService;

    public function __construct(
        TahapanPengajuanKegiatanService $tahapanPengajuanKegiatanService,
        Request $request
    ) {
        $this->tahapanPengajuanKegiatanService    =   $tahapanPengajuanKegiatanService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->tahapanPengajuanKegiatanService->getAllAttr();

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

        $result = $this->tahapanPengajuanKegiatanService->apiLang($id, $lang);

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
