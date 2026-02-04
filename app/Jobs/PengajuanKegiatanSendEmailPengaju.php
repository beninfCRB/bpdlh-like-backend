<?php

namespace App\Jobs;

use App\Services\EmailPhpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PengajuanKegiatanSendEmailPengaju implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user, $sendData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $sendData)
    {
        $this->user = $user;
        $this->sendData = $sendData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EmailPhpService $emailPhpService)
    {
        //
        $emailPhpService->pengajuanKegiatanBerhasilDikirim($this->user, 'Pengajuan Kegiatan Berhasil Dikirim', $this->sendData, null, 'mail.pengajuan-kegiatan-berhasil-dikirim');
    }
}
