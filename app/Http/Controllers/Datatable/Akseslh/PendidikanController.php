<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\PendidikanService;

class PendidikanController extends Controller
{
    protected $pendidikanService;

    /**
     * @param PendidikanService $pendidikanService
     */
    public function __construct(PendidikanService $pendidikanService)
    {
        $this->pendidikanService = $pendidikanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->pendidikanService->getAll();
    }
}
