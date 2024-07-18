<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\DataPicKelompokMasyarakatService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class DataPicKelompokMasyarakatController extends Controller
{
    protected $dataPicKelompokMasyarakatService;

    /**
     * @param DataPicKelompokMasyarakatService $dataPicKelompokMasyarakatService
     */
    public function __construct(DataPicKelompokMasyarakatService $dataPicKelompokMasyarakatService)
    {
        $this->dataPicKelompokMasyarakatService = $dataPicKelompokMasyarakatService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->dataPicKelompokMasyarakatService->getAll();
    }
}
