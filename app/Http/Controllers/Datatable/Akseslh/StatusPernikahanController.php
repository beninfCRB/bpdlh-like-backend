<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\StatusPernikahanService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class StatusPernikahanController extends Controller
{
    protected $statusPernikahanService;

    /**
     * @param StatusPernikahanService $statusPernikahanService
     */
    public function __construct(StatusPernikahanService $statusPernikahanService)
    {
        $this->statusPernikahanService = $statusPernikahanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->statusPernikahanService->getAll();
    }
}
