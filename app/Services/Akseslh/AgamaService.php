<?php


namespace App\Services\Akseslh;


use App\Models\Agama;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class AgamaService extends AppService implements AppServiceInterface
{

    public function __construct(Agama $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {
        $model = $this->model->query()->orderBy('created_at', 'ASC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function getAllAttr()
    {
        $result = Cache::remember('agama', now()->adddays(7), function () {
            $data  = $this->model->newQuery()
                ->orderBy('created_at', 'ASC')
                ->get();

            return $data->transform(function ($items, $key) {
                return [
                    'id'        => $items->id,
                    'agama'     => $items->agama,
                ];
            });
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

            $data = $this->model->newQuery()->create([
                'agama'       =>  $data['agama'],
                'flag'        => 1,
            ]);

            \DB::commit(); // commit the changes
            Cache::forget('agama');

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

            $read->agama    =   $data['agama'];
            $read->save();

            \DB::commit(); // commit the changes
            Cache::forget('agama');

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
            Cache::forget('agama');

            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }
}
