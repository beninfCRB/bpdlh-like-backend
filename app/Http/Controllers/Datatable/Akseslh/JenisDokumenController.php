<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\JenisDokumenService;

class JenisDokumenController extends Controller
{
    protected $jenisDokumenService;

    /**
     * @param JenisDokumenService $jenisDokumenService
     */
    public function __construct(JenisDokumenService $jenisDokumenService)
    {
        $this->jenisDokumenService = $jenisDokumenService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->jenisDokumenService->getAll();
    }
}
