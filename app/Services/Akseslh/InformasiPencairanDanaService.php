<?php


namespace App\Services\Akseslh;


use App\Models\InformasiPencairanDana;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class InformasiPencairanDanaService extends AppService implements AppServiceInterface
{

    public function __construct(InformasiPencairanDana $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {
        $model = $this->model->query()->orderBy('short_id', 'ASC');

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
        \DB::beginTransaction();
        try {

            $data = $this->model->newQuery()->create([
                'master_data_bank_id'               => $data['master_data_bank_id'],
                'log_tahapan_pengajuan_kegiatan_id' => $data['log_tahapan_pengajuan_kegiatan_id'],
                'nama_cabang'                       => $data['nama_cabang'],
                'jenis_rekening'                    => $data['jenis_rekening'],
                'nama_pemilik_rekening'             => $data['nama_pemilik_rekening'],
                'nomor_rekening'                    => $data['nomor_rekening']
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
        echo "<pre>";
        print_r($read);
        exit;
        \DB::beginTransaction();

        try {

            $read->master_data_bank_id                  =   $data['master_data_bank_id'];
            $read->log_tahapan_pengajuan_kegiatan_id    =   $data['log_tahapan_pengajuan_kegiatan_id'];
            $read->nama_cabang                          =   $data['nama_cabang'];
            $read->jenis_rekening                       =   $data['jenis_rekening'];
            $read->nama_pemilik_rekening                =   $data['nama_pemilik_rekening'];
            $read->nomor_rekening                       =   $data['nomor_rekening'];
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

    public function apiGetAll()
    {
        $result  = $this->model->newQuery()
            ->where('flag', true)
            ->orderBy('short_id', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'                            => $items->id,
                'jenis_kelompok_masyarakat'     => $items->jenis_kelompok_masyarakat,
                'short_id'                      => $items->short_id,
                'flag'                          => $items->flag,
            ];
        });

        return $this->sendSuccess($result);
    }
}
