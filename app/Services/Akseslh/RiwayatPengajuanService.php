<?php


namespace App\Services\Akseslh;


use Carbon\Carbon;
use App\Services\AppService;
use App\Models\PengajuanKegiatan;
use App\Services\AppServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class RiwayatPengajuanService extends AppService implements AppServiceInterface
{

    public function __construct(PengajuanKegiatan $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {
        $model = $this->model->query()->orderBy('short_id', 'ASC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function getAllAttr()
    {
        $result  = $this->model->newQuery()
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

    public function getPaginated($flag = null, $search = null, $page = null, $perPage = null, $tahapanKegiatan = null, $input = null)
    {
        $createdAtAwal = $input['created_at_awal'] ?? null;
        $createdAtAkhir = $input['created_at_akhir'] ?? null;
        $tanggalAwalKegiatan = $input['tanggal_mulai_kegiatan'] ?? null;
        $tanggalAkhirKegiatan = $input['tanggal_akhir_kegiatan'] ?? null;
        $result  = $this->model->newQuery()
            ->with(['paket_kegiatan.jenis_kegiatan' => function ($query) {
                $query->withTrashed();
            }, 'paket_kegiatan.master_sub_tematik_kegiatan.sub_tematik_kegiatan' => function ($query) {
                $query->withTrashed();
            }])
            ->when($search, function ($query) use ($search) {
                return $query->where('nomor_pengajuan', 'like', '%' . $search . '%')
                    ->orWhereHas('user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat', function ($query) use ($search) {
                        return $query->where('kelompok_masyarakat', 'like', '%' . $search . '%')->withTrashed();
                    })
                    ->orWhereHas('user_akseslh.data_pic_kelompok_masyarakat', function ($query) use ($search) {
                        return $query->where('nama_pic', 'like', '%' . $search . '%')->withTrashed();
                    })
                    ->orWhereHas('paket_kegiatan.jenis_kegiatan', function ($query) use ($search) {
                        return $query->where('jenis_kegiatan', 'like', '%' . $search . '%')->withTrashed();
                    })
                    ->orWhereHas('paket_kegiatan.master_sub_tematik_kegiatan.tematik_kegiatan', function ($query) use ($search) {
                        return $query->where('tematik_kegiatan', 'like', '%' . $search . '%')->withTrashed();
                    });
            })
            ->when($flag, function ($query) use ($flag) {
                switch ($flag) {
                    case 'Berjalan':
                        return $query->whereIn('flag', ['1', '2', '3', '4', '5', '6', '7', '8', '9', '11']);
                        break;

                    case 'Selesai':
                        return $query->where('flag', '10');
                        break;

                    case 'Ditolak':
                        return $query->where('flag', '20');
                        break;

                    default:
                        # code...
                        return $query->whereIn('flag', ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '20']);
                        break;
                }
            })
            ->when($tahapanKegiatan, function ($query) use ($tahapanKegiatan) {
                $query->whereHas('tahapan', function ($query) use ($tahapanKegiatan) {
                    $query->where('id', $tahapanKegiatan);
                });
            })
            // ->when(isset($input['created_at_awal']) && isset($input['created_at_akhir']), function ($query) use ($input) {
            //     $query->whereBetween('created_at', [Carbon::parse($input['created_at_awal'])->startOfDay(), Carbon::parse($input['created_at_akhir'])->endOfDay()]);
            // })
            // ->when(
            //     isset($input['tanggal_awal_kegiatan']) && isset($input['tanggal_akhir_kegiatan']),
            //     function ($query) use ($input) {
            //         $tanggalAwal = $input['tanggal_awal_kegiatan'];
            //         $tanggalAkhir = $input['tanggal_akhir_kegiatan'];
            //         $query->where(
            //             function ($q) use ($tanggalAwal, $tanggalAkhir) {
            //                 $q->where(function ($sub) use ($tanggalAwal, $tanggalAkhir) {
            //                     $sub->where('tanggal_awal_kegiatan', '<=', $tanggalAwal)
            //                         ->where('tanggal_akhir_kegiatan', '>=', $tanggalAkhir);
            //                 });
            //             }
            //         );
            //     }
            // )
            ->when(
                ($createdAtAwal && $createdAtAkhir && $tanggalAwalKegiatan && $tanggalAkhirKegiatan),
                function ($query) use ($createdAtAwal, $createdAtAkhir, $tanggalAwalKegiatan, $tanggalAkhirKegiatan) {
                    // Semua tanggal terisi -> AND
                    return $query->whereBetween('created_at', [Carbon::parse($createdAtAwal)->startOfDay(), Carbon::parse($createdAtAkhir)->endOfDay()])
                        ->where(function ($q) use ($tanggalAwalKegiatan, $tanggalAkhirKegiatan) {
                            $q->where(function ($sub) use ($tanggalAwalKegiatan, $tanggalAkhirKegiatan) {
                                $sub->where('tanggal_mulai_kegiatan', '<=', $tanggalAwalKegiatan)
                                    ->where('tanggal_akhir_kegiatan', '>=', $tanggalAkhirKegiatan);
                            });
                        });
                },
                function ($query) use ($createdAtAwal, $createdAtAkhir, $tanggalAwalKegiatan, $tanggalAkhirKegiatan) {
                    // Salah satu atau tidak semua terisi -> OR
                    $query->where(function ($q) use ($createdAtAwal, $createdAtAkhir, $tanggalAwalKegiatan, $tanggalAkhirKegiatan) {
                        if ($createdAtAwal && $createdAtAkhir) {
                            $q->orWhereBetween('created_at', [Carbon::parse($createdAtAwal)->startOfDay(), Carbon::parse($createdAtAkhir)->endOfDay()]);
                        }

                        if ($tanggalAwalKegiatan && $tanggalAkhirKegiatan) {
                            $q->orWhere(function ($sub) use ($tanggalAwalKegiatan, $tanggalAkhirKegiatan) {
                                $sub->where('tanggal_mulai_kegiatan', '<=', $tanggalAwalKegiatan)
                                    ->where('tanggal_akhir_kegiatan', '>=', $tanggalAkhirKegiatan);
                            });
                        }
                    });
                }
            )
            ->orderBy('created_at', 'DESC')
            ->paginate((int)$perPage, ['*'], null, $page);

        $result->getCollection()->transform(function ($items, $key) {

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
                'kelompok_masyarakat'       => $items->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat ?? null,
                'jenis_kegiatan'            => $items->paket_kegiatan->jenis_kegiatan->jenis_kegiatan,
                'tahapan_pengajuan'         => tahapanPengajuan($items->flag),
                'status'                    => $items->flag,
                'progres'                   => '',
                'tematik_kegiatan'          => $items->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan,
                'sub_tematik_kegiatan'      => $items->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan,
                'pic_kelompok'              => $items->user_akseslh->data_pic_kelompok_masyarakat->nama_pic ?? null,
                'tanggal_pengajuan'         => $items->created_at->format('Y-m-d H:i:s'),
                'tanggal_kegiatan'          => $items->tanggal_mulai_kegiatan . ' - ' . $items->tanggal_akhir_kegiatan,
                'tanggal_realisasi'         => '',
                'update_terakhir'           => $items->updated_at->format('Y-m-d H:i:s'),
                'lokasi'                    => $items->alamat_kegiatan ?? 'Alamat',
                'dana_yang_disetujui'       => $items->flag >= 3 ? $total : 0,
                'dana_yang_dicairkan'       => $total_penyaluran,
                'sisa_pencairan'            => ($total - $total_penyaluran)
            ];
        });

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $model =   $this->model->newQuery()->with('log_tahapan_pengajuan.catatan_log_tahapan_pengajuan_kegiatan')->where('id', $id)->first();

        if (!$model) return $this->sendError(null, 'Not Found', 422);

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
        })->first();

        // Data Verifikator
        $verifikasi = $model->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
            $q->where(['deskripsi_kegiatan' => 'Verifikasi']);
        })->first();

        $nama_verifikator       = $verifikasi->user_akseslh_admin->email ?? null;
        $tanggal_verifikasi     = $verifikasi->tanggal_selesai ?? null;
        $catatan_verifikator    = $verifikasi->catatan_log_tahapan_pengajuan_kegiatan()->first()->catatan_log ?? null;

        // Data Validator
        $validator = $model->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
            $q->where(['deskripsi_kegiatan' => 'Validasi']);
        })->first();

        $nama_validator     = $validator->user_akseslh_admin->email ?? null;
        $tanggal_validasi   = $validator->tanggal_selesai ?? null;
        $catatan_validator  = $validator->catatan_log_tahapan_pengajuan_kegiatan()->first()->catatan_log ?? null;

        // Data Master Bank Penyaluran Pertama
        $transaksi_penyaluran   = $model->transaksi_penyaluran()->latest()->first();
        $master_data_bank       = $transaksi_penyaluran->master_data_bank->nama_bank ?? null;
        $nomor_rekening         = $transaksi_penyaluran->nomor_rekening ?? null;
        $nama_pemilik_rekening  = $transaksi_penyaluran->nama_pemilik_rekening ?? null;
        $tanggal_penyaluran     = $transaksi_penyaluran->tanggal_penyaluran ?? null;
        $nilai_penyaluran       = $transaksi_penyaluran->nilai_penyaluran ?? null;

        // Model Dokumen
        $files              = $model->document;
        $file_lampiran      = $files->where('group', 'document')->first();
        $file_sk            = $files->where('group', 'document_sk')->first();
        $file_perjanjian    = $files->where('group', 'perjanjian_kerjasama')->first();

        $result = [
            'id'    => $model->id,
            1 => [
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
                'dana_yang_disetujui'       => $total,
                'dana_yang_dicairkan'       => $total_penyaluran,
                'sisa_pencairan'            => ($total - $total_penyaluran),
                'nama_verifikator'          => $nama_verifikator,
                'tanggal_verifikasi'        => $tanggal_verifikasi,
                'catatan_verifikator'       => $catatan_verifikator,
                'nama_validator'            => $nama_validator,
                'tanggal_validasi'          => $tanggal_validasi,
                'catatan_validator'         => $catatan_validator,
                'lampiran'                  => $file_lampiran,
                'sk'                        => $file_sk,
            ],
            2 => [
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
                'dana_yang_disetujui'       => $total,
                'dana_yang_dicairkan'       => $total_penyaluran,
                'file_perjanjian'           => $file_perjanjian,
            ],
            3   => [
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
                'master_data_bank'          => $master_data_bank,
                'nomor_rekening'            => $nomor_rekening,
                'nama_pemilik_rekening'     => $nama_pemilik_rekening,
                'tanggal_penyaluran'        => $tanggal_penyaluran,
                'nilai_penyaluran'          => $nilai_penyaluran,
                'dana_yang_disetujui'       => $total,
                'dana_yang_dicairkan'       => $total_penyaluran,
            ],
            4   => [
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
                'dana_yang_disetujui'       => $total,
                'dana_yang_dicairkan'       => $total_penyaluran,
                'indikator_laporan_kegiatan'    => $model->indikator_laporan_kegiatan->transform(function ($item, $key) {
                    return [
                        'nama_indikator'    => $item->master_data_indikator_laporan->nama_indikator,
                        'nilai_laporan'     => $item->nilai_laporan,
                        'satuan'            => $item->master_data_indikator_laporan->satuan
                    ];
                }),
                'dokumen_file'              => $laporan_kegiatan_termin_1->document_file,
            ],
            5 => [
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
                'master_data_bank'          => $master_data_bank,
                'nomor_rekening'            => $nomor_rekening,
                'nama_pemilik_rekening'     => $nama_pemilik_rekening,
                'tanggal_penyaluran'        => $tanggal_penyaluran,
                'nilai_penyaluran'          => $nilai_penyaluran,
                'dana_yang_disetujui'       => $total,
                'dana_yang_dicairkan'       => $total_penyaluran,
            ],
            6 => [
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
                'master_data_bank'          => $master_data_bank,
                'nomor_rekening'            => $nomor_rekening,
                'nama_pemilik_rekening'     => $nama_pemilik_rekening,
                'tanggal_penyaluran'        => $tanggal_penyaluran,
                'nilai_penyaluran'          => $nilai_penyaluran,
                'dana_yang_disetujui'       => $total,
                'dana_yang_dicairkan'       => $total_penyaluran,
            ]

        ];

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $data = $this->model->newQuery()->create([
                'jenis_kegiatan'       =>  $data['jenis_kegiatan'],
                'short_id'                      =>  $data['short_id'],
                'code_id'                      =>  $data['code_id'],
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

        \DB::beginTransaction();

        try {

            $read->jenis_kegiatan    =   $data['jenis_kegiatan'];
            $read->short_id                     =   $data['short_id'];
            $read->code_id                     =   $data['code_id'];
            $read->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
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

    protected function switchLang($search = null, $page = null, $perPage = null)
    {
        $result  = $this->model->newQuery()
            // ->where('is_publish', true)
            // ->when($search, function ($query, $search) {
            //     return $query->where('title', 'like', '%' . $search . '%');
            // })
            ->orderBy('created_at', 'DESC')
            ->paginate((int)$perPage, ['*'], null, $page);

        $result->getCollection()->transform(function ($items, $key) {
            $total = 0;

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
                'dana_yang_disetujui'       => $items->flag >= 3 ? $total : 0,
                'dana_yang_dicairkan'       => 0,
                'tanggal_kegiatan'          => $items->tanggal_mulai_kegiatan,
            ];
        });

        return $result;
    }

    public function apiLang($id, $lang = 'ID')
    {
        $model =   $this->model->newQuery()->where('is_publish', true)->find($id);

        if (!$model)  return $this->sendError(null, 'Not Published');

        if ($lang === 'ID') {
            $result =   [
                'id'            => $model->id,
                'title'         => $model->title_id,
                'desc'          => $model->desc_id,
                'lastUpdate'    => $model->created_at
            ];
        } else {
            $result =   [
                'id'            => $model->id,
                'title'         => $model->title_en,
                'desc'          => $model->desc_en,
                'lastUpdate'    => $model->created_at
            ];
        }

        return $this->sendSuccess($result);
    }

    public function searchLang($lang = 'ID', $search = null)
    {
        if ($lang === 'ID') {

            $result  = $this->model->newQuery()
                ->when($search, function ($query, $search) {
                    return $query->where('title_id', 'like', '%' . $search . '%')
                        ->orWhere('desc_id', 'like', '%' . $search . '%');
                })
                ->where('is_publish', true)
                ->orderBy('created_at', 'DESC')
                ->get();

            $result->transform(function ($items, $key) {
                return [
                    'type'          => 'CAREER',
                    'id'            => $items->id,
                    'title'         => $items->title_id,
                    'desc'          => $items->desc_id,
                    'lastUpdate'    => $items->created_at
                ];
            });
        } else {

            $result  = $this->model->newQuery()
                ->when($search, function ($query, $search) {
                    return $query->where('title_en', 'like', '%' . $search . '%')
                        ->orWhere('desc_en', 'like', '%' . $search . '%');
                })
                ->where('is_publish', true)
                ->orderBy('created_at', 'DESC')
                ->get();

            $result->transform(function ($items, $key) {
                return [
                    'type'          => 'CAREER',
                    'id'            => $items->id,
                    'title'         => $items->title_en,
                    'desc'          => $items->desc_en,
                    'lastUpdate'    => $items->created_at
                ];
            });
        }
        return $result;
    }
}
