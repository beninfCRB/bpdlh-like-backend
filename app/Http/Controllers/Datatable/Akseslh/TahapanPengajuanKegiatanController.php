<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\TahapanPengajuanKegiatanService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class TahapanPengajuanKegiatanController extends Controller
{
    protected $tahapanPengajuanKegiatanService;

    /**
     * @param TahapanPengajuanKegiatanService $tahapanPengajuanKegiatanService
     */
    public function __construct(TahapanPengajuanKegiatanService $tahapanPengajuanKegiatanService)
    {
        $this->tahapanPengajuanKegiatanService = $tahapanPengajuanKegiatanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->tahapanPengajuanKegiatanService->getAll();
    }
}
