<?php


namespace App\Services\Akseslh;


use App\Services\AppService;
use App\Models\LogJadwalPembukaan;
use App\Services\AppServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class LogJadwalPembukaanService extends AppService implements AppServiceInterface
{

    public function __construct(LogJadwalPembukaan $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {
        $model = $this->model->query()->withTrashed()->orderBy('created_at', 'DESC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function apiGetAll()
    {
        $result = Cache::remember('range_opening', now()->adddays(7), function () {
            $model  = $this->model->newQuery()
                ->latest()
                ->first();

            return [
                'tanggal_awal'      => $model->tanggal_awal,
                'jam_awal'          => $model->jam_awal,
                'tanggal_akhir'     => $model->tanggal_akhir,
                'jam_akhir'         => $model->jam_akhir,
                'batas_pengajuan'   => $model->batas_pengajuan,
            ];
        });

        return $this->sendSuccess($result);

        // $model = $this->model->newQuery()->latest()->first();

        // $result = [
        //     'tanggal_awal'      => $model->tanggal_awal,
        //     'jam_awal'          => $model->jam_awal,
        //     'tanggal_akhir'     => $model->tanggal_akhir,
        //     'jam_akhir'         => $model->jam_akhir,
        //     'batas_pengajuan'         => $model->batas_pengajuan,
        // ];

        // return $this->sendSuccess($result);
    }

    public function getAllAttr()
    {
        $result  = $this->model->newQuery()
            ->orderBy('created_at', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'                 => $items->id,
                'jenis_kegiatan'     => $items->jenis_kegiatan,
            ];
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
            $oldData = $this->model->newQuery()->latest()->first();

            if ($oldData) $oldData->delete();

            $amount = preg_replace('/[^\d]/', '', $data['batas_pengajuan']);

            $data = $this->model->newQuery()->create([
                'tanggal_awal'      =>  $data['tanggal_awal'],
                'tanggal_akhir'     =>  $data['tanggal_akhir'],
                'jam_awal'          =>  $data['jam_awal'],
                'jam_akhir'         =>  $data['jam_akhir'],
                'batas_pengajuan'   =>  $amount,
                'username'          =>  auth()->user()->username ?? auth()->user()->email
            ]);

            \DB::commit(); // commit the changes

            Cache::forget('range_opening');
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

            $read->jenis_kegiatan    =   $data['jenis_kegiatan'];
            $read->short_id                     =   $data['short_id'];
            $read->code_id                     =   $data['code_id'];
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
}
