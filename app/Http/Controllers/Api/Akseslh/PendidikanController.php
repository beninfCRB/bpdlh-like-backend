<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\PendidikanService;
use Illuminate\Http\Request;

class PendidikanController extends ApiController
{
    protected $pendidikan;

    public function __construct(
        PendidikanService $pendidikan,
        Request $request
    ) {
        $this->pendidikan    =   $pendidikan;
        parent::__construct($request);
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $result = $this->pendidikan->getAllAttr();

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

        $result = $this->pendidikan->getById($id);

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
