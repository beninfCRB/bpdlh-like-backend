<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\JenisKegiatanService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class JenisKegiatanController extends Controller
{
    protected $jenisKegiatanService;

    /**
     * @param JenisKegiatanService $jenisKegiatanService
     */
    public function __construct(JenisKegiatanService $jenisKegiatanService)
    {
        $this->jenisKegiatanService = $jenisKegiatanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->jenisKegiatanService->getAll();
    }
}
