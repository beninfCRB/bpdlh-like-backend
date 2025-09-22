<?php


namespace App\Services\Akseslh;

use App\Jobs\TolakPengajuanDanProfilJob;
use Illuminate\Support\Str;
use App\Services\AppService;
use App\Models\PengajuanKegiatan;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\AppServiceInterface;
use App\Models\TolakPengajuanDanProfil;
use Yajra\DataTables\Facades\DataTables;

class TolakPengajuanDanProfilService extends AppService implements AppServiceInterface
{
    protected $modelTolakPengajuanDanProfil;

    public function __construct(PengajuanKegiatan $model, TolakPengajuanDanProfil $modelTolakPengajuanDanProfil)
    {
        parent::__construct($model);
        $this->modelTolakPengajuanDanProfil = $modelTolakPengajuanDanProfil;
    }

    public function getAll()
    {
        $model = $this->modelTolakPengajuanDanProfil->query()->orderBy('created_at', 'ASC');

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

    private function isHeaderRow($row)
    {
        $expected = ['nomor_pengajuan', 'email_pic', 'status_penolakan', 'catatan_penolakan'];
        $keys = array_map('strtolower', array_keys($row->toArray()));
        return $keys === $expected;
    }

    public function proses()
    {
        $data = $this->modelTolakPengajuanDanProfil->query()
            ->where('status', 'pending')
            ->pluck('id')
            ->toArray();

        try {
            //code...
            if (empty($data)) {
                # code...
                return $this->sendError(null, 'Tidak ada data yang perlu diproses', 422);
            }

            $chunks = array_chunk($data, 20);

            foreach ($chunks as $chunk) {
                TolakPengajuanDanProfilJob::dispatch($chunk);
            }

            return $this->sendSuccess(null, 'Data berhasil diproses', 200);
        } catch (\Exception $exception) {
            //throw $th;
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function create($data)
    {

        $file = $data['file'];

        try {
            // $path = $file->getRealPath();

            $rows = Excel::toCollection(null, $file)[0];

            if ($rows->count() <= 1) {
                # code...
                return $this->sendError(null, 'File upload kosong', 422);
            }

            $dataToInsert = [];

            foreach ($rows as $index => $row) {

                // Skip baris kosong atau baris header
                if ($index === 0) continue;

                $dataToInsert[] = [
                    'id' => (string) Str::uuid(),
                    'nomor_pengajuan' => $row[0] ?? null,
                    'email_pic' => $row[1] ?? null,
                    'status_penolakan' => $row[2] ?? null,
                    'catatan_penolakan' => $row[3] ?? null,
                    'username'  => $data['username'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            \DB::transaction(function () use ($dataToInsert) {
                TolakPengajuanDanProfil::insert($dataToInsert);
            });

            return $this->sendSuccess($data, 'Berhasil mengunggah data', 200);
        } catch (\Exception $exception) {

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

    public function updatePublish($id, $isPublish): object
    {
        $read = $this->model->newQuery()->find($id);

        \DB::beginTransaction();

        try {
            $read->is_publish       =   $isPublish;
            $read->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
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
