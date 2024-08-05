<?php


namespace App\Services\Akseslh;


use App\Models\District;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class KecamatanService extends AppService implements AppServiceInterface
{

  public function __construct(District $model)
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
    $result =   $this->model->newQuery()->with(['kota.provinsi'])->find($id);

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
      return $this->sendSuccess($data);
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

      $read->jenis_kelompok_masyarakat    =   $data['jenis_kelompok_masyarakat'];
      $read->short_id                     =   $data['short_id'];
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
}
