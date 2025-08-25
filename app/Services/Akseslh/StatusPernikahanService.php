<?php


namespace App\Services\Akseslh;


use App\Services\AppService;
use App\Models\StatusPernikahan;
use App\Services\AppServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class StatusPernikahanService extends AppService implements AppServiceInterface
{

    public function __construct(StatusPernikahan $model)
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
        $result = Cache::remember('status_pernikahan', now()->adddays(7), function () {
            $data  = $this->model->newQuery()
                ->orderBy('created_at', 'ASC')
                ->get();

            return $data->transform(function ($items, $key) {
                return [
                    'id'                 => $items->id,
                    'status_pernikahan'     => $items->status_pernikahan,
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
                'status_pernikahan'    =>  $data['status_pernikahan'],
                'flag'                 => 1,
            ]);

            \DB::commit(); // commit the changes
            Cache::forget('status_pernikahan');

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

            $read->status_pernikahan    =   $data['status_pernikahan'];
            $read->save();

            \DB::commit(); // commit the changes
            Cache::forget('status_pernikahan');

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
            Cache::forget('status_pernikahan');

            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }
}
