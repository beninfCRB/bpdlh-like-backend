<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\LaporanKegiatanService;

class LaporanKegiatanController extends Controller
{
    protected $laporanKegiatanService;

    /**
     * @param LaporanKegiatanService $laporanKegiatanService
     */
    public function __construct(LaporanKegiatanService $laporanKegiatanService)
    {
        $this->laporanKegiatanService = $laporanKegiatanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->laporanKegiatanService->getAllLaporanAkhir();
    }
}
