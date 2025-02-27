<?php


namespace App\Services\Akseslh;

use App\Models\LogTahapanPengajuanKegiatan;
use App\Models\PengajuanKegiatan;
use App\Models\TahapanPengajuanKegiatan;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class VerifikasiPengajuanKegiatanService extends AppService implements AppServiceInterface
{
    private $modelTahapanPengajuanKegiatan;

    public function __construct(PengajuanKegiatan $model, TahapanPengajuanKegiatan $modelTahapanPengajuanKegiatan)
    {
        parent::__construct($model);
        $this->modelTahapanPengajuanKegiatan = $modelTahapanPengajuanKegiatan;
    }

    public function getAll()
    {
        $model = $this->model->query()->orderBy('created_at', 'DESC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function getAllAttr($user)
    {
        $result  = $this->model->newQuery()
            // ->whereHas('log_tahapan_pengajuan', function ($q) {
            //     $q->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
            //         $q->where(['deskripsi_kegiatan' => 'Verifikasi']);
            //     })->whereNotNull('tanggal_masuk')
            //         ->whereNull('tanggal_selesai');
            // })
            // ->whereHas('user_akseslh', function ($q) use ($user) {
            //     $q->whereHas('data_pic_kelompok_masyarakat', function ($q) use ($user) {
            //         $q->whereHas('kelompok_masyarakat', function ($q) use ($user) {
            //             $q->whereHas('jenis', function ($q) use ($user) {
            //                 $q->whereIn('jenis_kelompok_masyarakat_id', $user->master_user_jenis_kelompok->pluck('jenis_kelompok_masyarakat_id')->toArray());
            //             });
            //         });
            //     });
            // })
            ->when($user->master_user_jenis_kelompok->isNotEmpty(), function ($query) use ($user) {
                $query->whereHas('user_akseslh', function ($q) use ($user) {
                    $q->whereHas('data_pic_kelompok_masyarakat', function ($q) use ($user) {
                        $q->whereHas('kelompok_masyarakat', function ($q) use ($user) {
                            $q->whereHas('jenis', function ($q) use ($user) {
                                $q->whereIn('jenis_kelompok_masyarakat_id', $user->master_user_jenis_kelompok->pluck('jenis_kelompok_masyarakat_id')->toArray());
                            });
                        });
                    });
                });
            })
            ->with(['paket_kegiatan.jenis_kegiatan' => function ($query) {
                $query->withTrashed(); // Mengambil data yang sudah dihapus soft delete
            }, 'user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat' => function ($query) {
                $query->withTrashed(); // Mengambil data yang sudah dihapus soft delete
            }])
            ->with(['paket_kegiatan.master_sub_tematik_kegiatan.sub_tematik_kegiatan' => function ($query) {
                $query->withTrashed(); // Mengambil data yang sudah dihapus soft delete
            }])
            ->where('flag', 1)
            ->orderBy('created_at', 'ASC')
            ->get();

        $result->transform(function ($items) {
            return [
                'id'                        => $items->id,
                'kelompok_masyarakat'       => $items->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat,
                'tematik_kegiatan'          => $items->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan,
                'sub_tematik_kegiatan'      => $items->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan,
                'judul_pengajuan_kegiatan'  => $items->judul_pengajuan_kegiatan,
                'kegiatan'                  => $items->paket_kegiatan->jenis_kegiatan->jenis_kegiatan . " " . $items->paket_kegiatan->jumlah_peserta . " " . ($items->paket_kegiatan->jumlah_peserta >= 50 ? "Orang" : "Hektare"),
                'jenis_kegiatan'            => $items->paket_kegiatan->jenis_kegiatan->jenis_kegiatan,
                'rencana_kegiatan'          => $items->tanggal_mulai_kegiatan,
                'jumlah'                    => $items->paket_kegiatan->jumlah_peserta . " " . ($items->paket_kegiatan->jumlah_peserta >= 50 ? "Orang" : "Hectare"),
                'tanggal_pengajuan'         => $items->created_at->format('d M Y H:i'),
                'tanggal_akhir_verifikasi'  => Carbon::parse($items->created_at)->locale('id')->addDays(7)->format('d M Y'),
                'kelompok_masyarakat'       => $items->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat,
                'id_pic'                    => $items->user_akseslh->data_pic_kelompok_masyarakat->id,
                'nama_pic'                  => $items->user_akseslh->data_pic_kelompok_masyarakat->nama_pic,
                'email_pic'                 => $items->user_akseslh->data_pic_kelompok_masyarakat->email_pic,
                'lokasi'                    => $items->alamat_kegiatan,
                'nomor_pengajuan'           => $items->nomor_pengajuan,
                'proposal_kegiatan'         => $items->proposal_kegiatan,
                'tujuan_kegiatan'           => $items->tujuan_kegiatan,
                'ruang_lingkup_kegiatan'    => $items->ruang_lingkup_kegiatan,
                'document'                  => $items->document
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
            'nama_pic'                  => $model->user_akseslh->data_pic_kelompok_masyarakat->nama_pic,
            'email_pic'                 => $model->user_akseslh->data_pic_kelompok_masyarakat->email_pic,
            'tematik_kegiatan'          => $model->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan,
            'sub_tematik_kegiatan'      => $model->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan,
            'jenis_kegiatan'            => $model->paket_kegiatan->jenis_kegiatan->jenis_kegiatan,
            'jumlah'                    => $model->paket_kegiatan->jumlah_peserta . " " . ($model->paket_kegiatan->jumlah_peserta >= 50 ? "Orang" : "Hectare"),
            'lokasi'                    => $model->alamat_kegiatan,
            'nomor_pengajuan'           => $model->nomor_pengajuan,
            'paket_kegiatan_id'         => $model->paket_kegiatan->id,
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

        \DB::beginTransaction();

        try {

            if ($read->paket_kegiatan_id != $data['paket_kegiatan_id']) {
                # code...
                $temp = $read->log_tahapan_pengajuan;
                $arrayTemp = $temp->toArray();

                $tahapanPengajuanKegiatan = $this->modelTahapanPengajuanKegiatan->get();

                $dataTahapanPengajuanKegiatan = [];
                foreach ($tahapanPengajuanKegiatan as $item) {
                    # code...
                    if ($item->deskripsi_kegiatan == "Validasi") {
                        # code...
                        $dataTahapanPengajuanKegiatan[] = [
                            'tahapan_pengajuan_kegiatan_id' => $item->id,
                            'tanggal_masuk'                 => isset($arrayTemp[array_search($item->id, $temp->pluck('tahapan_pengajuan_kegiatan_id')->toArray())]['tanggal_masuk']) ?  $arrayTemp[array_search($item->id, $temp->pluck('tahapan_pengajuan_kegiatan_id')->toArray())]['tanggal_masuk'] : null,
                            'tanggal_selesai'               => Carbon::now()->format('Y-m-d'),
                            'flag'                          => 1,
                        ];
                    } else {
                        $dataTahapanPengajuanKegiatan[] = [
                            'tahapan_pengajuan_kegiatan_id' => $item->id,
                            'tanggal_masuk'                 => isset($arrayTemp[array_search($item->id, $temp->pluck('tahapan_pengajuan_kegiatan_id')->toArray())]['tanggal_masuk']) ?  $arrayTemp[array_search($item->id, $temp->pluck('tahapan_pengajuan_kegiatan_id')->toArray())]['tanggal_masuk'] : null,
                            'tanggal_selesai'               => isset($arrayTemp[array_search($item->id, $temp->pluck('tahapan_pengajuan_kegiatan_id')->toArray())]['tanggal_selesai']) ?  $arrayTemp[array_search($item->id, $temp->pluck('tahapan_pengajuan_kegiatan_id')->toArray())]['tanggal_selesai'] : null,
                            'flag'                          => 1,
                        ];
                    }
                }

                $read->log_tahapan_pengajuan()->saveMany(
                    collect($dataTahapanPengajuanKegiatan)->map(function ($tahapanPengajuanKegiatan) {
                        return new LogTahapanPengajuanKegiatan($tahapanPengajuanKegiatan);
                    })
                );
            } else {
            }

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
}
