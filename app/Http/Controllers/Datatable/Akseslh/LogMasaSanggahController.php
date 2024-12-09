<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\LogMasaSanggahService;

class LogMasaSanggahController extends Controller
{
    protected $logMasaSanggahService;

    /**
     * @param LogMasaSanggahService $logMasaSanggahService
     */
    public function __construct(LogMasaSanggahService $logMasaSanggahService)
    {
        $this->logMasaSanggahService = $logMasaSanggahService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->logMasaSanggahService->getAll();
    }
}
