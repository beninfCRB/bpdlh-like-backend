<?php


namespace App\Services\Akseslh;

use App\Models\DataPicKelompokMasyarakat;
use App\Models\ProfilePic;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use App\Models\File as FileTable;
use App\Services\FileUploadService;


class ProfilePicService extends AppService implements AppServiceInterface
{
    protected $modelDataPicKelompokMasyarakat;
    protected $fileUploadService;
    protected $fileTable;

    public function __construct(
        ProfilePic $model,
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        DataPicKelompokMasyarakat $modelDataPicKelompokMasyarakat
    ) {
        parent::__construct($model);
        $this->fileUploadService                        = $fileUploadService;
        $this->fileTable                                = $fileTable;
        $this->modelDataPicKelompokMasyarakat           = $modelDataPicKelompokMasyarakat;
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
        $result =   $this->model->newQuery()->where('id', $id)->with(['data_pic_kelompok_masyarakat.kelompok_masyarakat.jenis', 'data_pic_kelompok_masyarakat.foto', 'document'])->first();

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

            $read = $this->model->newQuery()->create([
                'data_pic_kelompok_masyarakat_id'   =>  $data['data_pic_kelompok_masyarakat_id'],
                'jenis_kelompok_masyarakat'         =>  $data['jenis_kelompok_masyarakat'] ?? null,
                'jenis_kelompok_masyarakat_id'      =>  $data['jenis_kelompok_masyarakat_id'] ?? null,
                'kelompok_masyarakat_id'            =>  $data['kelompok_masyarakat_id'] ?? null,
                'kelompok_masyarakat'               =>  $data['kelompok_masyarakat'] ?? null,
                'nama_pic'              =>  $data['nama_pic'] ?? null,
                'jenis_identitas_pic'   =>  $data['jenis_identitas_pic'] ?? 'KTP',
                'nomor_identitas_pic'   =>  $data['nomor_identitas_pic'] ?? null,
                'nomor_npwp_pic'        =>  $data['nomor_npwp_pic'] ?? null,
                'email_pic'             =>  $data['email_pic'] ?? null,
                'nohp_pic'              =>  $data['nohp_pic'] ?? null,
                'alamat_pic'            =>  $data['alamat_pic'] ?? null,
                'kelurahan_pic'         =>  $data['kelurahan_pic'] ?? null,
                'kecamatan_pic'         =>  $data['kecamatan_pic'] ?? null,
                'kabupaten_pic'         =>  $data['kabupaten_pic'] ?? null,
                'provinsi_pic'          =>  $data['provinsi_pic'] ?? null,
                'tempat_lahir'          =>  $data['tempat_lahir'] ?? null,
                'tanggal_lahir'         =>  $data['tanggal_lahir'] ?? null,
                'agama_id'              =>  $data['agama_id'] ?? null,
                'status_perkawinan_id'  =>  $data['status_perkawinan_id'] ?? null,
                'jenis_pekerjaan_id'    =>  $data['jenis_pekerjaan_id'] ?? null,
                'pendidikan_id'         =>  $data['pendidikan_id'] ?? null,
                'jenis_kelamin'         =>  $data['jenis_kelamin'] ?? null,
                'nama_kontak_darurat'               =>  $data['nama_kontak_darurat'] ?? null,
                'nomor_kontak_darurat'              =>  $data['nomor_kontak_darurat'] ?? null,
                'alamat_kontak_darurat'             =>  $data['alamat_kontak_darurat'] ?? null,
                'flag'                  =>  1,
            ]);

            if (isset($data['foto_ktp'])) {
                # code...
                $upload = $this->fileUploadService->handleImage($data['foto_ktp'])->saveToDb('foto_ktp');
                if ($upload) {
                    $document = $this->fileTable->newQuery()->find($upload->id);
                    $document->update([
                        'fileable_type' => get_class($read),
                        'fileable_id'   => $read->id,
                    ]);
                }
            }

            if (isset($data['profil_kelompok'])) {
                # code...
                $upload = $this->fileUploadService->handleFile($data['profil_kelompok'])->saveToDb('profil_kelompok');
                if ($upload) {
                    $document = $this->fileTable->newQuery()->find($upload->id);
                    $document->update([
                        'fileable_type' => get_class($read),
                        'fileable_id'   => $read->id,
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

    public function pengajuanPerubahanProfil($id, $data)
    {
        $foto_ktp = false;
        $profil_kelompok = false;
        $nama_pic = false;
        $email_pic = false;
        $kelompok_masyarakat = false;
        $jenis_kelompok_masyarakat = false;

        if (in_array('is_accurate', $data['profile_field'])) {
            unset($data['profile_field'][array_search('is_accurate', $data['profile_field'])]);
        }

        if (in_array('foto_ktp', $data['profile_field'])) {
            $foto_ktp = true;

            // Hapus data foto_ktp
            $data['profile_field'] = array_diff($data['profile_field'], ['foto_ktp']);

            // kalau mau reset index biar rapi 0,1,2 dst
            $data['profile_field'] = array_values($data['profile_field']);
        }

        if (in_array('profil_kelompok', $data['profile_field'])) {
            $profil_kelompok = true;

            // Hapus data profile_kelompok
            $data['profile_field'] = array_diff($data['profile_field'], ['profil_kelompok']);

            // kalau mau reset index biar rapi 0,1,2 dst
            $data['profile_field'] = array_values($data['profile_field']);
        }

        if (in_array('nama_pic', $data['profile_field'])) {
            $nama_pic = true;
        }

        if (in_array('email_pic', $data['profile_field'])) {
            $email_pic = true;
        }

        if (in_array('jenis_kelompok_masyarakat', $data['profile_field'])) {
            $jenis_kelompok_masyarakat = true;
        }

        if (in_array('kelompok_masyarakat', $data['profile_field'])) {
            $kelompok_masyarakat = true;
        }

        $read   =   $this->model->newQuery()->select($data['profile_field'])->with(['document'])->where('id', $id)->first();

        if (!$read) {
            return $this->sendError(null, 'Data tidak ditemukan', 422);
        }

        $read_document = $read->document;

        $data_pic = $this->modelDataPicKelompokMasyarakat->newQuery()->with(['user_akseslh', 'kelompok_masyarakat'])->where('id', $read->data_pic_kelompok_masyarakat_id)->first();

        if (!$data_pic) {
            # code...
            return $this->sendError(null, 'Data user tidak ditemukan', 422);
        }

        \DB::beginTransaction();
        try {

            if ($data['status'] == 1) {
                if ($nama_pic) {
                    # code...
                    $data_pic->user_akseslh->nama_pic = $read->nama_pic;
                }

                if ($email_pic) {
                    # code...
                    $data_pic->user_akseslh->email = $read->email_pic;
                }

                $data_pic->user_akseslh->save();

                if ($jenis_kelompok_masyarakat) {
                    $data_pic->kelompok_masyarakat->jenis_kelompok_masyarakat = $read->jenis_kelompok_masyarakat;
                    $data_pic->kelompok_masyarakat->save();
                }

                if ($kelompok_masyarakat) {
                    $data_pic->kelompok_masyarakat->kelompok_masyarakat = $read->kelompok_masyarakat;
                    $data_pic->kelompok_masyarakat->save();
                }

                if ($foto_ktp) {
                    $id_old_document   =   $data_pic->foto()->where('group', 'foto_ktp')->first() ? $data_pic->foto()->where('group', 'foto_ktp')->first()->id : null;

                    if ($id_old_document) {
                        # code...
                        $readOldDocument   =   \DB::table('files')->where('id', $id_old_document)->first();

                        if ($readOldDocument) {
                            $filePath = $readOldDocument->file_path;

                            $this->fileUploadService->deleteFiles($filePath);

                            \DB::table('files')->where('id', $id_old_document)->delete();
                        }
                    }

                    $document_foto_ktp = $read_document->where('group', 'foto_ktp')->first();

                    if ($document_foto_ktp) {
                        $document_foto_ktp->update([
                            'fileable_type' => get_class($data_pic),
                            'fileable_id'   => $data_pic->id,
                        ]);
                    }
                }

                if ($profil_kelompok) {
                    $id_old_document   =   $data_pic->foto()->where('group', 'profil_kelompok')->first() ? $data_pic->foto()->where('group', 'profil_kelompok')->first()->id : null;

                    if ($id_old_document) {
                        # code...
                        $readOldDocument   =   \DB::table('files')->where('id', $id_old_document)->first();

                        if ($readOldDocument) {
                            $filePath = $readOldDocument->file_path;

                            $this->fileUploadService->deleteFiles($filePath);

                            \DB::table('files')->where('id', $id_old_document)->delete();
                        }
                    }

                    $document_profil_kelompok = $read_document->where('group', 'profil_kelompok')->first();

                    if ($document_profil_kelompok) {
                        $document_profil_kelompok->update([
                            'fileable_type' => get_class($data_pic),
                            'fileable_id'   => $data_pic->id,
                        ]);
                    }
                }

                $readData = $read->toArray();
                $readData['is_accurate'] = true;
                $readData['accurate_date'] = date('Y-m-d H:i:s');
                unset($readData['id'], $readData['created_at'], $readData['updated_at'], $readData['data_pic_kelompok_masyarakat_id'], $readData['document']);

                $data_pic->update($readData);

                $read->status_verifikasi    = 'verifikasi';
                $read->save();
            } else {
                $read->catatan              = $data['catatan'];
                $read->status_verifikasi    = 'tolak';
                $read->save();
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess(null, 'Success', 200);
        } catch (\Exception $exception) {
            //throw $th;
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function delete($id)
    {
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
