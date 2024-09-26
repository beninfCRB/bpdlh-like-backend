<?php

namespace App\Exports;

use App\Models\DataPicKelompokMasyarakat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DataPicKelompokMasyarakatExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function collection()
    {
        return DataPicKelompokMasyarakat::with(
            'provinsi',
            'kabupaten',
            'kecamatan',
            'kelurahan',
            'kelompok_masyarakat',
            'user_akseslh',
        )->get();
    }

    public function map($data): array
    {
        $nomor_identitas_pic = '`' . $data->nomor_identitas_pic;
        $nohp_pic = '`' . $data->nohp_pic;
        return [
            $data->kelompok_masyarakat->kelompok_masyarakat,
            $data->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat,
            $data->nama_pic,
            $data->jenis_identitas_pic,
            $nomor_identitas_pic,
            $data->email_pic,
            $nohp_pic,
            $data->alamat_pic,
            $data->kelurahan->name ?? '-',
            $data->kecamatan->name ?? '-',
            $data->kabupaten->name ?? '-',
            $data->provinsi->name ?? '-',
            $data->user_akseslh->status_user,

        ];
    }

    public function headings(): array
    {
        return [
            'Kelompok Masyarakat',
            'Jenis Kelompok',
            'Nama PIC',
            'Jenis Identitas PIC',
            'Nomor Identitas PIC',
            'Email PIC',
            'No. Handphone PIC',
            'Alamat PIC',
            'Kelurahan PIC',
            'Kecamatan PIC',
            'Kabupaten PIC',
            'Provinsi PIC',
            'Status PIC'
        ];
    }
}
