<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\JenisPekerjaanService;

class JenisPekerjaanController extends Controller
{
    protected $jenisPekerjaanService;

    /**
     * @param JenisPekerjaanService $jenisPekerjaanService
     */
    public function __construct(JenisPekerjaanService $jenisPekerjaanService)
    {
        $this->jenisPekerjaanService = $jenisPekerjaanService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->jenisPekerjaanService->getAll();
    }
}
