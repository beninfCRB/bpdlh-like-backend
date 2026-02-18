<?php

namespace App\Http\Controllers\Api\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Services\Akseslh\IndikatorLaporanKegiatanService;

class IndikatorLaporanKegiatanController extends ApiController
{
    protected $indikatorLaporanService;

    public function __construct(
        IndikatorLaporanKegiatanService $indikatorLaporanService,
        Request $request
    ) {
        $this->indikatorLaporanService    =   $indikatorLaporanService;
        parent::__construct($request);
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->indikatorLaporanService->apiGetAll();

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

        $result = $this->indikatorLaporanService->apiLang($id, $lang);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'master_data_indikator_laporan_id'  => 'required|exists:master_data_indikator_laporans,id',
            'pengajuan_kegiatan_id'             => 'required|exists:pengajuan_kegiatans,id',
            'nilai_laporan'                     => 'required',
            'peserta_laki_laki'                 => 'required',
            'peserta_perempuan'                 => 'required',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input          = $validator->validated();

        $result = $this->indikatorLaporanService->create($input);

        try {
            if ($result->success) {
                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_realisasi_kegiatan'                                => 'required|string|regex:/^\d{4}-\d{2}-\d{2} \- \d{4}-\d{2}-\d{2}$/',
            'longitude'                                                 => 'required|string',
            'latitude'                                                  => 'required|string',
            'alamat_kegiatan_realisasi'                                 => 'required|string|max:255',
            'indikator_kegiatan'                                        => 'nullable|array',
            'indikator_kegiatan.*.master_data_indikator_laporan_id'     => 'nullable|exists:master_indikators,id',
            'indikator_kegiatan.*.nilai_laporan'                        => 'nullable',
            'testimonial'                                               => 'required|string|max:800',
            'capaian_output'                                            => 'required|string|max:850',
            'capaian_outcome'                                           => 'required|string|max:850',
            'kendala_kegiatan'                                         => 'required|string|max:850',
        ]);

        if ($validator->fails()) {
            # code...
            \Sentry\captureMessage('Validate Message: ' . $request->user()->email . ' ' . $validator->getMessageBag(), \Sentry\Severity::warning());
            return $this->sendError($request->all(), $validator->getMessageBag(), 422);
        }

        $input  = $validator->validated();

        if (isset($request->tanggal_realisasi_kegiatan)) {
            # code...
            $tanggalArray   = explode(" - ", $input["tanggal_realisasi_kegiatan"]);
            $input["tanggal_mulai_kegiatan"]    = $tanggalArray[0];
            $input["tanggal_akhir_kegiatan"]    = $tanggalArray[1];

            // Validasi rentang tanggal dan waktu (optional)
            if (strtotime($input["tanggal_mulai_kegiatan"]) > strtotime($input["tanggal_akhir_kegiatan"])) {
                return $this->sendError(null, 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.', 422);
            }

            //eliminate unnecessary key
            unset($input["tanggal_realisasi_kegiatan"]);
        }

        $input['user']  = $request->user();

        $result = $this->indikatorLaporanService->update($id, $input);

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
