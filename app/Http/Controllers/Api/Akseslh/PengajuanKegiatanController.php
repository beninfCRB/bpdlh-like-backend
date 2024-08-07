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

    public function show($id, Request $request)
    {
        $lang           = $request->input('lang')  ?: 'ID';

        $result = $this->pengajuanKegiatanService->apiLang($id, $lang);

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
            'judul_pengajuan_kegiatan'  => 'nullable|string|max:500',
            'provinsi_kegiatan'         => 'nullable',
            'kabupaten_kegiatan'        => 'nullable',
            'kecamatan_kegiatan'        => 'nullable',
            'kelurahan_kegiatan'        => 'nullable',
            'alamat_kegiatan'           => 'nullable',
            'tanggal_kegiatan'          => 'nullable',
            'waktu_kegiatan'            => 'nullable',
            'proposal_kegiatan'         => 'nullable',
            'tujuan_kegiatan'           => 'nullable',
            'ruang_lingkup_kegiatan'    => 'nullable',
            'fileDocument'              => 'nullable',
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

        if (isset($request->tanggal_kegiatan)) {
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

        //add new key for required field in table
        $input["user_akseslh_id"]           = $request->user()->id;
        $input['user']                      = $request->user();

        $result = $this->pengajuanKegiatanService->create($input);

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
        // dd($id, $request->all());

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
