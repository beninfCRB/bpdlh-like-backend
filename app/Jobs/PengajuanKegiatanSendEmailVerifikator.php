<?php

namespace App\Jobs;

use App\Services\EmailPhpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PengajuanKegiatanSendEmailVerifikator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user, $model;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $model)
    {
        $this->user = $user;
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EmailPhpService $emailPhpService)
    {
        //
        $emailPhpService->verifikasiPengajuanKegiatan($this->user, 'Verifikasi Pengajuan Kegiatan', $this->model, null, 'mail.verifikasi-pengajuan-kegiatan');
    }
}
