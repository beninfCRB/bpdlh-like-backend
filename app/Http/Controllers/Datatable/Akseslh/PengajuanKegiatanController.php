<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\PengajuanKegiatanService;
use App\Services\Akseslh\PaketKegiatanService;
use App\Services\Akseslh\UserEksternalService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class PengajuanKegiatanController extends Controller
{
    protected $PengajuanKegiatanService;
    protected $PaketKegiatanService;
    protected $UserEksternalService;

    /**
     * @param PaketKegiatanService $paketKegiatanService
     */
    public function __construct(
        PengajuanKegiatanService $PengajuanKegiatanService,
        PaketKegiatanService $PaketKegiatanService,
        UserEksternalService $UserEksternalService
    ) {
        $this->PengajuanKegiatanService     =   $PengajuanKegiatanService;
        $this->PaketKegiatanService         =   $PaketKegiatanService;
        $this->UserEksternalService         =   $UserEksternalService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->PengajuanKegiatanService->getAll();
    }

    public function getPaginate()
    {
        return $this->PengajuanKegiatanService->getPaginated();
    }
}
