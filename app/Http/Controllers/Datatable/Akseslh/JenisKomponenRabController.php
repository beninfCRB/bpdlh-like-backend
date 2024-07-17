<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\JenisKomponenRabService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class JenisKomponenRabController extends Controller
{
    protected $JenisKomponenRabService;

    /**
     * @param JenisKomponenRabService $JenisKomponenRabService
     */
    public function __construct(JenisKomponenRabService $JenisKomponenRabService)
    {
        $this->JenisKomponenRabService = $JenisKomponenRabService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->JenisKomponenRabService->getAll();
    }
}
