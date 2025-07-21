<?php

namespace App\Console\Commands;

use App\Models\MasterDataIndikatorLaporan;
use App\Models\MasterIndikator;
use Illuminate\Console\Command;

class SyncIndikator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:indikator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Singkronisasi data indikator ke master data indikator';

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
            $indikator = MasterIndikator::all();
            $masterDataIndikatorLaporan = MasterDataIndikatorLaporan::all();

            foreach ($masterDataIndikatorLaporan as $item) {
                $indikatorItem = $indikator->firstWhere('nama_indikator', $item->nama_indikator);
                if ($indikatorItem) {
                    $item->update([
                        'master_indikator_id' => $indikatorItem->id
                    ]);
                }
            }
            \DB::commit();
            $this->info('Data indikator berhasil disinkronkan.');
            $this->line('Jumlah data yang disinkronkan: ' . $masterDataIndikatorLaporan->count());
            $this->line('Jumlah data indikator master: ' . $indikator->count());
            $this->line('Jumlah data master data indikator laporan: ' . $masterDataIndikatorLaporan->count());
            return 0;
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollBack();
            $this->error('Terjadi kesalahan saat menyinkronkan data indikator: ' . $th->getMessage());
            return 1;
        }
    }
}
