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

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $flag               = request()->query('flag', null);
        $search             = $request->query('search', null);
        $page               = $this->request->query('page', null);
        $perPage            = !empty($_GET['perPage']) ? $_GET['perPage'] : null;
        $tahapanKegiatan    = $request->tahapanKegiatan;

        $input = [];

        $fullUrlWithQuery = $request->fullUrl() . " " . request()->fullUrl() . " " . url()->full();

        if ($request->query('tanggalAwalSubmit', null) && $request->query('tanggalAkhirSubmit', null)) {
            # code...
            $input["created_at_awal"]    = $request->query('tanggalAwalSubmit', null);
            $input["created_at_akhir"]    = $request->query('tanggalAkhirSubmit', null);
        }

        if ($request->query('tanggalAwalKegiatan', null) && $request->query('tanggalAkhirKegiatan', null)) {
            # code...
            $input["tanggal_mulai_kegiatan"]    = $request->query('tanggalAwalKegiatan', null);
            $input["tanggal_akhir_kegiatan"]    = $request->query('tanggalAkhirKegiatan', null);
        }


        $result = $this->riwayatPengajuanService->getPaginated($flag, $search, $page, $perPage, $tahapanKegiatan, $input);

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
        $result = $this->riwayatPengajuanService->getById($id);

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
