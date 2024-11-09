<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\MasterDataIndikatorLaporanService;

class MasterDataIndikatorLaporanController extends Controller
{
    protected $masterDataIndikatorLaporanService;

    /**
     * @param MasterDataIndikatorLaporanService $masterDataIndikatorLaporanService
     */
    public function __construct(MasterDataIndikatorLaporanService $masterDataIndikatorLaporanService)
    {
        $this->masterDataIndikatorLaporanService = $masterDataIndikatorLaporanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->masterDataIndikatorLaporanService->getAll();
    }
}
