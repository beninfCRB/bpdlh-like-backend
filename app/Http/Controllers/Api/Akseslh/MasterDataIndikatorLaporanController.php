<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\MasterDataIndikatorLaporanService;
use Illuminate\Http\Request;

class MasterDataIndikatorLaporanController extends ApiController
{
    protected $masterDataIndikatorLaporanSercice;

    public function __construct(
        MasterDataIndikatorLaporanService $masterDataIndikatorLaporanSercice,
        Request $request
    ) {
        $this->masterDataIndikatorLaporanSercice    =   $masterDataIndikatorLaporanSercice;
        parent::__construct($request);
    }

    public function index($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->masterDataIndikatorLaporanSercice->apiGetAll($id);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function byIdJenisKelompokMasyarakat($id)
    {
        $result = $this->masterDataIndikatorLaporanSercice->apiGetByIdJenisKelompokMasyarakat($id);

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

        $result = $this->kelompokMasyarakatService->apiLang($id, $lang);

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
