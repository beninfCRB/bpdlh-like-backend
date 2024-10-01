<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Services\Akseslh\MasterDataBankService;
use Illuminate\Http\Request;

class MasterDataBankController extends ApiController
{
    protected $masterDataBankService;

    public function __construct(
        MasterDataBankService $masterDataBankService,
        Request $request
    ) {
        $this->masterDataBankService    =   $masterDataBankService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->masterDataBankService->getAllAttr();

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function show($id, Request $request)
    {
        $lang           = $request->input('lang')  ?: 'ID';

        $result = $this->masterDataBankService->apiLang($id, $lang);

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
