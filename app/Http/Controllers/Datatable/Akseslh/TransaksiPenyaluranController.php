<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\TransaksiPenyaluranService;

class TransaksiPenyaluranController extends Controller
{
    protected $transaksiPenyaluranService;

    /**
     * @param TransaksiPenyaluranService $transaksiPenyaluranService
     */
    public function __construct(TransaksiPenyaluranService $transaksiPenyaluranService)
    {
        $this->transaksiPenyaluranService = $transaksiPenyaluranService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->transaksiPenyaluranService->getAll();
    }
}
