<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\TolakPengajuanDanProfilService;

class TolakPengajuanDanProfilController extends Controller
{
    protected $tolakPengajuanDanProfilService;

    /**
     * @param TolakPengajuanDanProfilService $tolakPengajuanDanProfilService
     */
    public function __construct(TolakPengajuanDanProfilService $tolakPengajuanDanProfilService)
    {
        $this->tolakPengajuanDanProfilService = $tolakPengajuanDanProfilService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->tolakPengajuanDanProfilService->getAll();
    }
}
