<?php

namespace App\Exports;

use App\Models\PengajuanKegiatan;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class PengajuanKegiatanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        // Filter data berdasarkan rentang tanggal
        return PengajuanKegiatan::with([
            'provinsi',
            'kabupaten',
            'kecamatan',
            'kelurahan',
            'rab_pengajuan_paket_kegiatans',
            'user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat.jenis'
            => function ($q) {
                $q->withTrashed();
            },
            'user_akseslh.data_pic_kelompok_masyarakat.provinsi',
            'user_akseslh.data_pic_kelompok_masyarakat.kabupaten',
            'user_akseslh.data_pic_kelompok_masyarakat.kecamatan',
            'user_akseslh.data_pic_kelompok_masyarakat.kelurahan',
            // 'user_akseslh.data_pic_kelompok_masyarakat.agama',
            // 'user_akseslh.data_pic_kelompok_masyarakat.status_perkawinan',
            // 'user_akseslh.data_pic_kelompok_masyarakat.jenis_pekerjaan',
            // 'user_akseslh.data_pic_kelompok_masyarakat.pendidikan',
            'paket_kegiatan.jenis_kegiatan',
            'paket_kegiatan.master_sub_tematik_kegiatan.tematik_kegiatan',
            'paket_kegiatan.master_sub_tematik_kegiatan.sub_tematik_kegiatan' => function ($q) {
                $q->withTrashed();
            }
        ])
            ->whereBetween('created_at', [$this->data['tanggal_awal'], $this->data['tanggal_akhir']])
            ->get();
    }

    public function map($pengajuanKegiatan): array
    {
        $total_sum = number_format($pengajuanKegiatan->rab_pengajuan_paket_kegiatans->reduce(function ($carry, $rab) {
            return $carry + ($rab->harga_unit * $rab->qty);
        }, 0));

        $nomor_identitas_pic = '`' . $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->nomor_identitas_pic;

        $jumlah = $pengajuanKegiatan->paket_kegiatan->jumlah_peserta < 50 ? $pengajuanKegiatan->paket_kegiatan->jumlah_peserta . ' Hectare' : $pengajuanKegiatan->paket_kegiatan->jumlah_peserta . ' Orang';
        return [
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->nama_pic,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->jenis_identitas_pic,
            $nomor_identitas_pic,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->kelurahan->name ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->kecamatan->name ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->kabupaten->name ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->provinsi->name ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->alamat_pic,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->email_pic,
            '`' . $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->nomor_identitas_pic,
            '`' . $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->nomor_npwp_pic,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->tempat_lahir,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->tanggal_lahir,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->agama->agama ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->status_perkawinan->status_pernikahan ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->jenis_pekerjaan->jenis_pekerjaan ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->pendidikan->pendidikan ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->nohp_pic,
            $pengajuanKegiatan->user_akseslh->status_user,
            $pengajuanKegiatan->user_akseslh->role_user,
            $pengajuanKegiatan->nomor_pengajuan,
            $pengajuanKegiatan->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan,
            $pengajuanKegiatan->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan,
            $pengajuanKegiatan->paket_kegiatan->jenis_kegiatan->jenis_kegiatan,
            $pengajuanKegiatan->kelurahan->name ?? null,
            $pengajuanKegiatan->kecamatan->name ?? null,
            $pengajuanKegiatan->kabupaten->name ?? null,
            $pengajuanKegiatan->provinsi->name ?? null,
            $jumlah,
            $pengajuanKegiatan->tanggal_mulai_kegiatan,
            $pengajuanKegiatan->tanggal_akhir_kegiatan,
            $pengajuanKegiatan->time_mulai_kegiatan,
            $pengajuanKegiatan->time_akhir_kegiatan,
            $pengajuanKegiatan->judul_pengajuan_kegiatan,
            $pengajuanKegiatan->alamat_kegiatan,
            $pengajuanKegiatan->proposal_kegiatan,
            $pengajuanKegiatan->ruang_lingkup_kegiatan,
            $total_sum,
            $pengajuanKegiatan->created_at,
            $pengajuanKegiatan->updated_at
        ];
    }

    public function headings(): array
    {
        return [
            'Jenis Kelompok Masyarakat',
            'Kelompok Masyarakat',
            'Nama PIC',
            'Jenis Identitas PIC',
            'Nomor Identitas',
            'Kelurahan PIC',
            'Kecamatan PIC',
            'Kabupaten PIC',
            'Provinsi PIC',
            'Alamat PIC',
            'Email PIC',
            'No. Identitas PIC',
            'NPWP PIC',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Status Perkawinan',
            'Jenis Pekerjaan',
            'Pendidikan Terakhir',
            'No. HP PIC',
            'Status PIC',
            'Role PIC',
            'Nomor Pengajuan',
            'Tematik Kegiatan',
            'Sub Tematik Kegiatan',
            'Jenis Kegiatan',
            'Kelurahan Kegiatan',
            'Kecamatan Kegiatan',
            'Kabupaten Kegiatan',
            'Provinsi Kegiatan',
            'Jumlah',
            'Tanggal Mulai Kegiatan',
            'Tanggal Akhir Kegiatan',
            'Waktu Mulai Kegiatan',
            'Waktu Akhir Kegiatan',
            'Judul Pengajuan Kegiatan',
            'Alamat Kegiatan',
            'Proposal Kegiatan',
            'Ruang Lingkup Kegiatan',
            'Total RAB',
            'Created At',
            'Updated At'
        ];
    }
}
