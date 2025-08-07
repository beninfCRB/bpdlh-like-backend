<?php

namespace App\Http\Controllers\Datatable\Akseslh;

use App\Http\Controllers\Controller;
use App\Services\Akseslh\ProfilePicService;

class ProfilePicController extends Controller
{
    protected $profilePicService;

    /**
     * @param ProfilePicService $profilePicService
     */
    public function __construct(ProfilePicService $profilePicService)
    {
        $this->profilePicService = $profilePicService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
        return $this->profilePicService->getAll();
    }
}
