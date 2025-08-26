<?php

namespace App\Jobs;

use App\Models\TolakPengajuanDanProfil;
use App\Services\Akseslh\ProfileService;
use App\Services\Akseslh\ValidasiPengajuanKegiatanService;
use App\Services\Akseslh\VerifikasiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TolakPengajuanDanProfilJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chunkIds;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $chunkIds)
    {
        //
        $this->chunkIds = $chunkIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(VerifikasiService $verifikasiService, ProfileService $profileService, ValidasiPengajuanKegiatanService $validasiPengajuanKegiatanService)
    {
        //
        $rows = TolakPengajuanDanProfil::whereIn('id', $this->chunkIds)->with(['user_akseslh'])->get();

        foreach ($rows as $data) {
            try {
                if ($data->status_penolakan == 'profil') {

                    // Jika Email PIC tidak sesuai dengan email yang tercantum di pengajuan, maka akan diskip
                    if ($data->email_pic != $data->pengajuan_kegiatan->user_akseslh->email) {
                        $data->update(['status' => 'rejected', 'catatan_penolakan' => 'Profil tidak ditemukan']);
                        continue;
                    }

                    $dataSend = [
                        'pengajuan_kegiatan_id' => $data->pengajuan_kegiatan->id,
                        'user'                  => $data->user_akseslh,
                        'catatan_log'           => $data->catatan_penolakan,
                    ];

                    $result = $profileService->delete_profile($data->pic_kelompok_masyarakat->id, $dataSend, false);

                    if ($result->success) {
                        $data->update(['status' => 'approved']);
                    }

                    $data->update(['status' => 'rejected', 'catatan_penolakan' => $result->message]);
                } elseif ($data->status_penolakan == 'pengajuan') {

                    if (!$data->pengajuan_kegiatan) {
                        $data->update(['status' => 'rejected', 'catatan_penolakan' => 'Pengajuan Kegiatan tidak ditemukan']);
                        continue;
                    }

                    if ($data->pengajuan_kegiatan->flag == 1) {
                        # code...
                        $id = $data->pengajuan_kegiatan->id ?? null;

                        $dataSend = [
                            'user_akseslh_id' => $data->username,
                            'user_akseslh'  => $data->user_akseslh,
                            'catatan_log' => $data->catatan_penolakan,
                            'status' => 0
                        ];

                        $result = $verifikasiService->updateTemp($id, $dataSend, false);

                        if ($result->success) {
                            $data->update(['status' => 'approved']);
                        }

                        $data->update(['status' => 'rejected', 'catatan_penolakan' => $result->message]);
                    } elseif ($data->pengajuan_kegiatan->flag == 2) {
                        $id = $data->pengajuan_kegiatan->id ?? null;

                        $dataSend = [
                            'user_akseslh_id' => $data->username,
                            'user'  => $data->user_akseslh,
                            'catatan_log' => $data->catatan_penolakan,
                            'status' => 0
                        ];

                        $result = $validasiPengajuanKegiatanService->updateTemp($id, $dataSend, false);

                        if ($result->success) {
                            # code...
                            $data->update(['status' => 'approved']);
                        }

                        $data->update(['status' => 'rejected', 'catatan_penolakan' => $result->message]);
                    }
                }
            } catch (\Throwable $th) {
                //throw $th;
                $data->update(['status' => 'rejected', 'catatan_penolakan' => $th->getMessage()]);
            }
        }
    }

    protected function tolakProfil($data) {}
}
