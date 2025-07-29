<?php

namespace App\Jobs;

use App\Models\TolakPengajuanDanProfil;
use App\Services\Akseslh\ProfileService;
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
    public function handle(VerifikasiService $verifikasiService, ProfileService $profileService)
    {
        //
        $rows = TolakPengajuanDanProfil::whereIn('id', $this->chunkIds)->with(['user_akseslh'])->get();

        foreach ($rows as $data) {
            try {
                if ($data->status_penolakan == 'profil') {
                    $dataSend = [
                        'catatan_log' => $data->catatan_penolakan,
                    ];
                    $result = $profileService->delet_profile_job($data->email_pic, $dataSend);

                    if ($result->success) {
                        $data->update(['status' => 'approved']);
                    }
                } elseif ($data->status_penolakan == 'pengajuan') {
                    if (!$data->pengajuan_kegiatan) {
                        continue;
                    }

                    $id = $data->pengajuan_kegiatan->id ?? null;

                    $dataSend = [
                        'user_akseslh_id' => $data->username,
                        'user_akseslh'  => $data->user_akseslh,
                        'catatan_log' => $data->catatan_penolakan,
                        'status' => 0
                    ];

                    $result = $verifikasiService->updateTemp($id, $dataSend);

                    if ($result->success) {
                        $data->update(['status' => 'approved']);
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
