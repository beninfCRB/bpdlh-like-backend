<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\UserAkseslhService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class UserAkseslhController extends Controller
{
    protected $userAkseslhService;

    /**
     * @param UserAkseslhService $userAkseslhService
     */
    public function __construct(UserAkseslhService $userAkseslhService)
    {
        $this->userAkseslhService = $userAkseslhService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->userAkseslhService->getAll();
    }
}
