<?php


namespace App\Services\Akseslh;


use Carbon\Carbon;
use App\Services\AppService;
use App\Models\PengajuanKegiatan;
use App\Models\TransaksiPenyaluran;
use App\Services\AppServiceInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Models\LogTahapanPengajuanKegiatan;

class TransaksiPenyaluranService extends AppService implements AppServiceInterface
{
    protected $pengajuanKegiatan;
    protected $modelLogTahapanPengajuanKegiatan;

    public function __construct(
        TransaksiPenyaluran $model,
        PengajuanKegiatan $pengajuanKegiatan,
        LogTahapanPengajuanKegiatan $modelLogTahapanPengajuanKegiatan
    ) {
        parent::__construct($model);
        $this->pengajuanKegiatan = $pengajuanKegiatan;
        $this->modelLogTahapanPengajuanKegiatan         = $modelLogTahapanPengajuanKegiatan;
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

    public function apiGetAll()
    {
        $result  = $this->pengajuanKegiatan->newQuery()
            // ->whereHas('log_tahapan_pengajuan', function ($q) {
            //     $q->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
            //         $q->where(['deskripsi_kegiatan' => 'Verifikasi']);
            //     })->whereNotNull('tanggal_masuk')
            //         ->whereNull('tanggal_selesai');
            // })
            ->where('flag', 4)
            ->orderBy('created_at', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
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

    public function apiGetPengajuanKegiatan()
    {
        $result  = $this->pengajuanKegiatan->newQuery()
            ->where('flag', 4)
            ->orderBy('created_at', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            $total = 0;

            foreach ($items->rab_pengajuan_paket_kegiatans as $i) {
                # code...
                $total += ($i->qty * $i->harga_unit);
            }

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
                'dana_yang_disetujui'       => $total,
                'dana_yang_dicairkan'       => 0,
                'nama_verifikator'          => $items->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where(['deskripsi_kegiatan' => 'Verifikasi']);
                })->first()->user_akseslh_admin->email ?? null,
                'tanggal_verifikasi'        => $items->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where(['deskripsi_kegiatan' => 'Verifikasi']);
                })->first()->tanggal_selesai ?? null,
                'nama_validator'          => $items->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where(['deskripsi_kegiatan' => 'Validasi']);
                })->first()->user_akseslh_admin->email ?? null,
                'tanggal_validasi'        => $items->log_tahapan_pengajuan()->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where(['deskripsi_kegiatan' => 'Validasi']);
                })->first()->tanggal_selesai ?? null,
                'document'                  => $items->document
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
        $result =   $this->model->newQuery()->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $newData = $this->model->newQuery()->create([
                'master_data_bank_id'   =>  $data['master_data_bank_id'],
                'pengajuan_kegiatan_id' =>  $data['pengajuan_kegiatan_id'],
                'nomor_rekening'        =>  $data['nomor_rekening'],
                'nama_pemilik_rekening' =>  $data['nama_pemilik_rekening'],
                'nilai_penyaluran'      =>  $data['nilai_penyaluran'],
                'tanggal_penyaluran'    =>  $data['tanggal_penyaluran'],
                'flag'                  =>  1,
                'username'              =>  $data['username'],
            ]);

            $informasiPencairanDana = $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $data['pengajuan_kegiatan_id'])
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Informasi Pencairan Dana');
                })
                ->first();

            if (!$informasiPencairanDana->tanggal_selesai) {
                # code...
                $informasiPencairanDana->update(['tanggal_selesai' => date("Y-m-d")]);
                $informasiPencairanDana->save();

                $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $data['pengajuan_kegiatan_id'])
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Konfirmasi Pencairan Dana Termin 1');
                    })
                    ->update(['tanggal_masuk' => date("Y-m-d")]);
            }

            $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $data['pengajuan_kegiatan_id'])
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Konfirmasi Pencairan Dana Termin 1');
                })
                ->update(['tanggal_selesai' => date("Y-m-d"), 'user_akseslh_id' => $data['username']]);

            $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $data['pengajuan_kegiatan_id'])
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Laporan Kegiatan');
                })
                ->update(['tanggal_masuk' => date("Y-m-d")]);

            $newData->pengajuan_kegiatan->flag = 5;
            $newData->pengajuan_kegiatan->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($newData);
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
}
