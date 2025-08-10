<?php


namespace App\Services\Akseslh;


use App\Models\ProfilePic;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;

class ProfilePicService extends AppService implements AppServiceInterface
{

    public function __construct(ProfilePic $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {
        $model = $this->model->query()->where('status_verifikasi', 'belum_verifikasi')->with('data_pic_kelompok_masyarakat.kelompok_masyarakat.jenis')->orderBy('created_at', 'ASC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function getPaginated($search = null, $page = null, $perPage = null, $lang = null)
    {
        $result =   $this->switchLang($search, $page, $perPage, $lang);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result =   $this->model->newQuery()->where('id', $id)->with('data_pic_kelompok_masyarakat.kelompok_masyarakat.jenis')->first();

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        $model = $this->model->newQuery()
            ->where(['data_pic_kelompok_masyarakat_id' => $data['data_pic_kelompok_masyarakat_id'], 'status_verifikasi' => 'belum_verifikasi'])
            ->first();

        if ($model) {
            return $this->sendError(null, 'Permintaan perubahan profil masih tahap verifikasi', 422);
        }

        \DB::beginTransaction();

        try {

            $data = $this->model->newQuery()->create([
                'data_pic_kelompok_masyarakat_id' =>  $data['data_pic_kelompok_masyarakat_id'],
                'kelompok_masyarakat_id' =>  $data['kelompok_masyarakat_id'],
                'kelompok_masyarakat' =>  $data['kelompok_masyarakat'],
                'nama_pic'              =>  $data['nama_pic'],
                'jenis_identitas_pic'   =>  $data['jenis_identitas_pic'],
                'nomor_identitas_pic'   =>  $data['nomor_identitas_pic'],
                'nomor_npwp_pic'        =>  $data['nomor_npwp_pic'],
                'email_pic'             =>  $data['email_pic'],
                'nohp_pic'              =>  $data['nohp_pic'],
                'alamat_pic'            =>  $data['alamat_pic'],
                'kelurahan_pic'         =>  $data['kelurahan_pic'],
                'kecamatan_pic'         =>  $data['kecamatan_pic'],
                'kabupaten_pic'         =>  $data['kabupaten_pic'],
                'provinsi_pic'          =>  $data['provinsi_pic'],
                'tempat_lahir'          =>  $data['tempat_lahir'],
                'tanggal_lahir'         =>  $data['tanggal_lahir'],
                'agama_id'              =>  $data['agama_id'],
                'status_perkawinan_id'  =>  $data['status_perkawinan_id'],
                'jenis_pekerjaan_id'    =>  $data['jenis_pekerjaan_id'],
                'pendidikan_id'         =>  $data['pendidikan_id'],
                'jenis_kelamin'         =>  $data['jenis_kelamin'],
                'flag'                  =>  1,
            ]);

            if (isset($data['foto_ktp'])) {
                # code...
                $upload = $this->fileUploadService->handleImage('foto_ktp')->saveToDb('foto_ktp');
                if ($upload) {
                    $document = $this->fileTable->newQuery()->find($upload->id);
                    $document->update([
                        'fileable_type' => get_class($data),
                        'fileable_id'   => $data->id,
                    ]);
                }
            }

            if (isset($data['profil_kelompok'])) {
                # code...
                $upload = $this->fileUploadService->handleFile('profil_kelompok')->saveToDb('profil_kelompok');
                if ($upload) {
                    $document = $this->fileTable->newQuery()->find($upload->id);
                    $document->update([
                        'fileable_type' => get_class($data),
                        'fileable_id'   => $data->id,
                    ]);
                }
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess(null, 'Permintaan perubahan profil berhasil dikirim', 200);
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
        $result = Cache::remember('jenis_kelompok_masyarakat', now()->adddays(7), function () {
            $data  = $this->model->newQuery()
                ->where('flag', true)
                ->orderBy('short_id', 'ASC')
                ->get();

            return $data->transform(function ($items, $key) {
                return [
                    'id'                            => $items->id,
                    'jenis_kelompok_masyarakat'     => $items->jenis_kelompok_masyarakat,
                    'short_id'                      => $items->short_id,
                    'flag'                          => $items->flag,
                ];
            });
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
