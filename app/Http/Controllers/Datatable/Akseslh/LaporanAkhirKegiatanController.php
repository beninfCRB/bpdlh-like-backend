<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\LaporanAkhirKegiatanService;

class LaporanAkhirKegiatanController extends Controller
{
    protected $laporanAkhirKegiatanService;

    /**
     * @param LaporanAkhirKegiatanService $laporanAkhirKegiatanService
     */
    public function __construct(LaporanAkhirKegiatanService $laporanAkhirKegiatanService)
    {
        $this->laporanAkhirKegiatanService = $laporanAkhirKegiatanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->laporanAkhirKegiatanService->getAllLaporanAkhir();
    }
}
