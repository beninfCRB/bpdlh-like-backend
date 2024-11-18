<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\InformasiPencairanDanaService;
use App\Services\Akseslh\PengajuanKegiatanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InformasiPencairanDanaController extends ApiController
{
    protected $InformasiPencairanDanaService;
    protected $pengajuanKegiatanService;

    public function __construct(
        InformasiPencairanDanaService $InformasiPencairanDanaService,
        PengajuanKegiatanService $pengajuanKegiatanService,
        Request $request
    ) {
        $this->InformasiPencairanDanaService    =   $InformasiPencairanDanaService;
        $this->pengajuanKegiatanService         =   $pengajuanKegiatanService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->InformasiPencairanDanaService->apiGetAll();

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function show($id, Request $request)
    {
        $lang           = $request->input('lang')  ?: 'ID';

        $result = $this->InformasiPencairanDanaService->apiLang($id, $lang);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'master_data_bank_id'               => 'required|exists:master_data_banks,id',
            'log_tahapan_pengajuan_kegiatan_id' => 'required|exists:log_tahapan_pengajuan_kegiatans,id',
            'nama_cabang'                       => 'required',
            'jenis_rekening'                    => 'required',
            'nama_pemilik_rekening'             => 'required',
            'nomor_rekening'                    => 'required'
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input          = $validator->validated();

        $result = $this->InformasiPencairanDanaService->create($input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }
    public function update($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'perjanjian_kerjasama'                  => 'required|file|mimes:pdf,jpg,png',
            'tanggal_kegiatan'                      => 'nullable|string|regex:/^\d{4}-\d{2}-\d{2} \- \d{4}-\d{2}-\d{2}$/',
            'waktu_kegiatan'                        => 'nullable|string|regex:/^\d{2}:\d{2} \- \d{2}:\d{2}$/',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input          = $validator->validated();

        if (isset($request->tanggal_kegiatan) && isset($request->waktu_kegiatan)) {
            # code...
            $tanggalArray   = explode(" - ", $input["tanggal_kegiatan"]);
            $waktuArray     = explode(" - ", $input["waktu_kegiatan"]);
            $input["tanggal_mulai_kegiatan"]    = $tanggalArray[0];
            $input["tanggal_akhir_kegiatan"]    = $tanggalArray[1];
            $input["time_mulai_kegiatan"]       = $waktuArray[0];
            $input["time_akhir_kegiatan"]       = $waktuArray[1];

            // Validasi rentang tanggal dan waktu (optional)
            if (strtotime($input["tanggal_mulai_kegiatan"]) > strtotime($input["tanggal_akhir_kegiatan"])) {
                return $this->sendError(null, 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.', 422);
            }

            if (strtotime($input["time_mulai_kegiatan"]) > strtotime($input["time_akhir_kegiatan"])) {
                return $this->sendError(null, 'Waktu mulai tidak boleh lebih besar dari waktu akhir.', 422);
            }

            //eliminate unnecessary key 
            unset($input["tanggal_kegiatan"]);
            unset($input["waktu_kegiatan"]);
        }

        $result = $this->pengajuanKegiatanService->updateInformasiPencairanDana($id, $input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function showSk($id, Request $request)
    {
        $input['user_akseslh'] = $request->user();

        $result = $this->pengajuanKegiatanService->getSk($id, $input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function showProposal($id, Request $request)
    {
        $input['user_akseslh'] = $request->user();

        $result = $this->pengajuanKegiatanService->getProposal($id, $input);

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
