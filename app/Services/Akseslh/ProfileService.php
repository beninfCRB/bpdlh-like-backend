<?php


namespace App\Services\Akseslh;

use App\Models\CatatanLogTahapanPengajuanKegiatan;
use App\Services\AppService;
use App\Services\EmailPhpService;
use App\Services\AppServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DataPicKelompokMasyarakat;
use App\Models\LogTahapanPengajuanKegiatan;

class ProfileService extends AppService implements AppServiceInterface
{
    protected $emailService;
    protected $modelLogTahapanPengajuanKegiatan;
    protected $modelCatatanLogTahapanPengajuanKegiatan;

    public function __construct(
        DataPicKelompokMasyarakat $model,
        EmailPhpService $emailPhpService,
        LogTahapanPengajuanKegiatan $modelLogTahapanPengajuanKegiatan,
        CatatanLogTahapanPengajuanKegiatan $modelCatatanLogTahapanPengajuanKegiatan
    ) {
        parent::__construct($model);
        $this->emailService = $emailPhpService;
        $this->modelLogTahapanPengajuanKegiatan = $modelLogTahapanPengajuanKegiatan;
        $this->modelCatatanLogTahapanPengajuanKegiatan = $modelCatatanLogTahapanPengajuanKegiatan;
    }

    public function getAll()
    {
        $model = $this->model->query()->orderBy('short_id', 'ASC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function apiGetById($id)
    {
        $model =   $this->model->newQuery()->find($id);

        if (!$model) return $this->sendError(null, 'Not Found', 422);

        $result = [
            "id" => $model->id,
            "kelompok_masyarakat" => $model->kelompok_masyarakat->kelompok_masyarakat ?? null,
            "nama_pic" => $model->nama_pic ?? null,
            "jenis_identitas_pic" => $model->jenis_identitas_pic ?? null,
            "nomor_identitas_pic" => $model->nomor_identitas_pic ?? null,
            "nomor_npwp_pic" => $model->nomor_npwp_pic ?? null,
            "email_pic" => $model->email_pic ?? null,
            "nohp_pic" => $model->nohp_pic ?? null,
            "alamat_pic" => $model->alamat_pic ?? null,
            "kelurahan_pic" => $model->kelurahan->name ?? null,
            "kecamatan_pic" => $model->kecamatan->name ?? null,
            "kabupaten_pic" => $model->kabupaten->name ?? null,
            "provinsi_pic" => $model->provinsi->name ?? null,
            "tempat_lahir" => $model->tempat_lahir ?? null,
            "tanggal_lahir" => $model->tanggal_lahir ?? null,
            "agama" => $model->agama->agama ?? null,
            "status_perkawinan" => $model->status_perkawinan->status_pernikahan ?? null,
            "nama_gadis_ibu_kandung" => $model->nama_gadis_ibu_kandung ?? null,
            "jenis_pekerjaan" => $model->jenis_pekerjaan->jenis_pekerjaan ?? null,
            'pendidikan'        => $model->pendidikan->pendidikan ?? null,
            "foto"  => $model->foto()->whereIn('group', ['foto_ktp'])->get(),
            'profile_kelompok' => $model->foto()->where('group', ['profil_kelompok'])->latest()->first()
        ];

        return $this->sendSuccess($result);
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

            $data = $this->model->newQuery()->create([
                'jenis_kegiatan'       =>  $data['jenis_kegiatan'],
                'short_id'                      =>  $data['short_id'],
                'code_id'                      =>  $data['code_id'],
                'flag'                 => 1,
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
        if (!$read) return $this->sendError(null, 'Not Found', 422);
        try {
            $read->user_akseslh->delete();
            $read->delete();
            \DB::commit(); // commit the changes
            return $this->sendSuccess(null);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function delete_profile($id, $data)
    {
        $read   =   $this->model->newQuery()->find($id);

        if (!$read) {
            \Sentry\captureMessage('Validate Message: ' . $data['user']->email_pic . ' User tidak ditemukan', \Sentry\Severity::warning());
            return $this->sendError(null, 'Not Found', 422);
        }

        \DB::beginTransaction();

        try {
            $idLog = $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $data['pengajuan_kegiatan_id'])
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Verifikasi');
                })->first();

            $this->modelCatatanLogTahapanPengajuanKegiatan->newQuery()
                ->create([
                    'log_tahapan_pengajuan_kegiatan_id' => $idLog->id,
                    'catatan_log'                       => $data['catatan_log']
                ]);

            $idLog->tanggal_selesai = date('Y-m-d');
            $idLog->user_akseslh_id = $data['user']->id;
            $idLog->save();

            $dataSend = array(
                'nomor_pengajuan'   => null,
                'catatan_log'       => $data['catatan_log'],
                'keterangan'        => 'Ditolak',
                'status'            => 20
            );

            $this->emailService->profileDitolak($read->user_akseslh, 'Profile Ditolak', $dataSend, null, 'mail.verifikasi-profile-ditolak');

            $read->user_akseslh->delete();
            $read->delete();
            \DB::commit(); // commit the changes
            return $this->sendSuccess(null);
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
}
