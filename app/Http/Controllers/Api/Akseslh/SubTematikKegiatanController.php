<?php

namespace App\Http\Controllers\Api\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Services\Akseslh\SubTematikKegiatanService;

class SubTematikKegiatanController extends ApiController
{
    protected $subTematikKegiatanService;

    public function __construct(
        SubTematikKegiatanService $subTematikKegiatanService,
        Request $request
    ) {
        $this->subTematikKegiatanService    =   $subTematikKegiatanService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'tematik_kegiatan_id'         => 'required|exists:tematik_kegiatans,id',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input = $validator->validated();

        $result = $this->subTematikKegiatanService->getApiAll($input);

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
        $input = [
            'tematik_kegiatan_id' => $id
        ];

        $result = $this->subTematikKegiatanService->getApiAll($input);

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
