<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\MasterDataBankService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class MasterDataBankController extends Controller
{
    protected $MasterDataBankService;

    /**
     * @param MasterDataBankService $MasterDataBankService
     */
    public function __construct(MasterDataBankService $MasterDataBankService)
    {
        $this->MasterDataBankService = $MasterDataBankService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->MasterDataBankService->getAll();
    }
}
