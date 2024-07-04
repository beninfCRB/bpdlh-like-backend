<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\KelompokMasyarakatService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class KelompokMasyarakatController extends Controller
{
    protected $KelompokMasyarakatService;

    /**
     * @param KelompokMasyarakatService $KelompokMasyarakatService
     */
    public function __construct(KelompokMasyarakatService $KelompokMasyarakatService)
    {
        $this->KelompokMasyarakatService = $KelompokMasyarakatService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->KelompokMasyarakatService->getAll();
    }
}
