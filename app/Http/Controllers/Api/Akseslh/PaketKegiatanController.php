<?php

namespace App\Http\Controllers\Api\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Services\Akseslh\PaketKegiatanService;

class PaketKegiatanController extends ApiController
{
    protected $paketKegiatanService;

    public function __construct(
        PaketKegiatanService $paketKegiatanService,
        Request $request
    ) {
        $this->paketKegiatanService    =   $paketKegiatanService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tematik_kegiatan_id'         => 'required|exists:tematik_kegiatans,id',
            'sub_tematik_kegiatan_id'     => 'required|exists:sub_tematik_kegiatans,id',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input = $validator->validated();

        $result = $this->paketKegiatanService->apiGetAll($input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function byIdTematik($tematik_kegiatan_id, $sub_tematik_kegiatan_id)
    {
        $input = [
            'tematik_kegiatan_id'       => $tematik_kegiatan_id,
            'sub_tematik_kegiatan_id'   => $sub_tematik_kegiatan_id,
        ];

        $result = $this->paketKegiatanService->apiGetAll($input);

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

        $result = $this->paketKegiatanService->apiLang($id, $lang);

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
