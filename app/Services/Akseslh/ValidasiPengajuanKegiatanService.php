<?php


namespace App\Services\Akseslh;

use Carbon\Carbon;
use App\Services\AppService;
use App\Models\File as FileTable;
use App\Models\PengajuanKegiatan;
use App\Services\EmailPhpService;
use App\Services\FileUploadService;
use App\Services\AppServiceInterface;
use App\Models\TahapanPengajuanKegiatan;
use Yajra\DataTables\Facades\DataTables;
use App\Models\LogTahapanPengajuanKegiatan;
use App\Models\CatatanLogTahapanPengajuanKegiatan;
use App\Notifications\VerifikasiValidasiNotification;


class ValidasiPengajuanKegiatanService extends AppService implements AppServiceInterface
{
    private $modelTahapanPengajuanKegiatan;
    protected $modelLogTahapanPengajuanKegiatan;
    protected $modelCatatanLogTahapanPengajuanKegiatan;
    protected $fileUploadService;
    protected $fileTable;
    protected $emailService;

    public function __construct(
        PengajuanKegiatan $model,
        TahapanPengajuanKegiatan $modelTahapanPengajuanKegiatan,
        LogTahapanPengajuanKegiatan $modelLogTahapanPengajuanKegiatan,
        CatatanLogTahapanPengajuanKegiatan $modelCatatanLogTahapanPengajuanKegiatan,
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        EmailPhpService $emailPhpService
    ) {
        parent::__construct($model);
        $this->modelTahapanPengajuanKegiatan            = $modelTahapanPengajuanKegiatan;
        $this->modelLogTahapanPengajuanKegiatan         = $modelLogTahapanPengajuanKegiatan;
        $this->modelCatatanLogTahapanPengajuanKegiatan  = $modelCatatanLogTahapanPengajuanKegiatan;
        $this->fileUploadService                        =   $fileUploadService;
        $this->fileTable                                =   $fileTable;
        $this->emailService = $emailPhpService;
    }

    public function getAll()
    {
        $model = $this->model->query()->orderBy('created_at', 'DESC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function apiGetAll()
    {
        $result  = $this->model->newQuery()
            ->where('flag', 3)
            ->orderBy('created_at', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'                 => $items->id,
                'jenis_kegiatan'     => $items->jenis_kegiatan,
            ];
        });

        return $this->sendSuccess($result);
    }

    public function getAllAttr($data = null)
    {
        if ($data) {
            # code...
            switch ($data) {
                case 4:
                    # code...
                    $result  = $this->model->newQuery()
                        ->whereHas(
                            'log_tahapan_pengajuan',
                            function ($q) {
                                $q->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                                    $q->where(['deskripsi_kegiatan' => 'Informasi Pencairan Dana']);
                                })->whereNotNull('tanggal_masuk')
                                    ->whereNull('tanggal_selesai');
                            }
                        )
                        ->orderBy('created_at', 'ASC')
                        ->get();
                    break;
                case 6:
                    # code...
                    $result  = $this->model->newQuery()
                        ->whereHas(
                            'log_tahapan_pengajuan',
                            function ($q) {
                                $q->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                                    $q->where(['deskripsi_kegiatan' => 'Verifikasi Laporan Kegiatan Termin 1']);
                                })->whereNotNull('tanggal_masuk')
                                    ->whereNull('tanggal_selesai');
                            }
                        )
                        ->orderBy('created_at', 'ASC')
                        ->get();
                    break;

                case 9:
                    # code...
                    $result  = $this->model->newQuery()
                        ->whereHas(
                            'log_tahapan_pengajuan',
                            function ($q) {
                                $q->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                                    $q->where(['deskripsi_kegiatan' => 'Verifikasi Laporan Akhir Kegiatan']);
                                })->whereNotNull('tanggal_masuk')
                                    ->whereNull('tanggal_selesai');
                            }
                        )
                        ->orderBy('created_at', 'ASC')
                        ->get();
                    break;

                default:
                    # code...
                    $result  = $this->model->newQuery()
                        ->whereHas(
                            'log_tahapan_pengajuan',
                            function ($q) {
                                $q->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                                    $q->where(['deskripsi_kegiatan' => 'Validasi']);
                                })->whereNotNull('tanggal_masuk')
                                    ->whereNull('tanggal_selesai');
                            }
                        )
                        ->orderBy('created_at', 'ASC')
                        ->get();
                    break;
            }
        } else {
            $result  = $this->model->newQuery()
                ->whereHas('log_tahapan_pengajuan', function ($q) {
                    $q->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where(['deskripsi_kegiatan' => 'Validasi']);
                    })->whereNotNull('tanggal_masuk')
                        ->whereNull('tanggal_selesai');
                })
                ->where('flag', 2)
                ->orderBy('created_at', 'ASC')
                ->get();
        }

        $result->transform(function ($items, $key) {
            return [
                'id'                        => $items->id,
                'kelompok_masyarakat'       => $items->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat,
                'tematik_kegiatan'          => $items->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan,
                'sub_tematik_kegiatan'      => $items->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan,
                'judul_pengajuan_kegiatan'  => $items->judul_pengajuan_kegiatan,
                'kegiatan'                  => $items->paket_kegiatan->jenis_kegiatan->jenis_kegiatan . " " . $items->paket_kegiatan->jumlah_peserta . " " . ($items->paket_kegiatan->jumlah_peserta > 50 ? "Orang" : "Hektare"),
                'jenis_kegiatan'            => $items->paket_kegiatan->jenis_kegiatan->jenis_kegiatan,
                'rencana_kegiatan'          => $items->tanggal_mulai_kegiatan,
                'jumlah'                    => $items->paket_kegiatan->jumlah_peserta . " " . ($items->paket_kegiatan->jumlah_peserta >= 50 ? "Orang" : "Hectare"),
                'tanggal_pengajuan'         => $items->created_at->format('d M Y H:i'),
                'tanggal_akhir_validasi'    => Carbon::parse($items->created_at)->locale('id')->addDays(7)->format('d M Y'),
                'kelompok_masyarakat'       => $items->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat,
                'nama_pic'                  => $items->user_akseslh->data_pic_kelompok_masyarakat->nama_pic,
                'email_pic'                 => $items->user_akseslh->data_pic_kelompok_masyarakat->email_pic,
                'lokasi'                    => $items->alamat_kegiatan,
                'nomor_pengajuan'           => $items->nomor_pengajuan,
                'proposal_kegiatan'         => $items->proposal_kegiatan,
                'tujuan_kegiatan'           => $items->tujuan_kegiatan,
                'ruang_lingkup_kegiatan'    => $items->ruang_lingkup_kegiatan,
                'nama_verifikator'          => $items->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where(['deskripsi_kegiatan' => 'Verifikasi']);
                })->first()->user_akseslh_admin->email,
                'tanggal_verifikasi'        => $items->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where(['deskripsi_kegiatan' => 'Verifikasi']);
                })->first()->tanggal_selesai,
                'document'                      => $items->document,
                'indikator_laporan_kegiatan'    => $items->indikator_laporan_kegiatan->transform(function ($items, $key) {
                    return [
                        'nilai_laporan' => $items->nilai_laporan,
                        'nama_indikator' => $items->master_data_indikator_laporan->nama_indikator,
                        'satuan' => $items->master_data_indikator_laporan->satuan,
                        'tipe_data' => $items->master_data_indikator_laporan->satuan,
                    ];
                })
            ];
        });

        return $this->sendSuccess($result);
    }

    public function apiGetBydId($id)
    {
        $model = $this->model->newQuery()->find($id);

        if (!$model)  return $this->sendError(null, 'Not Found');

        $result = [
            'id'                        => $model->id,
            'kelompok_masyarakat'       => $model->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat,
            'tematik_kegiatan'          => $model->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan,
            'sub_tematik_kegiatan'      => $model->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan,
            'judul_pengajuan_kegiatan'  => $model->judul_pengajuan_kegiatan,
            'kegiatan'                  => $model->paket_kegiatan->jenis_kegiatan->jenis_kegiatan . " " . $model->paket_kegiatan->jumlah_peserta . " " . ($model->paket_kegiatan->jumlah_peserta > 50 ? "Orang" : "Hektare"),
            'jenis_kegiatan'            => $model->paket_kegiatan->jenis_kegiatan->jenis_kegiatan,
            'rencana_kegiatan'          => $model->tanggal_mulai_kegiatan,
            'jumlah'                    => $model->paket_kegiatan->jumlah_peserta . " " . ($model->paket_kegiatan->jumlah_peserta >= 50 ? "Orang" : "Hectare"),
            'tanggal_pengajuan'         => $model->created_at->format('d M Y H:i'),
            'tanggal_akhir_validasi'    => Carbon::parse($model->created_at)->locale('id')->addDays(7)->format('d M Y'),
            'kelompok_masyarakat'       => $model->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat,
            'nama_pic'                  => $model->user_akseslh->data_pic_kelompok_masyarakat->nama_pic,
            'email_pic'                 => $model->user_akseslh->data_pic_kelompok_masyarakat->email_pic,
            'lokasi'                    => $model->alamat_kegiatan,
            'nomor_pengajuan'           => $model->nomor_pengajuan,
            'proposal_kegiatan'         => $model->proposal_kegiatan,
            'tujuan_kegiatan'           => $model->tujuan_kegiatan,
            'ruang_lingkup_kegiatan'    => $model->ruang_lingkup_kegiatan,
            'nama_verifikator'          => $model->log_tahapan_pengajuan->whereNotNull('user_akseslh_id')->first()->user_akseslh_admin->email,
            'tanggal_verifikasi'        => $model->log_tahapan_pengajuan->whereNotNull('user_akseslh_id')->first()->tanggal_selesai,
            'document'                  => $model->document
        ];

        return $this->sendSuccess($result);
    }

    public function getPaginated($search = null, $page = null, $perPage = null, $lang = null)
    {
        $result =   $this->switchLang($search, $page, $perPage, $lang);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result =   $this->model->newQuery()->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $data = $this->model->newQuery()->create([
                'jenis_kegiatan'       =>  $data['jenis_kegiatan'],
                'flag'                 => 1,
            ]);

            \DB::commit(); // commit the changes
            return $this->sendSuccess($data);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function update($id, $data)
    {
        $read   =   $this->model->newQuery()->find($id);

        if (!$read) return $this->sendError(null, 'Not Found');

        $total = 0;

        foreach ($read->rab_pengajuan_paket_kegiatans as $items) {
            # code...
            $total += ($items->qty * $items->harga_unit);
        }

        \DB::beginTransaction();

        try {

            $idLog = $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $id)
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Validasi');
                })->first()->id;

            $this->modelCatatanLogTahapanPengajuanKegiatan->newQuery()
                ->create([
                    'log_tahapan_pengajuan_kegiatan_id' => $idLog,
                    'catatan_log'           => $data['catatan_log'],
                    'flag'                  => "3"
                ]);

            // $dataTahapanPengajuanKegiatan = $this->modelTahapanPengajuanKegiatan->newQuery()
            //     ->orderBy('created_at', 'DESC')->get();
            // $dataLogTahapanPengajuanKegiatan = $this->modelLogTahapanPengajuanKegiatan->newQuery()
            //     ->with(['tahapan_pengajuan_kegiatan'])
            //     ->where('pengajuan_kegiatan_id', $id)
            //     ->orderBy('created_at', 'DESC')->get();

            if ($data['status'] == 0) {
                $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Validasi');
                    })
                    ->update(['tanggal_selesai' => date("Y-m-d"), 'user_akseslh_id' => $data['user_akselh_id']]);

                $read->flag = 20;
                $read->save();

                $dataSend = array(
                    'nomor_pengajuan' => $read->nomor_pengajuan,
                    'catatan_log'     => $data['catatan_log'],
                    'keterangan'      => 'Ditolak',
                    'status'          => '20'
                );

                $this->emailService->verifikasiValidasiDitolak($read->user_akseslh, 'Pengajuan Ditolak', $dataSend, null, 'mail.verifikasi-pengajuan-kegiatan-ditolak');
            } else {

                // Update data langsung berdasarkan pengajuan_kegiatan_id
                $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Validasi');
                    })
                    ->update(['tanggal_selesai' => date("Y-m-d"), 'user_akseslh_id' => $data['user_akselh_id']]);

                $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Informasi Pencairan Dana');
                    })
                    ->update(['tanggal_masuk' => date("Y-m-d")]);

                $read->flag = 3;
                $read->save();

                // Save document 
                // upload document
                $upload = $this->fileUploadService->handleFile($data['file_sk'])->saveToDb('document_sk');

                if (!empty($upload)) {
                    $image = $this->fileTable->newQuery()->find($upload->id);
                    $image->update([
                        'fileable_type' => get_class($read),
                        'fileable_id'   => $read->id,
                    ]);
                }

                $dataSend = array(
                    'nomor_pengajuan'           => $read->nomor_pengajuan,
                    'keterangan'                => 'Disetujui',
                    'status'                    => '3',
                    'judul_pengajuan_kegiatan'  => $read->judul_pengajuan_kegiatan,
                    'total'                     => $total,
                    'nama_pic'                  => $read->user_akseslh->data_pic_kelompok_masyarakat->nama_pic,
                    'kelompok_masyarakat'       => $read->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat,
                    'document_sk'               => env('APP_URL') . '/storage/' . $read->document->where('group', 'document_sk')->first()->file_path
                );

                // $this->emailService->pengajuanKegiatanDiterima(
                //     $read->user_akseslh,
                //     'Pemberitahuan Persetujuan Pengajuan Proposal Akses Dana Layanan Masyarakat untuk Lingkungan',
                //     $dataSend,
                //     null,
                //     'mail.pengajuan-kegiatan-diterima'
                // );

                $read->user_akseslh->notify(new VerifikasiValidasiNotification($read->nomor_pengajuan, $read->user_akseslh->data_pic_kelompok_masyarakat->nama_pic, $total));
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess($dataSend);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function update_termin_1($id, $data)
    {
        $read   =   $this->model->newQuery()->find($id);

        // If data not found
        if (!$read) return $this->sendError(null, 'Not Found', 422);

        // If data flag not equals 6 (Verifikasi Laporan Kegiatan Termin 1)
        if ($read->flag != 6 || $read->flag != '6') return $this->sendError(null, 'Data Invalid', 422);

        \DB::beginTransaction();

        try {

            if (isset($data['catatan_log'])) {
                $idLog = $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Verifikasi Laporan Kegiatan Termin 1');
                    })->first()->id;

                $this->modelCatatanLogTahapanPengajuanKegiatan->newQuery()
                    ->create([
                        'log_tahapan_pengajuan_kegiatan_id' => $idLog,
                        'catatan_log'           => $data['catatan_log'],
                        'flag'                  => "6"
                    ]);
            }

            // Update data langsung berdasarkan pengajuan_kegiatan_id
            $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $id)
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Verifikasi Laporan Kegiatan Termin 1');
                })
                ->update(['tanggal_selesai' => date("Y-m-d"), 'user_akseslh_id' => $data['user']->id]);

            $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $id)
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Konfirmasi Pencairan Dana Termin II');
                })
                ->update(['tanggal_masuk' => date("Y-m-d")]);

            $read->flag = 7;
            $read->save();

            // Save document 
            // upload document
            $upload = $this->fileUploadService->handleFile($data['surat_pencairan_dana_termin_2'])->saveToDb('surat_pencairan_dana_termin_2');

            if (!empty($upload)) {
                $image = $this->fileTable->newQuery()->find($upload->id);
                $image->update([
                    'fileable_type' => get_class($read),
                    'fileable_id'   => $read->id,
                ]);
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess(null);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function update_tahap_akhir($id, $data)
    {
        $read   =   $this->model->newQuery()->find($id);

        // If data not found
        if (!$read) return $this->sendError(null, 'Not Found', 422);

        // If data flag not equals 8 (Verifikasi Laporan Akhir)
        if ($read->flag != 9 || $read->flag != '9') return $this->sendError(null, 'Data Invalid', 422);

        \DB::beginTransaction();

        try {

            if (isset($data['catatan_log'])) {
                $idLog = $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Verifikasi Laporan Akhir Kegiatan');
                    })->first()->id;

                $this->modelCatatanLogTahapanPengajuanKegiatan->newQuery()
                    ->create([
                        'log_tahapan_pengajuan_kegiatan_id' => $idLog,
                        'catatan_log'           => $data['catatan_log'],
                        'flag'                  => "8"
                    ]);
            }

            // Update data langsung berdasarkan pengajuan_kegiatan_id
            $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $id)
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Verifikasi Laporan Akhir Kegiatan');
                })
                ->update(['tanggal_selesai' => date("Y-m-d"), 'user_akseslh_id' => $data['user']->id]);

            $read->flag = 10;
            $read->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess(null);
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
}
