<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\JenisDokumenService;
use Illuminate\Http\Request;

class JenisDokumenController extends ApiController
{
    protected $jenisDokumenService;

    public function __construct(
        JenisDokumenService $jenisDokumenService,
        Request $request
    ) {
        $this->jenisDokumenService    =   $jenisDokumenService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $flag = $request->query('flag');
        $result = $this->jenisDokumenService->apiGetAll($flag);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function show($id)
    {

        $result = $this->jenisDokumenService->getById($id);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }
}
