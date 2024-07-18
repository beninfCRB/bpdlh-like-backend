<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\SatuanService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    protected $SatuanService;

    /**
     * @param SatuanService $SatuanService
     */
    public function __construct(SatuanService $SatuanService)
    {
        $this->SatuanService = $SatuanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->SatuanService->getAll();
    }
}
