<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\TematikKegiatanService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class TematikKegiatanController extends Controller
{
    protected $tematikKegiatanService;

    /**
     * @param TematikKegiatanService $tematikKegiatanService
     */
    public function __construct(TematikKegiatanService $tematikKegiatanService)
    {
        $this->tematikKegiatanService = $tematikKegiatanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->tematikKegiatanService->getAll();
    }
}
