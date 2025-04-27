<?php

namespace App\Jobs;

use App\Models\PivotEmailBlast;
use App\Services\EmailPhpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailBlastJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $log;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PivotEmailBlast $log)
    {
        $this->log = $log;
    }
    /**
     * The number of seconds the job may be delayed.
     *
     * @var int
     */

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(EmailPhpService $emailPhpService)
    {
        $pengajuan = $this->log->pengajuan;
        $email = (object)['email' => $this->log->email];

        if (!$pengajuan || !$pengajuan->user_akseslh) {
            // Skip atau log error
            return;
        }


        // $subject = $this->log->status === 'diterima' ? 'Pengajuan Anda Diterima' : 'Pengajuan Anda Ditolak';
        // $view = $this->log->status === 'diterima' ? 'mail.verifikasi-pengajuan-kegiatan-diterima' : 'mail.verifikasi-pengajuan-kegiatan-ditolak';

        $dataSend = [
            'nomor_pengajuan' => $pengajuan->nomor_pengajuan,
            'catatan_log'     => $this->log->catatan_log ?? null,
            'keterangan'      => null,
            'status'          => null
        ];
        $dataSend['document_sk'] = env('APP_URL') . '/storage/';
        $dataSend['judul_pengajuan_kegiatan'] = $pengajuan->judul_pengajuan_kegiatan;
        $dataSend['kelompok_masyarakat'] = $pengajuan->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat;
        $dataSend['nama_pic'] = $pengajuan->user_akseslh->data_pic_kelompok_masyarakat->nama_pic;
        $dataSend['total'] = $pengajuan->rab_pengajuan_paket_kegiatans->sum(function ($item) {
            return $item->qty * $item->harga_unit;
        });

        if ($this->log->status === 'diterima') {
            # code...
            $emailPhpService->pengajuanKegiatanDiterima($pengajuan->user_akseslh, 'Pemberitahuan Persetujuan Pengajuan Proposal Akses Dana Layanan Masyarakat untuk Lingkungan', $dataSend, null, 'mail.pengajuan-kegiatan-diterima');
        } else {
            # code...
            $emailPhpService->verifikasiValidasiDitolak(
                $pengajuan->user_akseslh,
                'Pengajuan Kegiatan Belum Dapat Disetujui',
                $dataSend,
                null,
                'mail.verifikasi-pengajuan-kegiatan-ditolak'
            );
        }



        // $emailPhpService->verifikasiPengajuanKegiatan($email, $subject, $pengajuan, '', $view);

        $this->log->update(['sent_at' => now()]);
    }
}
