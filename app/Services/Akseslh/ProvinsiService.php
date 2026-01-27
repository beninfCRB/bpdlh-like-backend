<?php


namespace App\Services\Akseslh;


use App\Models\Province;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class ProvinsiService extends AppService implements AppServiceInterface
{

    public function __construct(Province $model)
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
        $cacheKey = 'provinsi_' . $id;
        $result = Cache::remember($cacheKey, now()->addDays(7), function () use ($id) {
            // Fetch the province by ID with related cities
            return $this->model->newQuery()->with(['kota' => function ($query) {
                $query->orderBy('name', 'ASC');
            }])->find($id);
        });

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $data = $this->model->newQuery()->create([
                'jenis_kelompok_masyarakat'     =>  $data['jenis_kelompok_masyarakat'],
                'short_id'                      =>  $data['short_id'],
                'flag'                          =>  1,
            ]);

            \DB::commit(); // commit the changes
            Cache::forget('provinsi');
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

            $read->jenis_kelompok_masyarakat    =   $data['jenis_kelompok_masyarakat'];
            $read->short_id                     =   $data['short_id'];
            $read->save();

            \DB::commit(); // commit the changes
            Cache::forget('provinsi');
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
            Cache::forget('provinsi');
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function apiGetAll()
    {
        $result = Cache::remember('provinsi', now()->addDays(7), function () {

            $data  = $this->model->newQuery()
                ->orderBy('name', 'ASC')
                ->get();

            return $data->transform(function ($items, $key) {
                return [
                    'id'    => $items->id,
                    'code'  => $items->code,
                    'name'  => $items->name,
                ];
            });
        });

        return $this->sendSuccess($result);
    }
}
