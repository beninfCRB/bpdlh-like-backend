<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\MasterIndikatorService;

class MasterIndikatorController extends Controller
{
    protected $masterIndikatorService;

    /**
     * @param MasterIndikatorService $masterIndikatorService
     */
    public function __construct(MasterIndikatorService $masterIndikatorService)
    {
        $this->masterIndikatorService = $masterIndikatorService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->masterIndikatorService->getAll();
    }
}
