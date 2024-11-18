<?php

namespace App\Http\Controllers\Api\Akseslh;

use App\Services\PdfService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Services\Akseslh\PengajuanKegiatanService;
use App\Notifications\PengajuanKegiatanNotification;
use Carbon\Carbon;

class PengajuanKegiatanController extends ApiController
{
    protected $pengajuanKegiatanService;
    protected $pdfService;

    public function __construct(
        PengajuanKegiatanService $pengajuanKegiatanService,
        PdfService $pdfService,
        Request $request
    ) {
        $this->pengajuanKegiatanService    =   $pengajuanKegiatanService;
        $this->pdfService = $pdfService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->pengajuanKegiatanService->apiGetAll();

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function show(Request $request)
    {
        $result = $this->pengajuanKegiatanService->getDraftPengajuan($request->user()->id);

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
            'paket_kegiatan_id'         => 'required|exists:paket_kegiatans,id',
            'lokasi_bidang_folu_id'     => 'nullable|exists:lokasi_bidang_folus,id',
            'judul_pengajuan_kegiatan'  => 'required|string|max:500',
            'provinsi_kegiatan'         => 'required',
            'kabupaten_kegiatan'        => 'required',
            'kecamatan_kegiatan'        => 'required',
            'kelurahan_kegiatan'        => 'required',
            'alamat_kegiatan'           => 'required',
            'tanggal_kegiatan'          => 'required|string|regex:/^\d{4}-\d{2}-\d{2} \- \d{4}-\d{2}-\d{2}$/',
            'waktu_kegiatan'            => 'required|string|regex:/^\d{2}:\d{2} \- \d{2}:\d{2}$/',
            'proposal_kegiatan'         => 'required|max:255',
            'tujuan_kegiatan'           => 'required|max:255',
            'ruang_lingkup_kegiatan'    => 'required|max:255',
            'fileDocument'              => 'nullable',
            'nomor_pengajuan'           => 'nullable',
        ]);

        if (isset($request->fileDocument)) {
            # code...
            $input['fileDocument'] = $request->file('fileDocument');
        }

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

        //add new key for required field in table
        $input["user_akseslh_id"]           = $request->user()->id;
        $input['user']                      = $request->user();

        if (isset($input['nomor_pengajuan'])) {
            # code...
            $result = $this->pengajuanKegiatanService->update($input['nomor_pengajuan'], $input);
        } else {
            $result = $this->pengajuanKegiatanService->create($input);
        }

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function update($id, Request $request)
    {
        $result = $this->pengajuanKegiatanService->updateRab($id, $request->komponen_rab);

        try {
            //code...
            if ($result->success) {
                // Send notification database
                $request->user()->notify(new PengajuanKegiatanNotification($result->data['nomor_pengajuan'], $result->data['atas_nama'], $result->data['sebesar']));

                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            //throw $th;
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function getDataRab($id): \Illuminate\Http\JsonResponse
    {
        $result = $this->pengajuanKegiatanService->getDataRab($id);

        try {
            //code...
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            //throw $th;
            $this->sendError($exception->getMessage(), "", 500);
        }
    }
}
