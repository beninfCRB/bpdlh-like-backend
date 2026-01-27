<?php


namespace App\Services\Akseslh;

use App\Services\AppService;
use App\Models\File as FileTable;
use App\Models\SubTematikKegiatan;
use App\Models\MasterSubTematikKegiatan;
use App\Services\FileUploadService;
use App\Services\AppServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class SubTematikKegiatanService extends AppService implements AppServiceInterface
{
    protected $modelMasterSubTematikKegiatan;
    protected $fileUploadService;
    protected $fileTable;

    public function __construct(
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        SubTematikKegiatan $model,
        MasterSubTematikKegiatan $modelMasterSubTematikKegiatan
    ) {
        $this->fileUploadService    =   $fileUploadService;
        $this->fileTable            =   $fileTable;
        $this->modelMasterSubTematikKegiatan = $modelMasterSubTematikKegiatan;
        parent::__construct($model);
    }

    public function getAll()
    {
        $model = $this->model->query()->withTrashed()->orderBy('short_id', 'ASC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function getPaginated($search = null, $page = null, $perPage = null, $lang = null)
    {
        $result =   $this->switchLang($search, $page, $perPage, $lang);

        return $this->sendSuccess($result);
    }

    public function getAllAttr()
    {
        $result = Cache::remember('sub_tematik_kegiatan', now()->addDays(7), function () {
            $data  = $this->model->newQuery()
                ->orderBy('short_id', 'ASC')
                ->get();

            return $data->transform(function ($items, $key) {
                return [
                    'id'                    => $items->id,
                    'sub_tematik_kegiatan'      => $items->sub_tematik_kegiatan,
                ];
            });
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
                'sub_tematik_kegiatan'      =>  $data['sub_tematik_kegiatan'],
                'short_id'                  =>  $data['short_id'],
                'code_id'                  =>  $data['code_id'],
                'deskripsi_tematik'         =>  $data['deskripsi_tematik'],
                'flag'                      => 1,
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
            Cache::forget('sub_tematik_kegiatan');
            return $this->sendSuccess($tematik_kegiatan);
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

            $read->sub_tematik_kegiatan     =   $data['sub_tematik_kegiatan'];
            $read->short_id                 =   $data['short_id'];
            $read->code_id                  =   $data['code_id'];
            $read->deskripsi_tematik        =   $data['deskripsi_tematik'];
            $read->save();

            \DB::commit(); // commit the changes
            Cache::forget('sub_tematik_kegiatan');
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
            Cache::forget('sub_tematik_kegiatan');
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function restore($id)
    {
        $read   =   $this->model->newQuery()->withTrashed()->find($id);
        try {
            $read->restore();
            \DB::commit(); // commit the changes
            Cache::forget('sub_tematik_kegiatan');
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function getApiAll($data)
    {
        $result = Cache::remember('sub_tematik_kegiatan', now()->addDays(7), function () use ($data) {
            $data  = $this->modelMasterSubTematikKegiatan->newQuery()
                ->with(['sub_tematik_kegiatan.image'])
                ->where(['tematik_kegiatan_id' => $data['tematik_kegiatan_id']])
                ->orderBy('short_id', 'ASC')
                ->get();

            if (!$data) return $this->sendError(null, 'Not Found', 422);

            $data->transform(function ($items, $key) {
                if ($items->sub_tematik_kegiatan != null) {
                    return [
                        'id'                    => $items->sub_tematik_kegiatan->id ?? null,
                        'tematik_kegiatan_id'   => $items->tematik_kegiatan_id ?? null,
                        'sub_tematik_kegiatan'  => $items->sub_tematik_kegiatan->sub_tematik_kegiatan ?? null,
                        'image'                 => $items->sub_tematik_kegiatan->image ?? null
                    ];
                }
            });
            $data = $data->toArray();
            $data = array_filter($data, function ($value) {
                return !is_null($value);
            });

            // Menyusun ulang array (karena array_filter bisa mengubah indeks)
            $data = array_values($data);
        });

        return $this->sendSuccess($result);
    }
}
