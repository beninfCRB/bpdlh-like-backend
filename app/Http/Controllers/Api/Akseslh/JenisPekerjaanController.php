<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\JenisPekerjaanService;
use Illuminate\Http\Request;

class JenisPekerjaanController extends ApiController
{
    protected $jenisPekerjaanService;

    public function __construct(
        JenisPekerjaanService $jenisPekerjaanService,
        Request $request
    ) {
        $this->jenisPekerjaanService    =   $jenisPekerjaanService;
        parent::__construct($request);
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $result = $this->jenisPekerjaanService->getAllAttr();

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

        $result = $this->jenisPekerjaanService->getById($id);

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
