<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\BannerInformasiService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class BannerInformasiController extends Controller
{
    protected $bannerInformasiService;

    /**
     * @param BannerInformasiService $bannerInformasiService
     */
    public function __construct(BannerInformasiService $bannerInformasiService)
    {
        $this->bannerInformasiService = $bannerInformasiService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->bannerInformasiService->getAll();
    }
}
