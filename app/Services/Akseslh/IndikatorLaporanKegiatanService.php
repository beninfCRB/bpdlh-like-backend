<?php


namespace App\Services\Akseslh;

use App\Services\AppService;
use App\Models\PengajuanKegiatan;
use App\Services\AppServiceInterface;
use App\Models\IndikatorLaporanKegiatan;
use App\Models\TahapanPengajuanKegiatan;
use Yajra\DataTables\Facades\DataTables;
use App\Models\LogTahapanPengajuanKegiatan;
use App\Models\DetailLogTahapanPengajuanKegiatan;
use App\Notifications\LaporanNotification;

class IndikatorLaporanKegiatanService extends AppService implements AppServiceInterface
{
    protected $modelPengajuanKegiatan;
    protected $modelLogTahapanPengajuanKegiatan;
    protected $modelTahapanPengajuanKegiatan;
    protected $modelDetailLogTahapanPengajuanKegiatan;

    public function __construct(
        IndikatorLaporanKegiatan $model,
        PengajuanKegiatan $modelPengajuanKegiatan,
        LogTahapanPengajuanKegiatan $modelLogTahapanPengajuanKegiatan,
        TahapanPengajuanKegiatan $modelTahapanPengajuanKegiatan,
        DetailLogTahapanPengajuanKegiatan $modelDetailLogTahapanPengajuanKegiatan
    ) {
        parent::__construct($model);
        $this->modelPengajuanKegiatan           = $modelPengajuanKegiatan;
        $this->modelLogTahapanPengajuanKegiatan = $modelLogTahapanPengajuanKegiatan;
        $this->modelTahapanPengajuanKegiatan    = $modelTahapanPengajuanKegiatan;
        $this->modelDetailLogTahapanPengajuanKegiatan   = $modelDetailLogTahapanPengajuanKegiatan;
    }

    public function getAll()
    {
        $model = $this->model->query()->with('jenis')->orderBy('created_at', 'DESC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
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
        $pengajuan_kegiatan = $this->modelPengajuanKegiatan->find($data['pengajuan_kegiatan_id']);

        \DB::beginTransaction();

        try {
            $data = $this->model->newQuery()->create([
                'master_data_indikator_laporan_id'  =>  $data['master_data_indikator_laporan_id'],
                'pengajuan_kegiatan_id'             =>  $data['pengajuan_kegiatan_id'],
                'nilai_laporan'                     =>  $data['nilai_laporan'],
                'flag'                              => 1,
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
        $read   =   $this->modelPengajuanKegiatan->newQuery()->find($id);

        if (!$read) {
            \Sentry\captureMessage('Validate Message: ' . $data['user']->email . ' Pengajuan tidak ditemukan', \Sentry\Severity::warning());
            return $this->sendError(null, 'Not found', 422);
        }

        if ($read->flag != 5) {
            \Sentry\captureMessage('Validate Message: ' . $data['user']->email . ' Pengajuan tidak dalam tahapan yang benar', \Sentry\Severity::warning());
            return $this->sendError(null, 'Invalid data', 422);
        }

        \DB::beginTransaction();

        try {

            // cek apabila indikator laporan ada isinya
            if (isset($read->indikator_laporan_kegiatan) && count($read->indikator_laporan_kegiatan) > 0) {
                # code...
                $read->indikator_laporan_kegiatan()->delete();
            }

            foreach ($data['indikator_kegiatan'] as $item) {

                $dataIndikator[] = [
                    'master_data_indikator_laporan_id'  => $item['master_data_indikator_laporan_id'],
                    'pengajuan_kegiatan_id'             => $id,
                    'nilai_laporan'                     => $item['nilai_laporan']
                ];
            }

            $read->indikator_laporan_kegiatan()->saveMany(
                collect($dataIndikator)->map(function ($dataIndikator) {
                    return new IndikatorLaporanKegiatan($dataIndikator);
                })
            );


            $read->tanggal_mulai_kegiatan = $data['tanggal_mulai_kegiatan'];
            $read->tanggal_akhir_kegiatan = $data['tanggal_akhir_kegiatan'];
            $read->longitude = $data['longitude'];
            $read->latitude = $data['latitude'];

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

    public function apiGetByIdJenisKelompokMasyarakat($id)
    {
        $result  = $this->model->newQuery()
            ->where('jenis_kelompok_masyarakat_id', $id)
            ->orderBy('created_at', 'DESC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'                                    => $items->id,
                'jenis_kelompok_masyarakat_id'          => $items->jenis_kelompok_masyarakat_id,
                'kelompok_masyarakat'                   => $items->kelompok_masyarakat,
                'flag'                                  => $items->flag,
            ];
        });

        return $this->sendSuccess($result);
    }

    public function apiGetAll()
    {
        $result  = $this->model->newQuery()
            ->where('flag', 1)
            ->orderBy('created_at', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'                            => $items->id,
                'kelompok_masyarakat'           => $items->kelompok_masyarakat,
            ];
        });

        return $this->sendSuccess($result);
    }
}
