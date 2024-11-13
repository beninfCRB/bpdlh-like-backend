<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\MasterUserJenisKelompokService;

class MasterUserJenisKelompokController extends Controller
{
    protected $masterUserJenisKelompokService;

    /**
     * @param MasterUserJenisKelompokService $masterUserJenisKelompokService
     */
    public function __construct(MasterUserJenisKelompokService $masterUserJenisKelompokService)
    {
        $this->masterUserJenisKelompokService = $masterUserJenisKelompokService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->masterUserJenisKelompokService->getAll("1");
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUser($id)
    {
        return $this->masterUserJenisKelompokService->getAllUser($id);
    }
}
