<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\JenisKelompokMasyarakatService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class JenisKelompokMasyarakatController extends Controller
{
    protected $jenisKelompokMasyarakatService;

    /**
     * @param JenisKelompokMasyarakatService $jenisKelompokMasyarakatService
     */
    public function __construct(JenisKelompokMasyarakatService $jenisKelompokMasyarakatService)
    {
        $this->jenisKelompokMasyarakatService = $jenisKelompokMasyarakatService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->jenisKelompokMasyarakatService->getAll();
    }
}
