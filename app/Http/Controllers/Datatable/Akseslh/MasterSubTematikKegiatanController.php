<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\MasterSubTematikKegiatanService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class MasterSubTematikKegiatanController extends Controller
{
    protected $masterSubTematikKegiatanService;

    /**
     * @param MasterSubTematikKegiatanService $masterSubTematikKegiatanService
     */
    public function __construct(MasterSubTematikKegiatanService $masterSubTematikKegiatanService)
    {
        $this->masterSubTematikKegiatanService = $masterSubTematikKegiatanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->masterSubTematikKegiatanService->getAll();
    }
}
