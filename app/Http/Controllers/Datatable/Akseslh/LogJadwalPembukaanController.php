<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\LogJadwalPembukaanService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class LogJadwalPembukaanController extends Controller
{
    protected $logJadwalPembukaanService;

    /**
     * @param LogJadwalPembukaanService $logJadwalPembukaanService
     */
    public function __construct(LogJadwalPembukaanService $logJadwalPembukaanService)
    {
        $this->logJadwalPembukaanService = $logJadwalPembukaanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->logJadwalPembukaanService->getAll();
    }
}
