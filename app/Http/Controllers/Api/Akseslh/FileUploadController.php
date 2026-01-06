<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class FileUploadController extends ApiController
{
    protected $fileUploadService;

    public function __construct(
        FileUploadService $fileUploadService,
        Request $request
    ) {
        $this->fileUploadService    =   $fileUploadService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->fileUploadService->apiGetAll();

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function show($id)
    {
        $result = $this->fileUploadService->show($id);
        return $result;
        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }
}
