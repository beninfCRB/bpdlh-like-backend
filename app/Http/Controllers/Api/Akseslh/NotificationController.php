<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Services\Akseslh\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends ApiController
{
    public function __construct(
        Request $request
    ) {
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {

        try {
            $result = $request->user()->unreadNotifications;
            return $this->sendSuccess($result);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }
}
