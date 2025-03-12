<?php


namespace App\Services\Akseslh;

use App\Models\DetailLogTahapanPengajuanKegiatan;
use App\Services\AppService;
use App\Services\PdfService;
use App\Models\File as FileTable;
use App\Models\LogJadwalPembukaan;
use App\Models\LogRabPengajuanPaketKegiatan;
use App\Models\PengajuanKegiatan;
use App\Services\FileUploadService;
use App\Services\AppServiceInterface;
use App\Models\TahapanPengajuanKegiatan;
use Yajra\DataTables\Facades\DataTables;
use App\Models\RabPengajuanPaketKegiatan;
use App\Models\LogTahapanPengajuanKegiatan;
use App\Models\TransaksiPenyaluran;
use App\Models\UserAkseslh;
use App\Services\EmailPhpService;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class PengajuanKegiatanService extends AppService implements AppServiceInterface
{
    protected $modelTahapanPengajuanKegiatan;
    protected $modelLogTahapanPengajuanKegiatan;
    protected $modelRabPengajuanPaketKegiatan;
    protected $modelTransaksiPenyaluran;
    protected $fileUploadService;
    protected $fileTable;
    protected $pdfService, $emailPhpService;
    protected $modelDetailLogTahapanPengajuanKegiatan;
    protected $modelLogRabPengajuanKegiatan;
    protected $modelLogJadwalPembukaan;

    public function __construct(
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        PengajuanKegiatan $model,
        TahapanPengajuanKegiatan $modelTahapanPengajuanKegiatan,
        LogTahapanPengajuanKegiatan $modelLogTahapanPengajuanKegiatan,
        RabPengajuanPaketKegiatan $modelRabPengajuanPaketKegiatan,
        TransaksiPenyaluran $modelTransaksiPenyaluran,
        PdfService $pdfService,
        EmailPhpService $emailPhpService,
        DetailLogTahapanPengajuanKegiatan $modelDetailLogTahapanPengajuanKegiatan,
        LogRabPengajuanPaketKegiatan $modelLogRabPengajuanKegiatan,
        LogJadwalPembukaan $modelLogJadwalPembukaan
    ) {
        parent::__construct($model);
        $this->modelTahapanPengajuanKegiatan            = $modelTahapanPengajuanKegiatan;
        $this->modelLogTahapanPengajuanKegiatan         = $modelLogTahapanPengajuanKegiatan;
        $this->modelRabPengajuanPaketKegiatan           = $modelRabPengajuanPaketKegiatan;
        $this->modelTransaksiPenyaluran                 = $modelTransaksiPenyaluran;
        $this->fileUploadService                        = $fileUploadService;
        $this->fileTable                                = $fileTable;
        $this->pdfService                               = $pdfService;
        $this->emailPhpService                          = $emailPhpService;
        $this->modelDetailLogTahapanPengajuanKegiatan   = $modelDetailLogTahapanPengajuanKegiatan;
        $this->modelLogRabPengajuanKegiatan             = $modelLogRabPengajuanKegiatan;
        $this->modelLogJadwalPembukaan                  = $modelLogJadwalPembukaan;
    }

    public function getAll()
    {
        $model = $this->model->query()->with([
            'provinsi:name,id',
            'kabupaten:name,id',
            'kecamatan:name,id',
            'kelurahan:name,id',
            'rab_pengajuan_paket_kegiatans:harga_unit,qty,pengajuan_kegiatan_id',
            'user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat.jenis',
            'user_akseslh.data_pic_kelompok_masyarakat.provinsi',
            'user_akseslh.data_pic_kelompok_masyarakat.kabupaten',
            'user_akseslh.data_pic_kelompok_masyarakat.kecamatan',
            'user_akseslh.data_pic_kelompok_masyarakat.kelurahan',
            'paket_kegiatan.jenis_kegiatan:jenis_kegiatan,id',
            'paket_kegiatan.master_sub_tematik_kegiatan.tematik_kegiatan:tematik_kegiatan,id',
            'paket_kegiatan.master_sub_tematik_kegiatan.sub_tematik_kegiatan:sub_tematik_kegiatan,id',
            'transaksi_penyaluran',
            'tahapan'
        ])->withTrashed()->orderBy('created_at', 'DESC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function getAllAttr()
    {
        $result  = $this->model->newQuery()
            ->orderBy('created_at', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'                        => $items->id,
                'judul_pengajuan_kegiatan'  => $items->judul_pengajuan_kegiatan,
                'alamat_kegiatan'           => $items->alamat_kegiatan,
                'tanggal_mulai_kegiatan'    => $items->tanggal_mulai_kegiatan,
                'tanggal_akhir_kegiatan'    => $items->tanggal_akhir_kegiatan,
                'time_mulai_kegiatan'       => $items->time_mulai_kegiatan,
                'time_akhir_kegiatan'       => $items->time_akhir_kegiatan,
                'proposal_kegiatan'         => $items->proposal_kegiatan,
                'tujuan_kegiatan'           => $items->tujuan_kegiatan,
                'ruang_lingkup_kegiatan'    => $items->ruang_lingkup_kegiatan,
            ];
        });

        return $this->sendSuccess($result);
    }

    public function getDataPenyerapanDana()
    {
        $sum_rab = $this->modelRabPengajuanPaketKegiatan->newQuery()->whereHas('pengajuan_kegiatan', function ($query) {
            $query->whereBetween('flag', [1, 9]);
        })->sum(\DB::raw('qty * harga_unit'));

        $sum_transaksi_penyaluran = $this->modelTransaksiPenyaluran->newQuery()->sum(\DB::raw('nilai_penyaluran'));

        $result = [
            'total_pendanaan'           => (int) $sum_rab,
            'total_dana_tersalurkan'    => (int) $sum_transaksi_penyaluran
        ];

        return $this->sendSuccess($result);
    }

    public function apiGetAll()
    {
        $result = $this->model->newQuery()->get();

        return $this->sendSuccess($result);
    }

    public function getDataProsesKegiatan($user_akseslh_id)
    {
        $result =   $this->model->newQuery()
            ->with(['paket_kegiatan.master_sub_tematik_kegiatan.sub_tematik_kegiatan' => function ($query) {
                $query->withTrashed(); // Mengambil data yang sudah dihapus soft delete
            }])
            ->where(['user_akseslh_id' => $user_akseslh_id])
            ->where('flag', '>', 0)
            ->where('flag', '<', 10)
            ->whereHas('rab_pengajuan_paket_kegiatans')
            ->latest()
            ->first();

        $data = [];

        if (!$result) return $this->sendSuccess(collect($data));

        if ($result) {
            $total = 0;
            $total_penyaluran = 0;

            if (isset($result->transaksi_penyaluran)) {
                # code...
                foreach ($result->transaksi_penyaluran as $item) {
                    # code...
                    $total_penyaluran += $item->nilai_penyaluran;
                }
            }

            foreach ($result->rab_pengajuan_paket_kegiatans as $items) {
                # code...
                $total += ($items->qty * $items->harga_unit);
            }

            # code...
            $data[] = [
                'id'                        => $result->id,
                'nomor_pengajuan'           => $result->nomor_pengajuan,
                'tematik_kegiatan'          => $result->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan,
                'sub_tematik_kegiatan'      => $result->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan,
                'jenis_kegiatan'            => $result->paket_kegiatan->jenis_kegiatan->jenis_kegiatan,
                'jumlah'                    => $result->paket_kegiatan->jumlah_peserta . " " . ($result->paket_kegiatan->jumlah_peserta >= 50 ? "Orang" : "Hectare"),
                'lokasi'                    => $result->alamat_kegiatan ?? 'Alamat',
                'tahapan_pengajuan'         => $result->flag,
                'persentase_pengajuan'      => $this->checkAngkaPengajuan($result->flag, $result->log_tahapan_pengajuan),
                'dana_yang_disetujui'       => $result->flag >= 3 ? $total : 0,
                'dana_yang_dicairkan'       => $total_penyaluran,
                'sisa_pencairan'            => $result->flag >= 3 ? ($total - $total_penyaluran) : 0,
                'tanggal_kegiatan'          => $result->tanggal_mulai_kegiatan,
            ];
        }

        return $this->sendSuccess($data);
    }

    public function getLogKegiatan($id)
    {
        $model = $this->model->newQuery()->with(['user_akseslh' => function ($q) {
            $q->withTrashed();
        }, 'user_akseslh.data_pic_kelompok_masyarakat' => function ($q) {
            $q->withTrashed();
        }, 'log_tahapan_pengajuan.tahapan_pengajuan_kegiatan'])->find($id);

        if (!$model) return $this->sendError(null, 'Not found', 422);

        $total = 0;
        $total_penyaluran = 0;

        if (isset($model->transaksi_penyaluran)) {
            # code...
            foreach ($model->transaksi_penyaluran as $item) {
                # code...
                $total_penyaluran += $item->nilai_penyaluran;
            }
        }

        foreach ($model->rab_pengajuan_paket_kegiatans as $i) {
            # code...
            $total += ($i->qty * $i->harga_unit);
        }

        $laporan_kegiatan_termin_1 = $model->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
            $q->where(['deskripsi_kegiatan' => 'Laporan Kegiatan Termin 1']);
        })->first()->document_file ?? null;

        // Data Verifikator
        $verifikasi = $model->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
            $q->where(['deskripsi_kegiatan' => 'Verifikasi']);
        })->first();

        $nama_verifikator       = $verifikasi->user_akseslh_admin->email ?? null;
        $tanggal_verifikasi     = $verifikasi->tanggal_selesai ?? null;
        $catatan_verifikator    = $verifikasi->catatan_log_tahapan_pengajuan_kegiatan()->first() ? $verifikasi->catatan_log_tahapan_pengajuan_kegiatan()->first()->catatan_log : null;

        // Data Validator
        $validator = $model->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
            $q->where(['deskripsi_kegiatan' => 'Validasi']);
        })->first();

        $nama_validator     = $validator->user_akseslh_admin->email ?? null;
        $tanggal_validasi   = $validator->tanggal_selesai ?? null;
        $catatan_validator  = $validator->catatan_log_tahapan_pengajuan_kegiatan()->first() ? $validator->catatan_log_tahapan_pengajuan_kegiatan()->first()->catatan_log : null;

        // Data Verifikator laporan termin 1
        $verifikator_laporan_tahap_1 = $model->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
            $q->where(['deskripsi_kegiatan' => 'Verifikasi Laporan Kegiatan Termin 1']);
        })->first();

        $nama_verifikator_laporan_tahap_1       = $verifikator_laporan_tahap_1->user_akseslh_admin->email ?? null;
        $tanggal_verifikator_laporan_tahap_1    = $verifikator_laporan_tahap_1->tanggal_selesai ?? null;
        $catatan_verifikator_laporan_tahap_1    = $verifikator_laporan_tahap_1->catatan_log_tahapan_pengajuan_kegiatan()->first() ? $verifikator_laporan_tahap_1->catatan_log_tahapan_pengajuan_kegiatan()->first()->catatan_log : null;

        // Data Master Bank Penyaluran Pertama
        $transaksi_penyaluran   = $model->transaksi_penyaluran()->latest()->first();
        $master_data_bank       = $transaksi_penyaluran->master_data_bank->nama_bank ?? null;
        $nomor_rekening         = $transaksi_penyaluran->nomor_rekening ?? null;
        $nama_pemilik_rekening  = $transaksi_penyaluran->nama_pemilik_rekening ?? null;
        $tanggal_penyaluran     = $transaksi_penyaluran->tanggal_penyaluran ?? null;
        $nilai_penyaluran       = $transaksi_penyaluran->nilai_penyaluran ?? null;

        // Data Master Bank Penyaluran Kedua
        $transaksi_penyaluran_2   = $model->transaksi_penyaluran()->first();
        $master_data_bank_2       = $transaksi_penyaluran_2->master_data_bank->nama_bank ?? null;
        $nomor_rekening_2         = $transaksi_penyaluran_2->nomor_rekening ?? null;
        $nama_pemilik_rekening_2  = $transaksi_penyaluran_2->nama_pemilik_rekening ?? null;
        $tanggal_penyaluran_2     = $transaksi_penyaluran_2->tanggal_penyaluran ?? null;
        $nilai_penyaluran_2       = $transaksi_penyaluran_2->nilai_penyaluran ?? null;

        // Model Dokumen
        $files              = $model->document;
        $file_lampiran      = $files->where('group', 'document')->first();
        $file_sk            = $files->where('group', 'document_sk')->first();
        $file_perjanjian    = $files->where('group', 'perjanjian_kerjasama')->first();

        // Indikator Laporan Kegiatan
        $indikator_laporan_kegiatan = $model->indikator_laporan_kegiatan->transform(function ($items, $key) {
            return [
                'nilai_laporan' => $items->nilai_laporan,
                'nama_indikator' => $items->master_data_indikator_laporan->nama_indikator,
                'satuan' => $items->master_data_indikator_laporan->satuan,
                'tipe_data' => $items->master_data_indikator_laporan->satuan,
            ];
        });

        $laporan_akhir  = $model->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
            $q->where(['deskripsi_kegiatan' => 'Laporan Akhir Kegiatan']);
        })->first();

        $prop = [
            'total' => $total,
            'total_penyaluran'  => $total_penyaluran,
            'nama_verifikator'  => $nama_verifikator,
            'tanggal_verifikasi'    => $tanggal_verifikasi,
            'catatan_verifikator'   => $catatan_verifikator,
            'nama_validator'        => $nama_validator,
            'tanggal_validasi'      => $tanggal_validasi,
            'catatan_validator'     => $catatan_validator,
            'master_data_bank'      => $master_data_bank,
            'nomor_rekening'        => $nomor_rekening,
            'nama_pemilik_rekening' => $nama_pemilik_rekening,
            'tanggal_penyaluran'    => $tanggal_penyaluran,
            'nilai_penyaluran'      => $nilai_penyaluran,
            'file_lampiran'         => $file_lampiran,
            'file_sk'               => $file_sk,
            'file_perjanjian'       => $file_perjanjian,
            'laporan_kegiatan_termin_1' => $laporan_kegiatan_termin_1,
            'master_data_bank_2'      => $master_data_bank_2,
            'nomor_rekening_2'        => $nomor_rekening_2,
            'nama_pemilik_rekening_2' => $nama_pemilik_rekening_2,
            'tanggal_penyaluran_2'    => $tanggal_penyaluran_2,
            'nilai_penyaluran_2'      => $nilai_penyaluran_2,
            'indikator_laporan_kegiatan' => $indikator_laporan_kegiatan,
            'nama_verifikator_laporan_tahap_1'  => $nama_verifikator_laporan_tahap_1,
            'tanggal_verifikator_laporan_tahap_1'    => $tanggal_verifikator_laporan_tahap_1,
            'catatan_verifikator_laporan_tahap_1'   => $catatan_verifikator_laporan_tahap_1,
            'laporan_akhir' => $laporan_akhir ? $laporan_akhir->document_file : null,
            'nomor_sptjm' => $model->nomor_sptjm,
        ];

        $prop = json_decode(json_encode($prop));

        $result = $model->log_tahapan_pengajuan()
            ->with(['pengajuan_kegiatan.user_akseslh' => function ($q) {
                $q->withTrashed();
            }, 'pengajuan_kegiatan.user_akseslh.data_pic_kelompok_masyarakat' => function ($q) {
                $q->withTrashed();
            }, 'pengajuan_kegiatan.paket_kegiatan.master_sub_tematik_kegiatan.sub_tematik_kegiatan' => function ($query) {
                $query->withTrashed(); // Mengambil data yang sudah dihapus soft delete
            }])
            ->get();

        $result->transform(function ($items, $key) use ($prop) {

            $detail = null;
            switch ($items->tahapan_pengajuan_kegiatan->sort) {
                case 3:
                    # code...
                    $detail = [
                        'kelompok_masyarakat'       => $items->pengajuan_kegiatan->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat,
                        'tematik_kegiatan'          => $items->pengajuan_kegiatan->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan,
                        'sub_tematik_kegiatan'      => $items->pengajuan_kegiatan->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan,
                        'judul_pengajuan_kegiatan'  => $items->pengajuan_kegiatan->judul_pengajuan_kegiatan,
                        'kegiatan'                  => $items->pengajuan_kegiatan->paket_kegiatan->jenis_kegiatan->jenis_kegiatan . " " . $items->pengajuan_kegiatan->paket_kegiatan->jumlah_peserta . " " . ($items->pengajuan_kegiatan->paket_kegiatan->jumlah_peserta > 50 ? "Orang" : "Hektare"),
                        'jenis_kegiatan'            => $items->pengajuan_kegiatan->paket_kegiatan->jenis_kegiatan->jenis_kegiatan,
                        'rencana_kegiatan'          => $items->pengajuan_kegiatan->tanggal_mulai_kegiatan,
                        'jumlah'                    => $items->pengajuan_kegiatan->paket_kegiatan->jumlah_peserta . " " . ($items->pengajuan_kegiatan->paket_kegiatan->jumlah_peserta >= 50 ? "Orang" : "Hectare"),
                        'tanggal_pengajuan'         => $items->pengajuan_kegiatan->created_at->format('d M Y H:i'),
                        'tanggal_akhir_validasi'    => Carbon::parse($items->pengajuan_kegiatan->created_at)->locale('id')->addDays(7)->format('d M Y'),
                        'kelompok_masyarakat'       => $items->pengajuan_kegiatan->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat,
                        'nama_pic'                  => $items->pengajuan_kegiatan->user_akseslh->data_pic_kelompok_masyarakat->nama_pic,
                        'email_pic'                 => $items->pengajuan_kegiatan->user_akseslh->data_pic_kelompok_masyarakat->email_pic,
                        'lokasi'                    => $items->pengajuan_kegiatan->alamat_kegiatan,
                        'nomor_pengajuan'           => $items->pengajuan_kegiatan->nomor_pengajuan,
                        'proposal_kegiatan'         => $items->pengajuan_kegiatan->proposal_kegiatan,
                        'tujuan_kegiatan'           => $items->pengajuan_kegiatan->tujuan_kegiatan,
                        'ruang_lingkup_kegiatan'    => $items->pengajuan_kegiatan->ruang_lingkup_kegiatan,
                        'dana_yang_disetujui'       => $prop->total,
                        'dana_yang_dicairkan'       => $prop->total_penyaluran,
                        'sisa_pencairan'            => ($prop->total - $prop->total_penyaluran),
                        'nama_verifikator'          => $prop->nama_verifikator,
                        'tanggal_verifikasi'        => $prop->tanggal_verifikasi,
                        'catatan_verifikator'       => $prop->catatan_verifikator,
                        'nama_validator'            => $prop->nama_validator,
                        'tanggal_validasi'          => $prop->tanggal_validasi,
                        'catatan_validator'         => $prop->catatan_validator,
                        'lampiran'                  => $prop->file_lampiran,
                        'sk'                        => $prop->file_sk,
                    ];
                    break;
                case 4:
                    $detail = [
                        'nomor_sptjm'    => $prop->nomor_sptjm,
                    ];
                    break;

                case 5:
                    $detail = [
                        'master_data_bank'          => $prop->master_data_bank,
                        'nomor_rekening'            => $prop->nomor_rekening,
                        'nama_pemilik_rekening'     => $prop->nama_pemilik_rekening,
                        'tanggal_penyaluran'        => $prop->tanggal_penyaluran,
                        'nilai_penyaluran'          => $prop->nilai_penyaluran,
                        'dana_yang_disetujui'       => $prop->total,
                        'dana_yang_dicairkan'       => $prop->total_penyaluran,
                    ];
                    break;
                case 7:
                    $detail = [
                        'catatan'                       => $prop->catatan_verifikator_laporan_tahap_1,
                        'tanggal_realisasi_kegiatan'    => $items->pengajuan_kegiatan->tanggal_mulai_kegiatan,
                        'indikator_laporan_kegiatan' => $prop->indikator_laporan_kegiatan,
                        'laporan_kegiatan_termin_1' => $prop->laporan_kegiatan_termin_1,
                    ];
                    break;

                case 8:
                    $detail = [
                        'master_data_bank'          => $prop->master_data_bank_2,
                        'nomor_rekening'            => $prop->nomor_rekening_2,
                        'nama_pemilik_rekening'     => $prop->nama_pemilik_rekening_2,
                        'tanggal_penyaluran'        => $prop->tanggal_penyaluran_2,
                        'nilai_penyaluran'          => $prop->nilai_penyaluran_2,
                        'dana_yang_disetujui'       => $prop->total,
                        'dana_yang_dicairkan'       => $prop->total_penyaluran,
                    ];
                    break;
                case 9:
                    $detail = [
                        'pengembalian_dana' => $items->pengajuan_kegiatan->pengembalian->jumlah_pengembalian ?? null,
                    ];
                    break;

                case 10:
                    $detail = [
                        'laporan_akhir' => $prop->laporan_akhir
                    ];
                    break;

                default:
                    # code...
                    $detail = null;
                    break;
            }

            return [
                'id'                => $items->id,
                'tahapan_kegiatan'  => $items->tahapan_pengajuan_kegiatan->deskripsi_kegiatan,
                'sort'              => $items->tahapan_pengajuan_kegiatan->sort,
                'tanggal_masuk'     => $items->tanggal_masuk,
                'tanggal_selesai'   => $items->tanggal_selesai,
                'user_akseslh'      => $items->user_akseslh_admin->email ?? null,
                'created_at'        => $items->created_at->format('Y-m-d H:i:s'),
                'updated_at'        => $items->updated_at->format('Y-m-d H:i:s'),
                'detail'            => $detail
            ];
        });

        return $this->sendSuccess(array_slice($result->sortBy('sort')->values()->all(), 2));
    }

    public function getDataRiwayatPengajuan($user_akseslh_id)
    {
        $result =   $this->model->newQuery()->with(['log_tahapan_pengajuan', 'paket_kegiatan.master_sub_tematik_kegiatan.sub_tematik_kegiatan' => function ($query) {
            $query->withTrashed(); // Mengambil data yang sudah dihapus soft delete
        }])->where(['user_akseslh_id' => $user_akseslh_id])->orderBy('created_at', 'DESC')->get();

        if (!$result)  return $this->sendSuccess(null);

        $result->transform(function ($items, $key) {

            $total = 0;
            $total_penyaluran = 0;

            if (isset($items->transaksi_penyaluran)) {
                # code...
                foreach ($items->transaksi_penyaluran as $item) {
                    # code...
                    $total_penyaluran += $item->nilai_penyaluran;
                }
            }

            foreach ($items->rab_pengajuan_paket_kegiatans as $i) {
                # code...
                $total += ($i->qty * $i->harga_unit);
            }

            return [
                'id'                        => $items->id,
                'nomor_pengajuan'           => $items->nomor_pengajuan,
                'tematik_kegiatan'          => $items->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan,
                'sub_tematik_kegiatan'      => $items->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan,
                'jenis_kegiatan'            => $items->paket_kegiatan->jenis_kegiatan->jenis_kegiatan,
                'jumlah'                    => $items->paket_kegiatan->jumlah_peserta . " " . ($items->paket_kegiatan->jumlah_peserta >= 50 ? "Orang" : "Hectare"),
                'lokasi'                    => $items->alamat_kegiatan ?? 'Alamat',
                'tahapan_pengajuan'         => $items->flag,
                'persentase_pengajuan'      => $this->checkAngkaPengajuan($items->flag, $items->log_tahapan_pengajuan),
                'dana_yang_disetujui'       => $items->flag >= 3 ? $total : 0,
                'dana_yang_dicairkan'       => $total_penyaluran,
                'sisa_pencairan'            => $items->flag >= 3 ? ($total - $total_penyaluran) : 0,
                'tanggal_kegiatan'          => $items->tanggal_mulai_kegiatan,
            ];
        });

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $page = null, $perPage = null, $lang = null)
    {
        $result =   $this->switchLang($search, $page, $perPage, $lang);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result =   $this->model->newQuery()->with(['user_akseslh' => function ($q) {
            $q->withTrashed();
        }, 'user_akseslh.data_pic_kelompok_masyarakat' => function ($q) {
            $q->withTrashed();
        }])->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {
            $cekData = PengajuanKegiatan::where(['user_akseslh_id' => $data['user_akseslh_id']])->latest()->first();

            if ($cekData) {
                # code...
                if ($cekData->flag < 10) {
                    # code...
                    \Sentry\captureMessage('Validate Message: ' . $cekData->email_pic . ' masih ada pengajuan', \Sentry\Severity::warning());
                    return $this->sendError(null, 'Masih ada pengajuan yang berlangsung', 422);
                }

                // if ($cekData->rab_pengajuan_paket_kegiatans->count() <= 0 || $cekData->flag == 0) {
                //     # code...
                //     $cekData->forceDelete();
                // }
            }

            // Menghasilkan nomor pengajuan otomatis
            $data['nomor_pengajuan'] = PengajuanKegiatan::generateNomorPengajuan($data['paket_kegiatan_id'], $data['user']);

            $newData = $this->model->newQuery()->create([
                'nomor_pengajuan'               => $data['nomor_pengajuan'],
                'paket_kegiatan_id'             => $data['paket_kegiatan_id'],
                'user_akseslh_id'               => $data['user_akseslh_id'],
                'judul_pengajuan_kegiatan'      => $data['judul_pengajuan_kegiatan'] ?? null,
                'provinsi_kegiatan'             => $data['provinsi_kegiatan'] ?? null,
                'kabupaten_kegiatan'            => $data['kabupaten_kegiatan'] ?? null,
                'kecamatan_kegiatan'            => $data['kecamatan_kegiatan'] ?? null,
                'kelurahan_kegiatan'            => $data['kelurahan_kegiatan'] ?? null,
                'alamat_kegiatan'               => $data['alamat_kegiatan'] ?? null,
                'proposal_kegiatan'             => $data['proposal_kegiatan'] ?? null,
                'tujuan_kegiatan'               => $data['tujuan_kegiatan'] ?? null,
                'ruang_lingkup_kegiatan'        => $data['ruang_lingkup_kegiatan'] ?? null,
                'tanggal_mulai_kegiatan'        => isset($data['tanggal_mulai_kegiatan']) ? date_create($data['tanggal_mulai_kegiatan']) : Carbon::now()->format('Y-m-d'),
                'tanggal_akhir_kegiatan'        => isset($data['tanggal_akhir_kegiatan']) ? date_create($data['tanggal_akhir_kegiatan']) : Carbon::now()->format('Y-m-d'),
                'time_mulai_kegiatan'           => isset($data['time_mulai_kegiatan']) ? $data['time_mulai_kegiatan'] : '08:00',
                'time_akhir_kegiatan'           => isset($data['time_akhir_kegiatan']) ? $data['time_akhir_kegiatan'] : '16:00',
                'lokasi_bidang_folu_id'         => $data['lokasi_bidang_folu_id'] ?? null,
                'flag'                          => 0
            ]);

            $dataSend = array('nomor_pengajuan' => $data['nomor_pengajuan']);

            if (isset($data['fileDocument'])) {
                // Save document
                // upload document
                $upload = $this->fileUploadService->handleFile($data['fileDocument'])->saveToDb('document');

                if (!empty($upload)) {
                    $image = $this->fileTable->newQuery()->find($upload->id);
                    $image->update([
                        'fileable_type' => get_class($newData),
                        'fileable_id'   => $newData->id,
                    ]);
                }
            }

            // $dataSend = $this->getDataRab($newData->id, true);
            foreach ($newData->paket_kegiatan->standar_rab_paket_kegiatan as $item) {
                # code...
                $rab[] = [
                    'id_komponen'           => $item->master_komponen_rab_id,
                    'jenis_komponen_rab'    => $item->master_komponen_rab->jenis_komponen->jenis_komponen_rab,
                    'komponen_rab'          => $item->master_komponen_rab->komponen_rab,
                    'satuan'                => $item->master_komponen_rab->satuan->satuan,
                    'harga_unit'            => $item->standar_harga_unit,
                    'nilai_standar'         => $item->standar_harga_unit,
                    'qty'                   => $item->standar_qty,

                ];
            }

            $dataSend = [
                'id_pengajuan'      => $newData->id,
                'nomor_pengajuan'   => $data['nomor_pengajuan'],
                'komponen_rab'      => collect($rab)->groupBy('jenis_komponen_rab'),
            ];


            // Save the PDF to the storage folder
            // Dicomment dulu,
            // $pdf = $this->pdfService->generateAndSavePdf('pdf.template-small-grant', get_class($newData), $newData, $data['nomor_pengajuan']);

            \DB::commit(); // commit the changes
            return $this->sendSuccess($dataSend);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function update($id, $data)
    {
        $read   =   $this->model->newQuery()->where(['nomor_pengajuan' => $id, 'user_akseslh_id' => $data['user_akseslh_id']])->first();

        if (!$read) return $this->sendError(null, 'Not Found', 422);

        if ($read->flag > 0) {
            # code...
            return $this->sendError(null, 'Data bukan draft', 422);
        }

        \DB::beginTransaction();

        try {
            $read->paket_kegiatan_id         = $data['paket_kegiatan_id'];
            $read->user_akseslh_id           = $data['user_akseslh_id'];
            $read->judul_pengajuan_kegiatan  = $data['judul_pengajuan_kegiatan'] ?? null;
            $read->provinsi_kegiatan         = $data['provinsi_kegiatan'] ?? null;
            $read->kabupaten_kegiatan        = $data['kabupaten_kegiatan'] ?? null;
            $read->kecamatan_kegiatan        = $data['kecamatan_kegiatan'] ?? null;
            $read->kelurahan_kegiatan        = $data['kelurahan_kegiatan'] ?? null;
            $read->alamat_kegiatan           = $data['alamat_kegiatan'] ?? null;
            $read->proposal_kegiatan         = $data['proposal_kegiatan'] ?? null;
            $read->tujuan_kegiatan           = $data['tujuan_kegiatan'] ?? null;
            $read->ruang_lingkup_kegiatan    = $data['ruang_lingkup_kegiatan'] ?? null;
            $read->tanggal_mulai_kegiatan    = isset($data['tanggal_mulai_kegiatan']) ? date_create($data['tanggal_mulai_kegiatan']) : Carbon::now()->format('Y-m-d');
            $read->tanggal_akhir_kegiatan    = isset($data['tanggal_akhir_kegiatan']) ? date_create($data['tanggal_akhir_kegiatan']) : Carbon::now()->format('Y-m-d');
            $read->time_mulai_kegiatan       = isset($data['time_mulai_kegiatan']) ? $data['time_mulai_kegiatan'] : '08:00';
            $read->time_akhir_kegiatan       = isset($data['time_akhir_kegiatan']) ? $data['time_akhir_kegiatan'] : '16:00';
            $read->save();

            if (isset($data['fileDocument'])) {
                // Save document
                // upload document
                $upload = $this->fileUploadService->handleFile($data['fileDocument'])->saveToDb('document');

                if (!empty($upload)) {
                    $image = $this->fileTable->newQuery()->find($upload->id);
                    $image->update([
                        'fileable_type' => get_class($read),
                        'fileable_id'   => $read->id,
                    ]);
                }
            }

            foreach ($read->paket_kegiatan->standar_rab_paket_kegiatan as $item) {
                # code...
                $rab[] = [
                    'id_komponen'           => $item->master_komponen_rab_id,
                    'jenis_komponen_rab'    => $item->master_komponen_rab->jenis_komponen->jenis_komponen_rab,
                    'komponen_rab'          => $item->master_komponen_rab->komponen_rab,
                    'satuan'                => $item->master_komponen_rab->satuan->satuan,
                    'harga_unit'            => $item->standar_harga_unit,
                    'nilai_standar'         => $item->standar_harga_unit,
                    'qty'                   => $item->standar_qty,
                ];
            }

            $dataSend = [
                'id_pengajuan'  => $read->id,
                'nomor_pengajuan'   => $read->nomor_pengajuan,
                'komponen_rab'  => collect($rab)->groupBy('jenis_komponen_rab'),
            ];

            \DB::commit(); // commit the changes
            return $this->sendSuccess($dataSend);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function delete($id)
    {
        $read   =   $this->model->newQuery()->find($id);
        try {
            $read->delete();
            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function getDataRab($id, $inController = false)
    {
        $result = $this->model->find($id);

        if (!$result || count($result->rab_pengajuan_paket_kegiatans) <= 0)  return $this->sendError(null, 'Not Found', 422);

        $rab = null;
        foreach ($result->rab_pengajuan_paket_kegiatans as $item) {
            # code...
            $rab[] = [
                'id_komponen_rab'       => $item->id,
                'id_komponen'           => $item->master_komponen_rab->id,
                'jenis_komponen_rab'    => $item->master_komponen_rab->jenis_komponen->jenis_komponen_rab,
                'komponen_rab'          => $item->master_komponen_rab->komponen_rab,
                'satuan'                => $item->master_komponen_rab->satuan->satuan,
                'harga_unit'            => $item->harga_unit,
                'nilai_standar'         => $item->harga_unit,
                'qty'                   => $item->qty,
            ];
        }
        $collectRab = collect($rab);

        $result = [
            'id_pengajuan'  => $id,
            'caping_rab'    => $result->caping_rab,
            'komponen_rab'  => $collectRab->groupBy('jenis_komponen_rab'),
        ];

        if ($inController) {
            # code...
            return $result;
        }
        return $this->sendSuccess($result);
    }

    private function checkStatusPengajuan($angka, $logTahapanPengajuanKegiatan)
    {
        if ($logTahapanPengajuanKegiatan->count() == 9) {
            # code...
            switch ($angka) {
                case 1:
                    # code...
                    return 'Dalam Proses Verifikasi';
                    break;

                case 20:
                    return 'Ditolak';
                    break;

                default:
                    # code...
                    0;
                    break;
            }
        }
    }

    private function checkAngkaPengajuan($angka, $logTahapanPengajuanKegiatan)
    {
        if ($logTahapanPengajuanKegiatan->count() == 9) {
            # code...
            switch ($angka) {
                case 1:
                    # code...
                    return 6;
                    break;

                default:
                    # code...
                    0;
                    break;
            }
        }
    }

    public function updateRab($id, $dataKomponenRab)
    {
        $model = $this->model->newQuery()->where(['nomor_pengajuan' => $id])->first();

        if (!$model) return $this->sendError(null, 'Not found', 422);

        if ($model->flag != 0) return $this->sendError(null, 'Not Allowed', 403);

        if ($model->rab_pengajuan_paket_kegiatans->count() > 0) return $this->sendError(null, 'Rab sudah ada', 422);

        \DB::beginTransaction();

        try {
            //code...

            $dataTahapanPengajuanKegiatan = $this->modelTahapanPengajuanKegiatan->newQuery()
                ->orderBy('created_at', 'DESC')->get();

            foreach ($dataTahapanPengajuanKegiatan as $dt) {
                $this->modelLogTahapanPengajuanKegiatan->newQuery()->create([
                    'pengajuan_kegiatan_id'         => $model->id,
                    'tahapan_pengajuan_kegiatan_id' => $dt->id,
                    'tanggal_masuk'                 => ($dt->deskripsi_kegiatan == "Pengajuan" || $dt->deskripsi_kegiatan == "Verifikasi" ? date("Y-m-d") : NULL),
                    'tanggal_selesai'               => ($dt->deskripsi_kegiatan == "Pengajuan" ? date("Y-m-d") : NULL)
                ]);
            }

            $total = 0;
            $dataKomponenRabInput = null;
            foreach ($dataKomponenRab as $item) {
                # code...
                $dataKomponenRabInput[] = [
                    'pengajuan_kegiatan_id' => $model->id,
                    'komponen_rab_id'       => $item['id_komponen'],
                    'harga_unit'            => $item['harga_unit'],
                    'qty'                   => $item['qty'],
                ];
                $total += ($item['qty'] * $item['harga_unit']);
            }

            $result = [
                'nomor_pengajuan'   => $model->nomor_pengajuan,
                'sebesar'           => $total,
                'atas_nama'         => $model->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat
            ];

            $model->rab_pengajuan_paket_kegiatans()->saveMany(
                collect($dataKomponenRabInput)->map(function ($tahapSalur) {
                    return new RabPengajuanPaketKegiatan($tahapSalur);
                })
            );

            $model->flag = 1;
            $model->save();

            // Get data verifikator
            $verifikator = UserAkseslh::where('role_user', 'verifikator')->get();
            // Kirim Email ke verifikator
            foreach ($verifikator as $user) {
                # code...
                $this->emailPhpService->verifikasiPengajuanKegiatan($user, 'Verifikasi Pengajuan Kegiatan', $model, null, 'mail.verifikasi-pengajuan-kegiatan');
            }

            \DB::commit();
            return $this->sendSuccess($result);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function getDraftPengajuan($user_id)
    {
        $model = $this->model->newQuery()->where([
            'user_akseslh_id' => $user_id,
            'flag' => 0
        ])
            ->orWhere(function ($query) use ($user_id) {
                $query->where('user_akseslh_id', $user_id);
                $query->whereHas(
                    'log_tahapan_pengajuan',
                    function ($q) {
                        $q->where('flag', 2);
                        $q->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                            $q->where(['deskripsi_kegiatan' => 'Validasi']);
                        });
                    }
                );
            })->latest()
            ->first();

        $retur = null;

        if (!$model) return $this->sendSuccess(collect([]));

        if ($model->log_tahapan_pengajuan) {
            $retur = $model->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                $q->where('deskripsi_kegiatan', 'Validasi');
            })->first();
        }

        if ($retur && $retur->flag == 2) {
            # code...
            $result = [
                'id'                        => $model->id,
                'judul_pengajuan_kegiatan'  => $model->judul_pengajuan_kegiatan,
                'provinsi_kegiatan'         => $model->provinsi_kegiatan,
                'kabupaten_kegiatan'        => $model->kabupaten_kegiatan,
                'kecamatan_kegiatan'        => $model->kecamatan_kegiatan,
                'kelurahan_kegiatan'        => $model->kelurahan_kegiatan,
                'alamat_kegiatan'           => $model->alamat_kegiatan,
                'tanggal_kegiatan'          => $model->tanggal_mulai_kegiatan . ' - ' . $model->tanggal_akhir_kegiatan,
                'waktu_kegiatan'            => $model->time_mulai_kegiatan . ' - ' . $model->time_akhir_kegiatan,
                'proposal_kegiatan'         => $model->proposal_kegiatan,
                'tujuan_kegiatan'           => $model->tujuan_kegiatan,
                'ruang_lingkup_kegiatan'    => $model->ruang_lingkup_kegiatan,
                'paket_kegiatan_id'         => $model->paket_kegiatan_id,
                'fileDocument'              => $model->document,
                'nomor_pengajuan'           => $model->nomor_pengajuan,
                'status'                    => $retur ? ($retur->flag == 2 ? 'retur' : 'draft') : 'draft',
                'caping_rab'                => $model->caping_rab
            ];
        } else {
            # code...
            $result = [
                'id'                        => $model->id,
                'judul_pengajuan_kegiatan'  => $model->judul_pengajuan_kegiatan,
                'provinsi_kegiatan'         => $model->provinsi_kegiatan,
                'kabupaten_kegiatan'        => $model->kabupaten_kegiatan,
                'kecamatan_kegiatan'        => $model->kecamatan_kegiatan,
                'kelurahan_kegiatan'        => $model->kelurahan_kegiatan,
                'alamat_kegiatan'           => $model->alamat_kegiatan,
                'tanggal_kegiatan'          => $model->tanggal_mulai_kegiatan . ' - ' . $model->tanggal_akhir_kegiatan,
                'waktu_kegiatan'            => $model->time_mulai_kegiatan . ' - ' . $model->time_akhir_kegiatan,
                'proposal_kegiatan'         => $model->proposal_kegiatan,
                'tujuan_kegiatan'           => $model->tujuan_kegiatan,
                'ruang_lingkup_kegiatan'    => $model->ruang_lingkup_kegiatan,
                'paket_kegiatan_id'         => $model->paket_kegiatan_id,
                'fileDocument'              => $model->document,
                'nomor_pengajuan'           => $model->nomor_pengajuan,
                'caping_rab'                => $model->caping_rab
            ];
        }



        return $this->sendSuccess($result);
    }

    public function updateInformasiPencairanDana($id, $data)
    {
        $model = $this->model->newQuery()->where(['id' => $id])->first();

        if (!$model) {
            \Sentry\captureMessage('Validate Message: ' . $data['user_akseslh']->email . ' Pengajuan tidak ditemukan', \Sentry\Severity::warning());
            return $this->sendError(null, 'Not found', 422);
        }

        if ($model->flag != 3) {
            \Sentry\captureMessage('Validate Message: ' . $data['user_akseslh']->email . ' Flag pengajuan tidak sesuai', \Sentry\Severity::warning());
            return $this->sendError(null, 'Data Invalid', 422);
        }

        \DB::beginTransaction();

        try {
            //code...

            if (isset($data['perjanjian_kerjasama'])) {
                // Save document

                if ($data['perjanjian_kerjasama']->getClientOriginalExtension() == 'pdf') {
                    // upload document
                    $upload = $this->fileUploadService->handleFile($data['perjanjian_kerjasama'])->saveToDb('perjanjian_kerjasama');
                } else {
                    $upload = $this->fileUploadService->handleImage($data['perjanjian_kerjasama'])->saveToDb('perjanjian_kerjasama');
                }

                if (!empty($upload)) {
                    $document = $this->fileTable->newQuery()->find($upload->id);
                    $document->update([
                        'fileable_type' => get_class($model),
                        'fileable_id'   => $model->id,
                    ]);
                }
            }

            if (
                isset($data['tanggal_mulai_kegiatan']) &&
                isset($data['tanggal_akhir_kegiatan']) &&
                isset($data['time_mulai_kegiatan']) &&
                isset($data['time_akhir_kegiatan'])
            ) {
                # code...
                $model->tanggal_mulai_kegiatan  = $data["tanggal_mulai_kegiatan"];
                $model->tanggal_akhir_kegiatan  = $data["tanggal_akhir_kegiatan"];
                $model->time_mulai_kegiatan     = $data["time_mulai_kegiatan"];
                $model->time_akhir_kegiatan     = $data["time_akhir_kegiatan"];
            }

            $logTahapan = $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $id)
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Informasi Pencairan Dana');
                })->first();

            // Create Log Tahapan Pengajuan
            $this->modelDetailLogTahapanPengajuanKegiatan->newQuery()->create([
                'pengajuan_kegiatan_id'         => $model->id,
                'tahapan_pengajuan_kegiatan_id' => $logTahapan->tahapan_pengajuan_kegiatan_id,
                'tanggal_masuk'                 => date("Y-m-d"),
                'tanggal_selesai'               => date("Y-m-d")
            ]);


            $logTahapan->update(['tanggal_selesai' => date("Y-m-d")]);

            $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $id)
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Konfirmasi Pencairan Dana Termin 1');
                })
                ->update(['tanggal_masuk' => date("Y-m-d")]);

            $model->user_akseslh->unreadNotifications->markAsRead();
            $model->user_akseslh->data_pic_kelompok_masyarakat->update(['nama_gadis_ibu_kandung' => $data['nama_gadis_ibu_kandung']]);
            $model->flag = 4;
            $model->save();

            \DB::commit();
            return $this->sendSuccess();
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function getSk($id, $data)
    {
        $items =   $this->model->newQuery()->with(['document'])->find($id);

        if (!$items) return $this->sendError(null, 'Not found', 422);

        $result = [
            'url'   => env('APP_URL') . '/storage/' . $items->document->where('group', 'document_sk')->first()->file_path
        ];

        return $this->sendSuccess($result);
    }

    public function getProposal($id, $data)
    {
        $items =   $this->model->newQuery()->with(['document'])->find($id);

        if (!$items) return $this->sendError(null, 'Not found', 422);

        $result = [
            'url'   => $items->document->where('group', 'document')->first() ? env('APP_URL') . '/storage/' . $items->document->where('group', 'document')->first()->file_path : ''
        ];

        return $this->sendSuccess($result);
    }

    public function updateRabTemp($id, $dataKomponenRab, $user)
    {
        $logJadwalPembukaan = $this->modelLogJadwalPembukaan->newQuery()->latest()->first();

        // Mencari model pengajuan berdasarkan nomor pengajuan
        $model = $this->model->with(['rab_pengajuan_paket_kegiatans', 'user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat'])
            ->where('nomor_pengajuan', $id)
            ->first();

        // Memeriksa apakah model ditemukan dan valid
        if (!$model) {
            \Sentry\captureMessage('Validate Message: ' . $user->email_pic . ' Pengajuan tidak ditemukan', \Sentry\Severity::warning());
            return $this->sendError(null, 'Not found', 422);
        }

        if ($model->flag != 0) {
            \Sentry\captureMessage('Validate Message: ' . $user->email_pic . ' Flag pengajuan tidak sesuai', \Sentry\Severity::warning());
            return $this->sendError(null, 'Not Allowed', 422);
        }

        if ($model->rab_pengajuan_paket_kegiatans->count() > 0) {
            \Sentry\captureMessage('Validate Message: ' . $user->email_pic . ' Rab sudah ada', \Sentry\Severity::warning());
            return $this->sendError(null, 'Rab sudah ada', 422);
        }

        \DB::beginTransaction();

        try {
            // Menghitung total harga RAB dan mempersiapkan data komponen RAB
            $total = 0;
            $dataKomponenRabInput = array_map(function ($item) use ($model, &$total) {
                $total += $item['qty'] * $item['harga_unit'];
                return [
                    'pengajuan_kegiatan_id' => $model->id,
                    'komponen_rab_id'       => $item['id_komponen'],
                    'harga_unit'            => $item['harga_unit'],
                    'qty'                   => $item['qty'],
                ];
            }, $dataKomponenRab);

            if ($total > (int)$logJadwalPembukaan->batas_pengajuan) return $this->sendError(null, 'Rab tidak boleh lebih dari caping', 422);

            // Ambil tahapan pengajuan kegiatan terbaru sekali saja
            $dataTahapanPengajuanKegiatan = $this->modelTahapanPengajuanKegiatan->orderBy('sort', 'ASC')->get();

            // Menyimpan log tahapan pengajuan kegiatan
            $logData = $dataTahapanPengajuanKegiatan->map(function ($dt) use ($model) {
                return [
                    'id'                            => Uuid::uuid4()->toString(),
                    'pengajuan_kegiatan_id'         => $model->id,
                    'tahapan_pengajuan_kegiatan_id' => $dt->id,
                    'tanggal_masuk'                 => in_array($dt->deskripsi_kegiatan, ['Pengajuan', 'Verifikasi']) ? now()->toDateString() : null,
                    'tanggal_selesai'               => $dt->deskripsi_kegiatan == 'Pengajuan' ? now()->toDateString() : null,
                    'created_at'                    => Carbon::now(),
                    'updated_at'                    => Carbon::now(),
                ];
            });

            $this->modelLogTahapanPengajuanKegiatan->insert($logData->toArray());

            // Cari ID log untuk tahapan pengajuan kegiatan 'Pengajuan'
            $id_log = $dataTahapanPengajuanKegiatan->firstWhere('deskripsi_kegiatan', 'Pengajuan')->id;

            // Create Log Tahapan Pengajuan
            $this->modelDetailLogTahapanPengajuanKegiatan->newQuery()->create([
                'pengajuan_kegiatan_id'         => $model->id,
                'tahapan_pengajuan_kegiatan_id' => $id_log,
                'tanggal_masuk'                 => date("Y-m-d"),
                'tanggal_selesai'               => date("Y-m-d")
            ]);

            // Menyimpan RAB pengajuan paket kegiatan
            $model->rab_pengajuan_paket_kegiatans()->createMany($dataKomponenRabInput);

            // Update status flag
            $model->update(['flag' => 1]);

            // Persiapkan data untuk response
            $result = [
                'nomor_pengajuan'   => $model->nomor_pengajuan,
                'sebesar'           => $total,
                'atas_nama'         => $model->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat
            ];

            // Mengirim email ke verifikator
            // $verifikator = UserAkseslh::where('role_user', 'verifikator')->get();
            $verifikator = UserAkseslh::where('role_user', 'verifikator')
                ->whereHas('master_user_jenis_kelompok', function ($q) use ($user) {
                    $q->where('jenis_kelompok_masyarakat_id', $user->data_pic_kelompok_masyarakat->kelompok_masyarakat->jenis_kelompok_masyarakat_id);
                })
                ->get();

            foreach ($verifikator as $user) {
                $this->emailPhpService->verifikasiPengajuanKegiatan($user, 'Verifikasi Pengajuan Kegiatan', $model, null, 'mail.verifikasi-pengajuan-kegiatan');
            }

            \DB::commit();
            return $this->sendSuccess($result);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : 'Internal Server Error', 500);
        }
    }

    public function updateTemp($id, $data)
    {
        // Eager load paket_kegiatan dan standar_rab_paket_kegiatan untuk menghindari query berulang
        $read = $this->model->with(['paket_kegiatan.standar_rab_paket_kegiatan.master_komponen_rab.satuan', 'paket_kegiatan.standar_rab_paket_kegiatan.master_komponen_rab.jenis_komponen'])
            ->where(['nomor_pengajuan' => $id, 'user_akseslh_id' => $data['user_akseslh_id']])
            ->first();

        if (!$read) {
            return $this->sendError(null, 'Not Found', 422);
        }

        if ($read->flag > 0) {
            return $this->sendError(null, 'Data bukan draft', 422);
        }

        $logJadwalPembukaan = $this->modelLogJadwalPembukaan->newQuery()->latest()->first();

        \DB::beginTransaction();

        try {
            // Update model data menggunakan method update
            $read->update([
                'paket_kegiatan_id'        => $data['paket_kegiatan_id'],
                'user_akseslh_id'          => $data['user_akseslh_id'],
                'judul_pengajuan_kegiatan' => $data['judul_pengajuan_kegiatan'] ?? null,
                'provinsi_kegiatan'        => $data['provinsi_kegiatan'] ?? null,
                'kabupaten_kegiatan'       => $data['kabupaten_kegiatan'] ?? null,
                'kecamatan_kegiatan'       => $data['kecamatan_kegiatan'] ?? null,
                'kelurahan_kegiatan'       => $data['kelurahan_kegiatan'] ?? null,
                'alamat_kegiatan'          => $data['alamat_kegiatan'] ?? null,
                'proposal_kegiatan'        => $data['proposal_kegiatan'] ?? null,
                'tujuan_kegiatan'          => $data['tujuan_kegiatan'] ?? null,
                'ruang_lingkup_kegiatan'   => $data['ruang_lingkup_kegiatan'] ?? null,
                'tanggal_mulai_kegiatan'   => isset($data['tanggal_mulai_kegiatan']) ? date_create($data['tanggal_mulai_kegiatan']) : Carbon::now()->format('Y-m-d'),
                'tanggal_akhir_kegiatan'   => isset($data['tanggal_akhir_kegiatan']) ? date_create($data['tanggal_akhir_kegiatan']) : Carbon::now()->format('Y-m-d'),
                'time_mulai_kegiatan'      => $data['time_mulai_kegiatan'] ?? '08:00',
                'time_akhir_kegiatan'      => $data['time_akhir_kegiatan'] ?? '16:00'
            ]);

            // Jika ada file document, proses upload
            if (isset($data['fileDocument'])) {

                $temp = $read->document()->where('group', 'document')->first();
                if ($temp) {
                    $this->fileUploadService->deleteFiles($temp->file_path);
                    $temp->delete();
                }

                $upload = $this->fileUploadService->handleFile($data['fileDocument'])->saveToDb('document');
                if ($upload) {
                    $upload->update([
                        'fileable_type' => get_class($read),
                        'fileable_id'   => $read->id
                    ]);
                }
            }

            // Mengambil data komponen RAB secara langsung tanpa loop berlebihan
            $rab = $read->paket_kegiatan->standar_rab_paket_kegiatan->map(function ($item) {
                return [
                    'id_komponen'        => $item->master_komponen_rab_id,
                    'jenis_komponen_rab' => $item->master_komponen_rab->jenis_komponen->jenis_komponen_rab,
                    'komponen_rab'       => $item->master_komponen_rab->komponen_rab,
                    'satuan'             => $item->master_komponen_rab->satuan->satuan,
                    'harga_unit'         => $item->standar_harga_unit,
                    'nilai_standar'      => $item->standar_harga_unit,
                    'qty'                => $item->standar_qty,
                ];
            });

            // Mengelompokkan komponen RAB berdasarkan jenis
            $dataSend = [
                'id_pengajuan'    => $read->id,
                'nomor_pengajuan' => $read->nomor_pengajuan,
                'caping_rab'      => $read->caping_rab > 0 ? $read->caping_rab : $logJadwalPembukaan->batas_pengajuan,
                'komponen_rab'    => $rab->groupBy('jenis_komponen_rab')
            ];

            \DB::commit();
            return $this->sendSuccess($dataSend);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : 'Internal Server Error', 500);
        }
    }

    public function createTemp($data)
    {
        // Mengecek data sebelumnya
        $cekData = PengajuanKegiatan::where('user_akseslh_id', $data['user_akseslh_id'])->latest()->first();

        if ($cekData && $cekData->flag < 11) {
            \Sentry\captureMessage('Validate Message: ' . $data['user']->email . ' masih ada pengajuan', \Sentry\Severity::warning());
            return $this->sendError(null, 'Masih ada pengajuan yang berlangsung', 422);
        }

        \DB::beginTransaction();

        try {
            // Menghasilkan nomor pengajuan otomatis
            $data['nomor_pengajuan'] = PengajuanKegiatan::generateNomorPengajuan($data['paket_kegiatan_id'], $data['user']);

            // Menyimpan data PengajuanKegiatan
            $newData = $this->model->create([
                'nomor_pengajuan'               => $data['nomor_pengajuan'],
                'paket_kegiatan_id'             => $data['paket_kegiatan_id'],
                'user_akseslh_id'               => $data['user_akseslh_id'],
                'judul_pengajuan_kegiatan'      => $data['judul_pengajuan_kegiatan'] ?? null,
                'provinsi_kegiatan'             => $data['provinsi_kegiatan'] ?? null,
                'kabupaten_kegiatan'            => $data['kabupaten_kegiatan'] ?? null,
                'kecamatan_kegiatan'            => $data['kecamatan_kegiatan'] ?? null,
                'kelurahan_kegiatan'            => $data['kelurahan_kegiatan'] ?? null,
                'alamat_kegiatan'               => $data['alamat_kegiatan'] ?? null,
                'proposal_kegiatan'             => $data['proposal_kegiatan'] ?? null,
                'tujuan_kegiatan'               => $data['tujuan_kegiatan'] ?? null,
                'ruang_lingkup_kegiatan'        => $data['ruang_lingkup_kegiatan'] ?? null,
                'tanggal_mulai_kegiatan'        => $data['tanggal_mulai_kegiatan'] ? date_create($data['tanggal_mulai_kegiatan']) : Carbon::now()->format('Y-m-d'),
                'tanggal_akhir_kegiatan'        => $data['tanggal_akhir_kegiatan'] ? date_create($data['tanggal_akhir_kegiatan']) : Carbon::now()->format('Y-m-d'),
                'time_mulai_kegiatan'           => $data['time_mulai_kegiatan'] ?? '08:00',
                'time_akhir_kegiatan'           => $data['time_akhir_kegiatan'] ?? '16:00',
                'lokasi_bidang_folu_id'         => $data['lokasi_bidang_folu_id'] ?? null,
                'flag'                          => 0,
            ]);

            // Jika ada file untuk di-upload
            if (isset($data['fileDocument'])) {
                $upload = $this->fileUploadService->handleFile($data['fileDocument'])->saveToDb('document');

                if ($upload) {
                    $upload->update([
                        'fileable_type' => get_class($newData),
                        'fileable_id'   => $newData->id,
                    ]);
                }
            }

            // Eager load relasi yang dibutuhkan dan map data untuk komponen RAB
            $rab = $newData->paket_kegiatan->standar_rab_paket_kegiatan->map(function ($item) {
                return [
                    'id_komponen'        => $item->master_komponen_rab_id,
                    'jenis_komponen_rab' => $item->master_komponen_rab->jenis_komponen->jenis_komponen_rab,
                    'komponen_rab'       => $item->master_komponen_rab->komponen_rab,
                    'satuan'             => $item->master_komponen_rab->satuan->satuan,
                    'harga_unit'         => $item->standar_harga_unit,
                    'nilai_standar'      => $item->standar_harga_unit,
                    'qty'                => $item->standar_qty,
                ];
            });

            // Menyiapkan data yang akan dikirim
            $dataSend = [
                'id_pengajuan'    => $newData->id,
                'nomor_pengajuan' => $data['nomor_pengajuan'],
                'komponen_rab'    => $rab->groupBy('jenis_komponen_rab'),
            ];

            // Proses PDF (jika dibutuhkan)
            // $pdf = $this->pdfService->generateAndSavePdf('pdf.template-small-grant', get_class($newData), $newData, $data['nomor_pengajuan']);

            \DB::commit(); // commit the changes
            return $this->sendSuccess($dataSend);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function revisi_pengajuan_kegiatan_create($data)
    {
        // Mengecek data sebelumnya
        $read = PengajuanKegiatan::where(['id' => $data['id_pengajuan'], 'user_akseslh_id' => $data['user_akseslh_id']])->first();

        if (!$read) {
            \Sentry\captureMessage('Validate Message: ' . $data['user']->email_pic . ' Pengajuan tidak ditemukan', \Sentry\Severity::warning());
            return $this->sendError(null, 'Not Found', 422);
        }

        if (!in_array($read->flag, [0, '0'])) {
            \Sentry\captureMessage('Validate Message: ' . $data['user']->email_pic . ' Flag pengajuan tidak sesuai', \Sentry\Severity::warning());
            return $this->sendError(null, 'Invalid Data', 422);
        }

        $retur = $read->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
            $q->where('deskripsi_kegiatan', 'Validasi');
        })->first();

        if (!$retur || ($retur && $retur->flag != 2)) {
            \Sentry\captureMessage('Validate Message: ' . $data['user']->email_pic . ' Bukan data retur', \Sentry\Severity::warning());
            return $this->sendError(null, 'Invalid Data', 422);
        }

        \DB::beginTransaction();

        try {

            // Eager load relasi yang dibutuhkan dan map data untuk komponen RAB
            $rab = $read->rab_pengajuan_paket_kegiatans->map(function ($item) {
                return [
                    'id_komponen'        => $item->master_komponen_rab->id,
                    'jenis_komponen_rab' => $item->master_komponen_rab->jenis_komponen->jenis_komponen_rab,
                    'komponen_rab'       => $item->master_komponen_rab->komponen_rab,
                    'satuan'             => $item->master_komponen_rab->satuan->satuan,
                    'harga_unit'         => $item->harga_unit,
                    'nilai_standar'      => $item->harga_unit,
                    'qty'                => $item->qty,
                ];
            });

            // Menyiapkan data yang akan dikirim
            $dataSend = [
                'id_pengajuan'    => $read->id,
                'nomor_pengajuan' => $read->nomor_pengajuan,
                'caping_rab'      => $read->caping_rab,
                'komponen_rab'    => $rab->groupBy('jenis_komponen_rab'),
            ];

            \DB::commit(); // commit the changes
            return $this->sendSuccess($dataSend);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function revisi_pengajuan_kegiatan_update($id, $data)
    {
        // Mencari model pengajuan berdasarkan nomor pengajuan
        $model = $this->model->with(['rab_pengajuan_paket_kegiatans', 'user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat'])
            ->where(['nomor_pengajuan' => $id, 'user_akseslh_id' => $data['user_akseslh_id']])
            ->first();

        // Memeriksa apakah model ditemukan dan valid
        if (!$model) {
            \Sentry\captureMessage('Validate Message: ' . $data['user']->email_pic . ' Pengajuan tidak ditemukan', \Sentry\Severity::warning());
            return $this->sendError(null, 'Not found', 422);
        }

        if ($model->flag != 0) {
            \Sentry\captureMessage('Validate Message: ' . $data['user']->email_pic . ' Flag pengajuan tidak sesuai', \Sentry\Severity::warning());
            return $this->sendError(null, 'Not Allowed', 422);
        }

        $retur = $model->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
            $q->where('deskripsi_kegiatan', 'Validasi');
        })->first();

        if (!$retur || ($retur && $retur->flag != 2)) {
            \Sentry\captureMessage('Validate Message: ' . $data['user']->email_pic . ' Bukan data retur', \Sentry\Severity::warning());
            return $this->sendError(null, 'Invalid Data', 422);
        }

        \DB::beginTransaction();

        try {

            $retur->update(['tanggal_selesai' => null, 'user_akseslh_id' => null, 'flag' => 1]);

            // Ambil tahapan pengajuan kegiatan terbaru sekali saja
            $id_log = $this->modelTahapanPengajuanKegiatan->firstWhere('deskripsi_kegiatan', 'Pengajuan')->id;

            // Create Detail Log Tahapan Pengajuan
            $this->modelDetailLogTahapanPengajuanKegiatan->newQuery()->create([
                'pengajuan_kegiatan_id'         => $model->id,
                'tahapan_pengajuan_kegiatan_id' => $id_log,
                'tanggal_masuk'                 => date("Y-m-d"),
                'tanggal_selesai'               => date("Y-m-d")
            ]);

            // Menghitung total harga RAB dan mempersiapkan data komponen RAB
            $total = 0;
            $dataKomponenRabInput = array_map(function ($item) use ($model, &$total) {
                $total += $item['qty'] * $item['harga_unit'];
                return [
                    'pengajuan_kegiatan_id' => $model->id,
                    'komponen_rab_id'       => $item['id_komponen'],
                    'harga_unit'            => $item['harga_unit'],
                    'qty'                   => $item['qty'],
                ];
            }, $data['komponen_rab']);

            if ($total > $model->caping_rab) {
                return $this->sendError(null, 'Nilai RAB tidak boleh melebihi caping', 422);
            }

            // Menyimpan rab sebelumnya ke tabel log rab
            $rabData = $model->rab_pengajuan_paket_kegiatans->map(function ($dt) use ($model) {
                return [
                    'id'                    => Uuid::uuid4()->toString(),
                    'pengajuan_kegiatan_id' => $model->id,
                    'komponen_rab_id'       => $dt->komponen_rab_id,
                    'harga_unit'            => $dt->harga_unit,
                    'qty'                   => $dt->qty,
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ];
            });

            // Insert Ke log Rab Pengajuan Kegiatan
            $this->modelLogRabPengajuanKegiatan->insert($rabData->toArray());

            // Menghapus RAB Sebelumnya
            $model->rab_pengajuan_paket_kegiatans()->forceDelete();

            // Menyimpan RAB pengajuan paket kegiatan
            $model->rab_pengajuan_paket_kegiatans()->createMany($dataKomponenRabInput);

            // Update status flag
            $model->update(['flag' => 2]);

            // Persiapkan data untuk response
            $result = [
                'nomor_pengajuan'   => $model->nomor_pengajuan,
                'sebesar'           => $total ?? null,
                'atas_nama'         => $model->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat
            ];

            \DB::commit();
            return $this->sendSuccess($result);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : 'Internal Server Error', 500);
        }
    }

    public function getDokumen($id)
    {
        $result =   $this->model->newQuery()->find($id);

        return $this->sendSuccess($result);
    }

    public function updateDokumen($id, $data)
    {
        $result =   $this->model->newQuery()->find($id);

        if (!$result) return $this->sendError(null, 'Not found', 422);

        \DB::beginTransaction();

        try {
            //
            if ($data['jenis_dokumen'] == 'document') {
                # code...
                $temp = $result->document()->where('group', 'document')->first();
                if ($temp) {
                    $this->fileUploadService->deleteFiles($temp->file_path);
                    $temp->delete();
                }

                $upload = $this->fileUploadService->handleFile($data['document'])->saveToDb('document');

                if ($upload) {
                    $upload->update([
                        'fileable_type' => get_class($result),
                        'fileable_id'   => $result->id,
                    ]);
                }

                $upload_pendukung = $this->fileUploadService->handleFile($data['dokumen_pendukung'])->saveToDb('dokumen_pendukung');

                if ($upload_pendukung) {
                    $upload_pendukung->update([
                        'fileable_type' => get_class($result),
                        'fileable_id'   => $result->id,
                    ]);
                }
            } else {
                $upload = $this->fileUploadService->handleFile($data['document'])->saveToDb($data['jenis_dokumen']);
                if ($upload) {
                    $upload->update([
                        'fileable_type' => get_class($result->user_akseslh->data_pic_kelompok_masyarakat),
                        'fileable_id'   => $result->user_akseslh->data_pic_kelompok_masyarakat->id,
                    ]);
                }

                $upload_pendukung = $this->fileUploadService->handleFile($data['dokumen_pendukung'])->saveToDb('dokumen_pendukung');

                if ($upload_pendukung) {
                    $upload_pendukung->update([
                        'fileable_type' => get_class($result->user_akseslh->data_pic_kelompok_masyarakat),
                        'fileable_id'   => $result->user_akseslh->data_pic_kelompok_masyarakat->id,
                    ]);
                }
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess(null);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }
}
