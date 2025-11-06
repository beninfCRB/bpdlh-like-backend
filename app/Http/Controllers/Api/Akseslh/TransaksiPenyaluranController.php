<?php

namespace App\Http\Controllers\Api\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\PengajuanKegiatanService;
use Illuminate\Support\Facades\Validator;
use App\Services\Akseslh\TransaksiPenyaluranService;

class TransaksiPenyaluranController extends ApiController
{
    protected $transaksiPenyaluranService;
    protected $pengajuanKegiatanService;

    public function __construct(
        TransaksiPenyaluranService $transaksiPenyaluranService,
        PengajuanKegiatanService $pengajuanKegiatanService,
        Request $request
    ) {
        $this->transaksiPenyaluranService    =   $transaksiPenyaluranService;
        $this->pengajuanKegiatanService    =   $pengajuanKegiatanService;
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

    public function getPengajuanKegiatan(): \Illuminate\Http\JsonResponse
    {
        $input = request()->query('flag');
        $result = $this->transaksiPenyaluranService->apiGetPengajuanKegiatan($input);

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

        $result = $this->pencairanDanaService->apiLang($id, $lang);

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
        // Handle null string dari frontend
        $requestData = $request->all();

        // Konversi string "null", empty string, atau "undefined" menjadi null untuk field file
        if (
            isset($requestData['surat_keterangan']) &&
            (is_string($requestData['surat_keterangan']) &&
                (strtolower($requestData['surat_keterangan']) === 'null' ||
                    $requestData['surat_keterangan'] === '' ||
                    strtolower($requestData['surat_keterangan']) === 'undefined'))
        ) {
            $requestData['surat_keterangan'] = null;
        }

        // Jika surat_keterangan adalah null atau tidak ada file, hapus dari request data untuk validasi
        if (
            !$request->hasFile('surat_keterangan') ||
            (isset($requestData['surat_keterangan']) && $requestData['surat_keterangan'] === null)
        ) {
            unset($requestData['surat_keterangan']);
        }

        // Menambahkan custom rule untuk mengecek apakah inputan sama dengan "undefined"
        Validator::extend('not_undefined', function ($attribute, $value, $parameters, $validator) {
            return $value !== 'undefined'; // Mengembalikan false jika nilai "undefined"
        });

        $validator = Validator::make($requestData, [
            'master_data_bank_id'       => 'required|exists:master_data_banks,id',
            'pengajuan_kegiatan_id'     => 'required|exists:pengajuan_kegiatans,id',
            'nomor_rekening'            => 'required',
            'nama_pemilik_rekening'     => 'required',
            'nilai_penyaluran'          => 'required',
            'tanggal_penyaluran'        => 'required|not_undefined',
            'surat_keterangan'          => 'sometimes|file|mimes:pdf'
        ], [
            'tanggal_penyaluran.not_undefined' => 'The tanggal penyaluran wajib diisi.'
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input              = $validator->validated();

        $input['username']  = $request->user()->id;
        $input['user_akseslh'] = $request->user();

        $result = $this->transaksiPenyaluranService->create($input);

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
