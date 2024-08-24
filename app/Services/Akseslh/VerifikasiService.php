<?php


namespace App\Services\Akseslh;


use App\Models\PengajuanKegiatan;
use App\Models\TahapanPengajuanKegiatan;
use App\Models\LogTahapanPengajuanKegiatan;
use App\Models\CatatanLogTahapanPengajuanKegiatan;
use App\Notifications\VerifikasiValidasiDitolakNotification;
use App\Notifications\VerifikasiValidasiNotification;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use App\Services\EmailPhpService;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class VerifikasiService extends AppService implements AppServiceInterface
{
    protected $modelTahapanPengajuanKegiatan;
    protected $modelLogTahapanPengajuanKegiatan;
    protected $modelCatatanLogTahapanPengajuanKegiatan;
    protected $emailService;

    public function __construct(
        PengajuanKegiatan $model,
        TahapanPengajuanKegiatan $modelTahapanPengajuanKegiatan,
        LogTahapanPengajuanKegiatan $modelLogTahapanPengajuanKegiatan,
        CatatanLogTahapanPengajuanKegiatan $modelCatatanLogTahapanPengajuanKegiatan,
        EmailPhpService $emailPhpService
    ) {
        parent::__construct($model);
        $this->modelTahapanPengajuanKegiatan = $modelTahapanPengajuanKegiatan;
        $this->modelLogTahapanPengajuanKegiatan = $modelLogTahapanPengajuanKegiatan;
        $this->modelCatatanLogTahapanPengajuanKegiatan = $modelCatatanLogTahapanPengajuanKegiatan;
        $this->emailService = $emailPhpService;
    }

    public function getAll()
    {
        $model = $this->model->query()->orderBy('created_at', 'DESC');

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
        return $this->sendSuccess();
    }

    public function update($id, $data)
    {
        $read   =   $this->model->newQuery()->find($id);

        // dd($read->rab_pengajuan_paket_kegiatans);

        $total = 0;
        foreach ($read->rab_pengajuan_paket_kegiatans as $items) {
            # code...
            $total += ($items->qty * $items->harga_unit);
        }

        if (!$read) return $this->sendError(null, 'Not Found');

        \DB::beginTransaction();

        try {

            $idLog = $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $id)
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Verifikasi');
                })->first()->id;

            $this->modelCatatanLogTahapanPengajuanKegiatan->newQuery()
                ->create([
                    'log_tahapan_pengajuan_kegiatan_id' => $idLog,
                    'catatan_log'                       => $data['catatan_log']
                ]);

            // $dataTahapanPengajuanKegiatan = $this->modelTahapanPengajuanKegiatan->newQuery()
            //     ->orderBy('created_at', 'DESC')->get();

            // $dataLogTahapanPengajuanKegiatan = $this->modelLogTahapanPengajuanKegiatan->newQuery()
            //     ->with(['tahapan_pengajuan_kegiatan'])
            //     ->where('pengajuan_kegiatan_id', $id)
            //     ->orderBy('created_at', 'DESC')->get();
            if ($data['status'] == 0) {
                // $read->user_akseslh = $data['user_akselh_id'];
                $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Verifikasi');
                    })
                    ->update(['tanggal_selesai' => date("Y-m-d"), 'user_akseslh_id' => $data['user_akselh_id']]);
                $read->flag = '20';
                $read->save();

                $dataSend = array(
                    'nomor_pengajuan' => $read->nomor_pengajuan,
                    'catatan_log'       => $data['catatan_log'],
                    'keterangan'      => 'Ditolak',
                    'status'          => '20'
                );
                $read->user_akseslh->notify(new VerifikasiValidasiDitolakNotification($read->nomor_pengajuan, $read->user_akseslh->data_pic_kelompok_masyarakat->nama_pic, $total, $data['catatan_log']));

                $this->emailService->verifikasiValidasiDitolak($read->user_akseslh, 'Pengajuan Ditolak', $dataSend, null, 'verifikasi-pengajuan-kegiatan-ditolak');
            } else {

                // Update data langsung berdasarkan pengajuan_kegiatan_id
                $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Verifikasi');
                    })
                    ->update(['tanggal_selesai' => date("Y-m-d"), 'user_akseslh_id' => $data['user_akselh_id']]);

                $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Validasi');
                    })
                    ->update(['tanggal_masuk' => date("Y-m-d")]);

                $read->flag = '2';
                $read->save();

                $dataSend = array(
                    'nomor_pengajuan' => $read->nomor_pengajuan,
                    'keterangan'      => 'Disetujui',
                    'status'          => '2'
                );

                $read->user_akseslh->notify(new VerifikasiValidasiNotification($read->nomor_pengajuan, $read->user_akseslh->data_pic_kelompok_masyarakat->nama_pic, $total));
            }


            \DB::commit(); // commit the changes
            return $this->sendSuccess($dataSend);
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

    public function updatePublish($id, $isPublish): object
    {
        $read = $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {
            $read->is_publish       =   $isPublish;
            $read->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    protected function switchLang($search = null, $page = null, $perPage = null, $lang = 'ID')
    {
        $result  = $this->model->newQuery()
            ->where('is_publish', true)
            ->when($search, function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'DESC')
            ->paginate((int)$perPage, ['*'], null, $page);

        if ($lang === 'ID') {
            $result->getCollection()->transform(function ($items, $key) {
                return [
                    'id'            => $items->id,
                    'title'         => $items->title_id,
                    'desc'          => $items->desc_id,
                    'lastUpdate'    => $items->created_at,
                ];
            });
        } else {
            $result->getCollection()->transform(function ($items, $key) {
                return [
                    'id'            => $items->id,
                    'title'         => $items->title_en,
                    'desc'          => $items->desc_en,
                    'lastUpdate'    => $items->created_at,
                ];
            });
        }
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
