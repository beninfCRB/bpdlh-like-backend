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
            'user_akseslh' => function ($q) {
                $q->withTrashed();
            },
            'user_akseslh.data_pic_kelompok_masyarakat' =>
            function ($q) {
                $q->withTrashed();
            },
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
            'paket_kegiatan.jenis_kegiatan' => function ($q) {
                $q->withTrashed();
            },
            'paket_kegiatan.master_sub_tematik_kegiatan.tematik_kegiatan',
            'paket_kegiatan.master_sub_tematik_kegiatan.sub_tematik_kegiatan' => function ($q) {
                $q->withTrashed();
            },
            'tahapan',
            'document'
        ])
            // ->whereBetween('created_at', [$this->data['tanggal_awal'], $this->data['tanggal_akhir']])
            ->whereDate('created_at', '>=', $this->data['tanggal_awal'])
            ->whereDate('created_at', '<=', $this->data['tanggal_akhir'])
            ->when($this->data['flag'], function ($query) {
                if ($this->data['flag'] == 1 || $this->data['flag'] == '1') {
                    # code...
                    $query->where(['flag' => '1', 'is_active' => 'INACTIVE']);
                } elseif ($this->data['flag'] == 20 || $this->data['flag'] == '20') {
                    $query->where(['flag' => '20']);
                } else {
                    $query->where('flag', $this->data['flag']);
                }
            },  function ($query) {
                $query->where('flag', '>', 0);
            })
            ->get();
    }

    public function map($pengajuanKegiatan): array
    {
        $total_sum = $pengajuanKegiatan->rab_pengajuan_paket_kegiatans->reduce(function ($carry, $rab) {
            return $carry + ($rab->harga_unit * $rab->qty);
        }, 0);

        $penyaluran = $pengajuanKegiatan->transaksi_penyaluran()->sum('nilai_penyaluran');

        $pengembalian = $pengajuanKegiatan->pengembalian()->sum('jumlah_pengembalian');
        $pengembalian = $pengembalian ? $pengembalian : 0;

        $nomor_identitas_pic = '`' . $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->nomor_identitas_pic;

        if ($pengajuanKegiatan->paket_kegiatan) {
            if ($pengajuanKegiatan->paket_kegiatan->jumlah_peserta) {
                # code...
                $jumlah = $pengajuanKegiatan->paket_kegiatan->jumlah_peserta < 50 ? $pengajuanKegiatan->paket_kegiatan->jumlah_peserta . ' Hectare' : $pengajuanKegiatan->paket_kegiatan->jumlah_peserta . ' Orang';
            } else {
                $jumlah = null;
            }
        } else {
            $jumlah = null;
        }

        return [
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->nama_pic ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->jenis_identitas_pic ?? null,
            $nomor_identitas_pic,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->kelurahan->name ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->kecamatan->name ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->kabupaten->name ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->provinsi->name ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->alamat_pic ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->email_pic ?? null,
            '`' . $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->nomor_identitas_pic ?? null,
            '`' . $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->nomor_npwp_pic ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->tempat_lahir ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->tanggal_lahir ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->agama->agama ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->jenis_kelamin ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->nama_gadis_ibu_kandung ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->status_perkawinan->status_pernikahan ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->jenis_pekerjaan->jenis_pekerjaan ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->pendidikan->pendidikan ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->nohp_pic ?? null,
            $pengajuanKegiatan->user_akseslh->status_user ?? null,
            $pengajuanKegiatan->user_akseslh->role_user ?? null,
            $pengajuanKegiatan->user_akseslh->data_pic_kelompok_masyarakat->nomor_kontak_darurat ?? null,
            $pengajuanKegiatan->nomor_pengajuan ?? null,
            $pengajuanKegiatan->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan ?? null,
            $pengajuanKegiatan->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan ?? null,
            $pengajuanKegiatan->paket_kegiatan->jenis_kegiatan->jenis_kegiatan ?? null,
            $pengajuanKegiatan->kelurahan->name ?? null,
            $pengajuanKegiatan->kecamatan->name ?? null,
            $pengajuanKegiatan->kabupaten->name ?? null,
            $pengajuanKegiatan->provinsi->name ?? null,
            $jumlah,
            $pengajuanKegiatan->tanggal_mulai_kegiatan ?? null,
            $pengajuanKegiatan->tanggal_akhir_kegiatan ?? null,
            $pengajuanKegiatan->time_mulai_kegiatan ?? null,
            $pengajuanKegiatan->time_akhir_kegiatan ?? null,
            $pengajuanKegiatan->judul_pengajuan_kegiatan ?? null,
            $pengajuanKegiatan->alamat_kegiatan ?? null,
            $pengajuanKegiatan->proposal_kegiatan ?? null,
            $pengajuanKegiatan->ruang_lingkup_kegiatan ?? null,
            number_format($total_sum),
            number_format($penyaluran),
            number_format($pengembalian),
            number_format($penyaluran - $pengembalian),
            $pengajuanKegiatan->flag == 20 ? 'Ditolak' : $pengajuanKegiatan->tahapan->deskripsi_kegiatan,
            $pengajuanKegiatan->created_at,
            $pengajuanKegiatan->updated_at,
            $pengajuanKegiatan->document()->where('group', 'document_sk')->first() ? env('APP_URL') . '/storage/' . $pengajuanKegiatan->document()->where('group', 'document_sk')->first()->file_path : null
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
            'Jenis Kelamin',
            'Nama Gadis Ibu Kandung',
            'Status Perkawinan',
            'Jenis Pekerjaan',
            'Pendidikan Terakhir',
            'No. HP PIC',
            'Status PIC',
            'Role PIC',
            'NO Kontak Darurat',
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
            'Total Dana Dicairkan',
            'Dana Dikembalikan',
            'Realisasi RAB',
            'Tahapan',
            'Created At',
            'Updated At',
            'Link File SK'
        ];
    }
}
