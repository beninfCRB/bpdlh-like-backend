<?php

namespace App\Imports;

use App\Models\MasterDataBank;
use App\Models\PengajuanKegiatan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TransaksiPenyaluranImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        //
        // Ambil semua data bank dari database
        $master_data_bank = MasterDataBank::all();

        foreach ($collection as $item) {
            \Log::warning($item);
            // Pastikan $item memiliki data yang cukup
            if (count($item) < 6) {
                continue; // Lewati jika data tidak lengkap
            }

            // Cari pengajuan berdasarkan nomor
            $pengajuan_kegiatan = PengajuanKegiatan::where('nomor_pengajuan', $item[1])->first();

            if ($pengajuan_kegiatan) {
                if ($pengajuan_kegiatan->flag != 4 || $pengajuan_kegiatan->transaksi_penyaluran->count() > 1) {
                    # code...
                    continue;
                }
                // Cari bank yang cocok
                $bank = $master_data_bank->first(function ($bankItem) use ($item) {
                    return stripos($bankItem->nama_bank, $item[2]) !== false;
                });

                // Pastikan bank ditemukan
                if ($bank) {
                    $pengajuan_kegiatan->transaksi_penyaluran()->create([
                        'master_data_bank_id'   => $bank->id,
                        'nomor_rekening'        => (string) $item[3],
                        'nama_pemilik_rekening' => $item[4],
                        'tanggal_penyaluran'    => \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[5])), // Pastikan tanggal valid
                        'nilai_penyaluran'      => (float) $item[6],
                    ]);
                } else {
                    // Tambahkan log atau catatan jika bank tidak ditemukan
                    \Log::warning("Bank tidak ditemukan: " . $item[1]);
                }
            } else {
                // Tambahkan log jika pengajuan tidak ditemukan
                \Log::warning("Pengajuan tidak ditemukan: " . $item[0]);
            }
        }
    }
}
