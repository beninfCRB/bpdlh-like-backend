<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\UserEksternalService;
use App\Service\Announcement\Performance\PerformanceService;
use Illuminate\Http\Request;

class UserEksternalController extends Controller
{
    protected $userEksternalService;

    /**
     * @param UserEksternalService $userEksternalService
     */
    public function __construct(UserEksternalService $userEksternalService)
    {
        $this->userEksternalService = $userEksternalService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->userEksternalService->getAll();
    }
}
