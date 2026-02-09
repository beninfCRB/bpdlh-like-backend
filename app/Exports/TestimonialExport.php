<?php

namespace App\Exports;

use App\Models\Testimonial;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class TestimonialExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        //
        return Testimonial::with([
            'data_pic_kelompok_masyarakat' => function ($query) {
                $query->withTrashed();
            },
            'data_pic_kelompok_masyarakat.kelompok_masyarakat' => function ($query) {
                $query->withTrashed();
            },
            'data_pic_kelompok_masyarakat.kelompok_masyarakat.jenis' => function ($query) {
                $query->withTrashed();
            },
        ])->get();
    }

    public function headings(): array
    {
        return [
            'Jenis Kelompok',
            'Nama Kelompok',
            'Nama PIC',
            'Nomor Pengajuan',
            'Nilai Pengajuan',
            'Nilai Pencairan',
            'Testimonial',
            'Deleted at',
            'Created at',
            'Updated at',
        ];
    }

    public function map($testimonial): array
    {
        return [
            $testimonial->data_pic_kelompok_masyarakat->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat ?? '-',
            $testimonial->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat ?? '-',
            $testimonial->data_pic_kelompok_masyarakat->nama_pic ?? '-',
            $testimonial->pengajuan_kegiatan->nomor_pengajuan,
            number_format($testimonial->pengajuan_kegiatan->rab_pengajuan_paket_kegiatans->reduce(function ($carry, $rab) {
                return $carry + $rab->harga_unit * $rab->qty;
            }, 0)),
            number_format($testimonial->pengajuan_kegiatan->transaksi_penyaluran()->sum('nilai_penyaluran')),
            $testimonial->testimonial ?? '-',
            $testimonial->deleted_at ?? '-',
            $testimonial->created_at ?? '-',
            $testimonial->updated_at ?? '-',
        ];
    }
}
