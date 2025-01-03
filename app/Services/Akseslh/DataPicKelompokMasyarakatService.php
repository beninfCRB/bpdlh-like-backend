<?php


namespace App\Services\Akseslh;


use App\Models\DataPicKelompokMasyarakat;
use App\Models\UserAkseslh;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Models\File as FileTable;
use App\Services\FileUploadService;


class DataPicKelompokMasyarakatService extends AppService implements AppServiceInterface
{
    protected $modelUserAkseslh;
    protected $fileUploadService;
    protected $fileTable;

    public function __construct(
        DataPicKelompokMasyarakat $model,
        UserAkseslh $modelAkseslh,
        FileUploadService $fileUploadService,
        FileTable $fileTable
    ) {
        parent::__construct($model);
        $this->modelUserAkseslh = $modelAkseslh;
        $this->fileUploadService                        = $fileUploadService;
        $this->fileTable                                = $fileTable;
    }

    public function getAll()
    {
        $model = $this->model->query()->with(['kelompok_masyarakat.jenis', 'user_akseslh'])->orderBy('created_at', 'DESC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
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

            // Insert data to database
            $data = $this->model->newQuery()->create([
                'kelompok_masyarakat_id'    => $data['kelompok_masyarakat_id'],
                'nama_pic'                  => $data['nama_pic'],
                'jenis_identitas_pic'       => $data['jenis_identitas_pic'],
                'nomor_identitas_pic'       => $data['nomor_identitas_pic'],
                'email_pic'                 => $data['email_pic'] ?? null,
                'nohp_pic'                  => $data['nohp_pic'],
                'alamat_pic'                => $data['alamat_pic'],
                'kelurahan_pic'             => $data['kelurahan_pic'],
                'kecamatan_pic'             => $data['kecamatan_pic'],
                'kabupaten_pic'             => $data['kabupaten_pic'],
                'provinsi_pic'              => $data['provinsi_pic'],
                'tempat_lahir'              => $data['tempat_lahir'],
                'tanggal_lahir'             => $data['tanggal_lahir'],
                'agama_id'                  => $data['agama_id'],
                'status_perkawinan_id'      => $data['status_perkawinan_id'],
                'nama_gadis_ibu_kandung'    => $data['nama_gadis_ibu_kandung'],
                'jenis_pekerjaan_id'        => $data['jenis_pekerjaan_id'],
                'pendidikan_id'             => $data['pendidikan_id'],
                'flag'                      => 1,
            ]);

            $dataUserAkseslh = $this->modelUserAkseslh->newQuery()->create([
                'data_pic_kelompok_masyarakat_id'   => $data->id,
                'nama_pic'                          => $data->nama_pic,
                'email'                             => $data['email_pic'] ?? null,
                'role_user'                         => 'maker',
                // 'password'                          => Hash::make($default_password),
                'status_user'                       => 'NON ACTIVE',
                'flag'                              => 1,
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

            $read->kelompok_masyarakat_id   = $data['kelompok_masyarakat_id'];
            $read->nama_pic                 = $data['nama_pic'];
            $read->jenis_identitas_pic      = $data['jenis_identitas_pic'];
            $read->nomor_identitas_pic      = $data['nomor_identitas_pic'];
            $read->email_pic                = $data['email_pic'] ?? null;
            $read->nohp_pic                 = $data['nohp_pic'];
            $read->alamat_pic               = $data['alamat_pic'];
            $read->kelurahan_pic            = $data['kelurahan_pic'];
            $read->kecamatan_pic            = $data['kecamatan_pic'];
            $read->kabupaten_pic            = $data['kabupaten_pic'];
            $read->provinsi_pic             = $data['provinsi_pic'];
            $read->tempat_lahir             = $data['tempat_lahir'];
            $read->tanggal_lahir            = $data['tanggal_lahir'];
            $read->agama_id                 = $data['agama_id'];
            $read->status_perkawinan_id     = $data['status_perkawinan_id'];
            $read->nama_gadis_ibu_kandung   = $data['nama_gadis_ibu_kandung'];
            $read->jenis_pekerjaan_id       = $data['jenis_pekerjaan_id'];
            $read->pendidikan_id            = $data['pendidikan_id'];

            $upload = $this->fileUploadService->handleFile($data['dokumen_pendukung'])->saveToDb('dokumen_pendukung');

            if ($upload) {
                $upload->update([
                    'fileable_type' => get_class($read),
                    'fileable_id'   => $read->id,
                ]);
            }

            $read->save();

            $read->user_akseslh->nama_pic           = $data['nama_pic'] ?? null;
            $read->user_akseslh->email              = $data['email_pic'] ?? null;
            $read->user_akseslh->status_user        = $data['status_user'];
            $read->user_akseslh->save();



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
            $read->user_akseslh->delete();
            $read->delete();
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
}
