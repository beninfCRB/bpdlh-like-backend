<?php

namespace App\Http\Controllers\Api\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Services\Akseslh\PengajuanKegiatanService;
use App\Services\Akseslh\ValidasiPengajuanKegiatanService;

class ValidasiPengajuanKegiatanController extends ApiController
{
    protected $validasiPengajuanKegiatanService;
    protected $pengajuanKegiatanService;

    public function __construct(
        ValidasiPengajuanKegiatanService $validasiPengajuanKegiatanService,
        PengajuanKegiatanService $pengajuanKegiatanService,
        Request $request
    ) {
        $this->validasiPengajuanKegiatanService     =   $validasiPengajuanKegiatanService;
        $this->pengajuanKegiatanService             =   $pengajuanKegiatanService;
        parent::__construct($request);
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $input = request()->query('flag');
        $result = $this->validasiPengajuanKegiatanService->getAllAttr($input);

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
        $input['pengajuan_id'] = $id;

        $result = $this->validasiPengajuanKegiatanService->apiGetBydId($input['pengajuan_id']);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function update($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status'        => 'required',
            'catatan_log'   => 'nullable',
        ]);

        $validator->sometimes('file_sk', 'required|file|mimes:pdf', function ($input) {
            return $input->status != 0;
        });

        $validator->sometimes('nomor_sptjm', 'required|string', function ($input) {
            return $input->status != 0;
        });

        if ($validator->fails()) {
            # code...
            \Sentry\captureMessage('Validate Message: ' . $request->user()->email . ' ' . json_encode($validator->errors()->all()), \Sentry\Severity::warning());
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input = $validator->validated();

        $input['user_akseslh_id']  = $request->user()->id;
        $input['user']           = $request->user();

        $result = $this->validasiPengajuanKegiatanService->updateTemp($id, $input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function update_termin_1($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'                => 'required',
            'catatan_log'           => 'nullable',
            'jumlah_pengembalian'   => 'nullable|numeric',
        ]);

        $validator->sometimes('surat_pencairan_dana_termin_2', 'required|file|mimes:pdf', function ($input) {
            return $input->status != 0;
        });

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input = $validator->validated();

        $input['user']  = $request->user();

        $result = $this->validasiPengajuanKegiatanService->update_termin_1($id, $input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function update_tahap_akhir($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'        => 'required',
        ]);

        $validator->sometimes('catatan_log', 'required|string', function ($input) {
            return $input->status == 0;
        });

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input = $validator->validated();

        $input['user']  = $request->user();

        $result = $this->validasiPengajuanKegiatanService->update_tahap_akhir($id, $input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function retur_pengajuan_kegiatan($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'catatan_log'       => 'required|string',
            'caping_rab'        => 'required'
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input = $validator->validated();

        $input['user_akseslh_id']  = $request->user()->id;

        $result = $this->validasiPengajuanKegiatanService->retur_pengajuan_kegiatan($id, $input);

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
