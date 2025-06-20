<?php


namespace App\Services\Akseslh;


use App\Models\MasterIndikator;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class MasterIndikatorService extends AppService implements AppServiceInterface
{

    public function __construct(MasterIndikator $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {
        $model = $this->model->query()->withTrashed()->orderBy('created_at', 'ASC');

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
                'nama_indikator'     =>  $data['nama_indikator'],
                'satuan'             =>  $data['satuan'],
                'tipe_data'          =>  $data['tipe_data'],
                'flag'               =>  1,
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
        $read   =   $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {

            $read->nama_indikator    =   $data['nama_indikator'];
            $read->satuan            =   $data['satuan'];
            $read->tipe_data         =   $data['tipe_data'];
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

    public function restore($id)
    {
        $read   =   $this->model->newQuery()->withTrashed()->find($id);
        try {
            $read->restore();
            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }
}
