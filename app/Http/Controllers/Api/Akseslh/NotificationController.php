<?php

namespace App\Http\Controllers\Api\Akseslh;

use stdClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\NotificationService;
use Illuminate\Support\Facades\DB;

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

            // $result = DB::table('notifications')->where(['id' => 'null'])->get();
            return $this->sendSuccess($result);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }
}
