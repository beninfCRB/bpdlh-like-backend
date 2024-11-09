<?php

namespace App\Http\Controllers\Api\Akseslh;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\PengajuanKegiatanService;
use App\Services\Akseslh\TransaksiPenyaluranService;

class DashboardController extends ApiController
{
    protected $pengajuanKegiatanService;
    protected $transaksiPenyaluranService;

    public function __construct(
        TransaksiPenyaluranService $transaksiPenyaluranService,
        PengajuanKegiatanService $pengajuanKegiatanService,
        Request $request
    ) {
        $this->transaksiPenyaluranService   = $transaksiPenyaluranService;
        $this->pengajuanKegiatanService    =   $pengajuanKegiatanService;
        parent::__construct($request);
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $result = $this->pengajuanKegiatanService->apiGetAll();

        try {
            if ($result->success) {
                $data = $result->data;
                $pengajuanBulanIni                  = $data->where('flag', '>=', 1)->where('flag', '<', 9)->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
                $pengajuanBulanSebelumnya           = $data->where('flag', '>=', 1)->where('flag', '<', 9)->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $pengajuanSelesaiBulanIni           = $data->where('flag', 9)->whereBetween('updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
                $pengajuanSelesaiBulanSebelumnya    = $data->where('flag', 9)->whereBetween('updated_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $pengajuanDibatalkanBulanIni        = $data->where('flag', 20)->whereBetween('updated_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();
                $pengajuanDibatalkanBulanSebelumnya = $data->where('flag', 20)->whereBetween('updated_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->count();
                $response = [
                    'jumlahPengajuanSdhi'                       => $data->where('flag', '>=', 1)->where('flag', '<', 9)->count(),
                    'jumlahPengajuanBulanIni'                   => $pengajuanBulanIni,
                    'jumlahPengajuanBulanSebelumnya'            => $pengajuanBulanSebelumnya,
                    'jumlahPengajuanSelesaiSdhi'                => $data->where('flag', 9)->count(),
                    'jumlahPengajuanSelesaiBulanIni'            => $pengajuanSelesaiBulanIni,
                    'jumlahPengajuanSelesaiBulanSebelumnya'     => $pengajuanSelesaiBulanSebelumnya,
                    'jumlahPengajuanDibatalkanSdhi'             => $data->where('flag', 20)->count(),
                    'jumlahPengajuanDibatalkanBulanIni'         => $pengajuanDibatalkanBulanIni,
                    'jumlahPengajuanDibatalkanBulanSebelumnya'  => $pengajuanDibatalkanBulanSebelumnya,
                    // 'persentasiPengajuan'               => (($pengajuanBulanIni - $pengajuanBulanSebelumnya) / $pengajuanBulanSebelumnya) * 100,
                    // 'persentasiPengajuanSelesai'        => (($pengajuanSelesaiBulanIni - $pengajuanSelesaiBulanSebelumnya) / $pengajuanSelesaiBulanSebelumnya) * 100,
                    // 'persentasiDibatalkan'              => (($pengajuanDibatalkanBulanIni - $pengajuanDibatalkanBulanSebelumnya) / $pengajuanDibatalkanBulanSebelumnya) * 100,

                ];
                return $this->sendSuccess($response, $result->message, $result->code);
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

    public function getDataPenyerapanDana()
    {
        $result = $this->pengajuanKegiatanService->getDataPenyerapanDana();

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
