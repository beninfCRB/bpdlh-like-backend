<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Services\Akseslh\KelompokMasyarakatService;
use Illuminate\Http\Request;

class KelompokMasyarakatController extends ApiController
{
    protected $kelompokMasyarakatService;

    public function __construct(
        KelompokMasyarakatService $kelompokMasyarakatService,
        Request $request
    ) {
        $this->kelompokMasyarakatService    =   $kelompokMasyarakatService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->kelompokMasyarakatService->apiGetAll();

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function byIdJenisKelompokMasyarakat($id)
    {
        $result = $this->kelompokMasyarakatService->apiGetByIdJenisKelompokMasyarakat($id);

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

        $result = $this->kelompokMasyarakatService->apiLang($id, $lang);

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
