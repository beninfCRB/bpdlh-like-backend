<?php

namespace App\Http\Controllers\Api\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Services\Akseslh\RealisasiRabService;
use App\Notifications\PengajuanKegiatanNotification;

class RealisasiRabController extends ApiController
{
    protected $realisasiRabService;

    public function __construct(
        RealisasiRabService $realisasiRabService,
        Request $request
    ) {
        $this->realisasiRabService    =   $realisasiRabService;
        parent::__construct($request);
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'komponen_rab'                          => 'required|array', // Pastikan 'komponen_rab' adalah array
            'komponen_rab.*.id_komponen_rab'        => 'required|exists:rab_pengajuan_paket_kegiatans,id', // Pastikan id_komponen ada di tabel master_data_komponen
            'komponen_rab.*.harga_unit_realisasi'   => 'required|numeric|min:0', // Pastikan harga_unit adalah angka dan lebih besar dari 0
            'komponen_rab.*.qty_realisasi'          => 'required|numeric|min:1', // Pastikan qty adalah angka dan lebih besar dari 0
        ]);

        if ($validator->fails()) {
            # code...
            \Sentry\captureMessage('Validate Message: ' . $request->user()->email_pic . ' ' . $validator->getMessageBag(), \Sentry\Severity::warning());
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $user = $request->user();

        $result = $this->realisasiRabService->updateRab($id, $validator->validated(), $user);

        try {
            //code...
            if ($result->success) {
                // Make before notification read
                $request->user()->unreadNotifications->markAsRead();

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
}
