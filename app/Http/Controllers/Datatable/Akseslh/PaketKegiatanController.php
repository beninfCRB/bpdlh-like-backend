<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\PaketKegiatanService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class PaketKegiatanController extends Controller
{
    protected $paketKegiatanService;

    /**
     * @param PaketKegiatanService $paketKegiatanService
     */
    public function __construct(PaketKegiatanService $paketKegiatanService)
    {
        $this->paketKegiatanService = $paketKegiatanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->paketKegiatanService->getAll();
    }
}
