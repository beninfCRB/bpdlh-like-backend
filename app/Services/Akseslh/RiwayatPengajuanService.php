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

    public function getPaginated($flag = null, $search = null, $page = null, $perPage = null, $tahapanKegiatan = null)
    {
        $tahapan = tahapanPengajuanFlag($tahapanKegiatan);

        $result  = $this->model->newQuery()
            ->when($search, function ($query) use ($search) {
                return $query->where('nomor_pengajuan', 'like', '%' . $search . '%')
                    ->orWhereHas('user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat', function ($query) use ($search) {
                        return $query->where('kelompok_masyarakat', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('user_akseslh.data_pic_kelompok_masyarakat', function ($query) use ($search) {
                        return $query->where('nama_pic', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('paket_kegiatan.jenis_kegiatan', function ($query) use ($search) {
                        return $query->where('jenis_kegiatan', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('paket_kegiatan.master_sub_tematik_kegiatan.tematik_kegiatan', function ($query) use ($search) {
                        return $query->where('tematik_kegiatan', 'like', '%' . $search . '%');
                    });
            })
            ->when($flag, function ($query) use ($flag) {
                switch ($flag) {
                    case 'Berjalan':
                        return $query->whereIn('flag', ['1', '2', '3', '4', '5', '6', '7', '8', '9']);
                        break;

                    case 'Selesai':
                        return $query->where('flag', '10');
                        break;

                    case 'Ditolak':
                        return $query->where('flag', '20');
                        break;

                    default:
                        # code...
                        return $query->whereIn('flag', ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '20']);
                        break;
                }
            })
            ->when($tahapan, function ($query) use ($tahapan) {
                return $query->where('flag', $tahapan);
            })
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
                'kelompok_masyarakat'       => $items->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat,
                'jenis_kegiatan'            => $items->paket_kegiatan->jenis_kegiatan->jenis_kegiatan,
                'tahapan_pengajuan'         => tahapanPengajuan($items->flag),
                'status'                    => $items->flag,
                'progres'                   => '',
                'tematik_kegiatan'          => $items->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan,
                'sub_tematik_kegiatan'      => $items->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan,
                'pic_kelompok'              => $items->user_akseslh->data_pic_kelompok_masyarakat->nama_pic,
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
        $model =   $this->model->newQuery()->where('id', $id)->first();
        dd($model->log_tahapan_pengajuan->catatan_log_tahapan_pengajuan_kegiatan);
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

        $result = [
            'id'    => $model->id,
            'tahap_1' => [
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
                'nama_verifikator'          => $model->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where(['deskripsi_kegiatan' => 'Verifikasi']);
                })->first()->user_akseslh_admin->email ?? null,
                'tanggal_verifikasi'        => $model->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where(['deskripsi_kegiatan' => 'Verifikasi']);
                })->first()->tanggal_selesai ?? null,
                'nama_validator'          => $model->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where(['deskripsi_kegiatan' => 'Validasi']);
                })->first()->user_akseslh_admin->email ?? null,
                'tanggal_validasi'        => $model->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where(['deskripsi_kegiatan' => 'Validasi']);
                })->first()->tanggal_selesai ?? null,
                'catatan'               => $model->log_tahapan_pengajuan->catatan_log_tahapan_pengajuan_kegiatan
            ],
            'tahap_2' => [
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
            ],
            'tahap_3'   => [
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
                'master_data_bank'          => $model->transaksi_penyaluran()->latest()->first()->master_data_bank->nama_bank,
                'nomor_rekening'            => $model->transaksi_penyaluran()->latest()->first()->nomor_rekening,
                'nama_pemilik_rekening'     => $model->transaksi_penyaluran()->latest()->first()->nama_pemilik_rekening,
                'nama_pemilik_rekening'     => $model->transaksi_penyaluran()->latest()->first()->nama_pemilik_rekening,
                'tanggal_penyaluran'        => $model->transaksi_penyaluran()->latest()->first()->tanggal_penyaluran,
                'nilai_penyaluran'          => $model->transaksi_penyaluran()->latest()->first()->nilai_penyaluran,
                'dana_yang_disetujui'       => $total,
                'dana_yang_dicairkan'       => $total_penyaluran,
            ],
            'tahap_4'   => [
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
            ],

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
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
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
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
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
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
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
