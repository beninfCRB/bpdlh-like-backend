<?php

namespace App\Console\Commands;

use App\Models\PengajuanKegiatan;
use Illuminate\Console\Command;

class PengajuanKegiatanTolakDraft extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pengajuan:kegiatan-tolak-draft';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tolak draft pengajuan kegiatan';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \DB::beginTransaction();
        try {
            //code...
            $this->info('Memulai proses penolakan draft pengajuan kegiatan...');

            $count = PengajuanKegiatan::where('flag', 0)
                ->doesntHave('log_tahapan_pengajuan')
                ->update(['flag' => 20]); // Set flag to 20 (rejected)

            \DB::commit();
            $this->info('Berhasil menolak ' . $count . ' draft pengajuan kegiatan.');
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollBack();
            $this->error('Gagal menolak draft pengajuan kegiatan: ' . $th->getMessage());
        }

        return 0;
    }
}
