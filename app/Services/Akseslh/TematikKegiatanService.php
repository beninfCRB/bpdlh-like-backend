<?php


namespace App\Services\Akseslh;

use App\Models\File as FileTable;
use App\Services\AppService;
use App\Models\TematikKegiatan;
use App\Services\FileUploadService;
use App\Services\AppServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class TematikKegiatanService extends AppService implements AppServiceInterface
{
    protected $fileUploadService;
    protected $fileTable;

    public function __construct(
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        TematikKegiatan $model
    ) {
        $this->fileUploadService    =   $fileUploadService;
        $this->fileTable            =   $fileTable;
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

    public function getAllAttr()
    {
        $result  = $this->model->newQuery()
            ->orderBy('short_id', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'                    => $items->id,
                'tematik_kegiatan'      => $items->tematik_kegiatan,
            ];
        });

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result =   $this->model->newQuery()->with('image')->find($id);
        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $tematik_kegiatan = $this->model->newQuery()->create([
                'tematik_kegiatan'      =>  $data['tematik_kegiatan'],
                'short_id'              =>  $data['short_id'],
                'code_id'              =>  $data['code_id'],
                'deskripsi_tematik'     =>  $data['deskripsi_tematik'],
                'flag'                  => 1,
            ]);

            // upload banner image
            $upload = $this->fileUploadService->handleImage($data['fileImage'])->saveToDb('image');

            if (!empty($upload)) {
                $image = $this->fileTable->newQuery()->find($upload->id);
                $image->update([
                    'fileable_type' => get_class($tematik_kegiatan),
                    'fileable_id'   => $tematik_kegiatan->id,
                ]);
            }

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

            $read->tematik_kegiatan     =   $data['tematik_kegiatan'];
            $read->short_id             =   $data['short_id'];
            $read->code_id              =   $data['code_id'];
            $read->deskripsi_tematik    =   $data['deskripsi_tematik'];
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

    public function getApiAll()
    {
        $result  = $this->model->newQuery()
            // ->where('is_publish', true)
            ->orderBy('short_id', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'                => $items->id,
                'tematik_kegiatan'  => $items->tematik_kegiatan,
                'deskripsi_tematik' => $items->deskripsi_tematik,
                'image'             => $items->image
            ];
        });

        return $this->sendSuccess($result);
    }
}
