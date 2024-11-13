<?php


namespace App\Services\Akseslh;

use App\Models\UserAkseslh;
use App\Services\AppService;
use App\Models\File as FileTable;
use App\Services\FileUploadService;
use App\Services\AppServiceInterface;
use App\Models\MasterUserJenisKelompok;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class MasterUserJenisKelompokService extends AppService implements AppServiceInterface
{
    protected $userAkseslh;
    protected $fileUploadService;
    protected $fileTable;

    public function __construct(
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        MasterUserJenisKelompok $model,
        UserAkseslh $userAkseslh
    ) {
        $this->fileUploadService    =   $fileUploadService;
        $this->fileTable            =   $fileTable;
        $this->userAkseslh          =   $userAkseslh;
        parent::__construct($model);
    }

    public function getAll()
    {
        $model = $this->model->query()->with(['tematik_kegiatan', 'sub_tematik_kegiatan'])->orderBy('short_id', 'ASC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function getAllUser($id)
    {
        $result = $this->userAkseslh->query()->with('master_user_jenis_kelompok')->where('id', $id)->first();
        $model = $result->master_user_jenis_kelompok()->with('jenis_kelompok_masyarakat');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function getPaginated($search = null, $page = null, $perPage = null, $lang = null)
    {
        $result =   $this->switchLang($search, $page, $perPage, $lang);

        return $this->sendSuccess($result);
    }

    public function getAllAttr()
    {
        $result  = $this->model->newQuery()
            ->orderBy('short_id', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'           => $items->id,
                'tematik'      => $items->tematik_kegiatan->tematik_kegiatan . ' - ' . $items->sub_tematik_kegiatan->sub_tematik_kegiatan,
            ];
        });

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

            $tematik_kegiatan = $this->model->newQuery()->create([
                'user_akseslh_id'       =>  $data['user_akseslh_id'],
                'jenis_kelompok_masyarakat_id'   =>  $data['jenis_kelompok_masyarakat_id'],
            ]);

            \DB::commit(); // commit the changes
            return $this->sendSuccess($tematik_kegiatan);
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

            $read->tematik_kegiatan_id      =   $data['tematik_kegiatan_id'];
            $read->sub_tematik_kegiatan_id  =   $data['sub_tematik_kegiatan_id'];
            $read->short_id                 =   $data['short_id'];
            $read->deskripsi_tematik        =   $data['deskripsi_tematik'];
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

    public function getApiAll($input)
    {
        $result  = $this->model->newQuery()
            ->where(['tematik_kegiatan_id' => $input['tematik_kegiatan_id']])
            ->orderBy('short_id', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'                    => $items->id,
                'tematik_kegiatan_id'   => $items->tematik_kegiatan_id,
                'sub_tematik_kegiatan'  => $items->sub_tematik_kegiatan,
                'image'                 => $items->image
            ];
        });

        return $this->sendSuccess($result);
    }
}
