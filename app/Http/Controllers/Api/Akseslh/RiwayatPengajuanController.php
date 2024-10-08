<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\RiwayatPengajuanService;
use Illuminate\Http\Request;

class RiwayatPengajuanController extends ApiController
{
    protected $riwayatPengajuanService;

    public function __construct(
        RiwayatPengajuanService $riwayatPengajuanService,
        Request $request
    ) {
        $this->riwayatPengajuanService    =   $riwayatPengajuanService;
        parent::__construct($request);
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $flag               = request()->query('flag', null);
        $search             = request()->query('search', null);
        $page               = request()->query('page', null);
        $perPage            = request()->query('perPage', 10);
        $tahapanKegiatan    = request()->query('tahapanKegiatan', null);

        $result = $this->riwayatPengajuanService->getPaginated($flag, $search, $page, $perPage, $tahapanKegiatan);

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

        $result = $this->riwayatPengajuanService->apiLang($id, $lang);

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
