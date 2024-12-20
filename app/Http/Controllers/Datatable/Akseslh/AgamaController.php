<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\AgamaService;

class AgamaController extends Controller
{
    protected $agamaService;

    /**
     * @param AgamaService $agamaService
     */
    public function __construct(AgamaService $agamaService)
    {
        $this->agamaService = $agamaService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->agamaService->getAll();
    }
}
