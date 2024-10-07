<?php

namespace App\Exports;

use App\Models\TransaksiPenyaluran;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransaksiPenyaluranExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Filter data berdasarkan rentang tanggal
        return TransaksiPenyaluran::with(
            'master_data_bank',
            'pengajuan_kegiatan.user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat.jenis'
        )->get();
    }

    public function map($transaksiPenyaluran): array
    {
        return [
            $transaksiPenyaluran->pengajuan_kegiatan->nomor_pengajuan,
            $transaksiPenyaluran->pengajuan_kegiatan->created_at,
            $transaksiPenyaluran->pengajuan_kegiatan->user_akseslh
                ->data_pic_kelompok_masyarakat->kelompok_masyarakat
                ->kelompok_masyarakat,
            $transaksiPenyaluran->pengajuan_kegiatan->user_akseslh
                ->data_pic_kelompok_masyarakat->kelompok_masyarakat
                ->jenis->jenis_kelompok_masyarakat,
            $transaksiPenyaluran->tanggal_penyaluran,
            $transaksiPenyaluran->nilai_penyaluran,
            $transaksiPenyaluran->master_data_bank->nama_bank,
            $transaksiPenyaluran->nomor_rekening,
            $transaksiPenyaluran->nama_pemilik_rekening,
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor Pengajuan',
            'Tanggal Pengajuan',
            'Nama Kelompok',
            'Jenis Kelompok',
            'Tanggal Transaksi Pencairan',
            'Nilai Transaksi Pencairan',
            'Bank Penerima',
            'Nomor Rekening Penerima',
            'Nama Rekening Penerima'
        ];
    }
}
