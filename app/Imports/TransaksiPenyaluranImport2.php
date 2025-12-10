<?php

namespace App\Imports;

use App\Jobs\TransaksiPenyaluranEmailJob;
use App\Models\MasterDataBank;
use App\Models\PengajuanKegiatan;
use Illuminate\Support\Collection;
use App\Models\LogTahapanPengajuanKegiatan;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\DetailLogTahapanPengajuanKegiatan;
use App\Notifications\TransaksiPenyaluranNotification;

class TransaksiPenyaluranImport2 implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
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
                if ($pengajuan_kegiatan->flag != 7 || $pengajuan_kegiatan->transaksi_penyaluran->count() < 1 || $pengajuan_kegiatan->transaksi_penyaluran->count() > 1) {
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
                        'flag'                  => 1,
                        'username'              => auth()->user()->id,
                    ]);

                    $log = LogTahapanPengajuanKegiatan::where('pengajuan_kegiatan_id', $pengajuan_kegiatan->id)
                        ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                            $q->where('deskripsi_kegiatan', 'Konfirmasi Pencairan Dana Termin II');
                        })
                        ->first();

                    $log->tanggal_selesai = date('Y-m-d');
                    $log->user_akseslh_id = auth()->user()->id;
                    $log->save();

                    DetailLogTahapanPengajuanKegiatan::create([
                        'pengajuan_kegiatan_id'         => $pengajuan_kegiatan->id,
                        'tahapan_pengajuan_kegiatan_id' => $log->tahapan_pengajuan_kegiatan_id,
                        'tanggal_masuk'                 => date("Y-m-d"),
                        'tanggal_selesai'               => date("Y-m-d"),
                        'user_akseslh_id'               => auth()->user()->id,
                    ]);

                    LogTahapanPengajuanKegiatan::where('pengajuan_kegiatan_id', $pengajuan_kegiatan->id)
                        ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                            $q->where('deskripsi_kegiatan', 'Laporan Akhir Kegiatan');
                        })
                        ->update(['tanggal_masuk' => date("Y-m-d")]);

                    $pengajuan_kegiatan->user_akseslh->unreadNotifications->markAsRead();

                    $pengajuan_kegiatan->user_akseslh->notify(new TransaksiPenyaluranNotification($pengajuan_kegiatan->nomor_pengajuan, $pengajuan_kegiatan->user_akseslh->data_pic_kelompok_masyarakat->nama_pic, (float) $item[6]));

                    $dataSend = [
                        'nomor_pengajuan'   => $pengajuan_kegiatan->nomor_pengajuan,
                        'nomor_rekening'    => (float) $item[6],
                    ];

                    TransaksiPenyaluranEmailJob::dispatch($pengajuan_kegiatan->user_akseslh, 'Pemberitahuan Pencairan Dana Termin II', $dataSend, null, 'mail.pencairan-dana-termin-2');

                    $pengajuan_kegiatan->flag = 8;
                    $pengajuan_kegiatan->save();
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
