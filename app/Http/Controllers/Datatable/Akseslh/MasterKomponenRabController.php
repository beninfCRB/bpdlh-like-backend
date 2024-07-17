<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\MasterKomponenRabService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class MasterKomponenRabController extends Controller
{
    protected $MasterKomponenRabService;

    /**
     * @param MasterKomponenRabService $MasterKomponenRabService
     */
    public function __construct(MasterKomponenRabService $MasterKomponenRabService)
    {
        $this->MasterKomponenRabService = $MasterKomponenRabService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->MasterKomponenRabService->getAll();
    }
}
