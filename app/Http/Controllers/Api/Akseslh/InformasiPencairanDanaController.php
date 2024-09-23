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
            $this->sendError($exception->getMessage(), "", 500);
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
            $this->sendError($exception->getMessage(), "", 500);
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
            $this->sendError($exception->getMessage(), "", 500);
        }
    }
    public function update($id, Request $request): \Illuminate\Http\JsonResponse
    {
        // dd($id, $request->all());
        $validator = Validator::make($request->all(), [
            // 'master_data_bank_id'               => 'required|exists:master_data_banks,id',
            // 'log_tahapan_pengajuan_kegiatan_id' => 'required|exists:log_tahapan_pengajuan_kegiatans,id',
            // 'nama_cabang'                       => 'required',
            // 'jenis_rekening'                    => 'required',
            // 'nama_pemilik_rekening'             => 'required',
            // 'nomor_rekening'                    => 'required'
            'perjanjian_kerjasama'                  => 'required|file|mimes:pdf',
            'tanggal_kegiatan'                      => 'nullable',
            'waktu_kegiatan'                        => 'nullable',
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
            $this->sendError($exception->getMessage(), "", 500);
        }
    }
}
