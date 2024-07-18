<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\SubTematikKegiatanService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class SubTematikKegiatanController extends Controller
{
    protected $subTematikKegiatanService;

    /**
     * @param SubTematikKegiatanService $subTematikKegiatanService
     */
    public function __construct(SubTematikKegiatanService $subTematikKegiatanService)
    {
        $this->subTematikKegiatanService = $subTematikKegiatanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->subTematikKegiatanService->getAll();
    }
}
