<?php


namespace App\Services\Akseslh;

use App\Services\AppService;
use App\Models\PengajuanKegiatan;
use App\Services\EmailPhpService;
use App\Services\AppServiceInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DataPicKelompokMasyarakat;
use App\Models\LogTahapanPengajuanKegiatan;
use App\Models\CatatanLogTahapanPengajuanKegiatan;

class ProfileService extends AppService implements AppServiceInterface
{
    protected $emailService;
    protected $modelLogTahapanPengajuanKegiatan;
    protected $modelCatatanLogTahapanPengajuanKegiatan;
    protected $modelPengajuanKegiatan;

    public function __construct(
        DataPicKelompokMasyarakat $model,
        EmailPhpService $emailPhpService,
        LogTahapanPengajuanKegiatan $modelLogTahapanPengajuanKegiatan,
        CatatanLogTahapanPengajuanKegiatan $modelCatatanLogTahapanPengajuanKegiatan,
        PengajuanKegiatan $modelPengajuanKegiatan
    ) {
        parent::__construct($model);
        $this->emailService = $emailPhpService;
        $this->modelLogTahapanPengajuanKegiatan = $modelLogTahapanPengajuanKegiatan;
        $this->modelCatatanLogTahapanPengajuanKegiatan = $modelCatatanLogTahapanPengajuanKegiatan;
        $this->modelPengajuanKegiatan = $modelPengajuanKegiatan;
    }

    public function getAll()
    {
        $model = $this->model->query()->orderBy('short_id', 'ASC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function apiGetById($id, $data = null)
    {
        $model =   $this->model->newQuery()
            ->with(['user_akseslh' => function ($q) {
                $q->withTrashed();
            }, 'kelompok_masyarakat' => function ($q) {
                $q->withTrashed();
            }])
            ->find($id);

        if (!$model) return $this->sendError(null, 'Not Found', 422);

        $status_perubahan_profil = $model->profile_pic()->where('status_verifikasi', 'belum_verifikasi')->exists();

        $result = [
            "id" => $model->id,
            "status_perubahan_profil" => $status_perubahan_profil ? 'Menunggu verifikasi oleh pengelola' : '',
            "jenis_kelompok_masyarakat" => $model->kelompok_masyarakat->jenis->jenis_kelompok_masyarakat ?? null,
            "jenis_kelompok_masyarakat_id" => $model->kelompok_masyarakat->jenis->id ?? null,
            "kelompok_masyarakat" => $model->kelompok_masyarakat->kelompok_masyarakat ?? null,
            "kelompok_masyarakat_id" => $model->kelompok_masyarakat->id ?? null,
            "nama_pic" => $model->nama_pic ?? null,
            "jenis_identitas_pic" => $model->jenis_identitas_pic ?? null,
            "nomor_identitas_pic" => $model->nomor_identitas_pic ?? null,
            "nomor_npwp_pic" => $model->nomor_npwp_pic ?? null,
            "email_pic" => $model->email_pic ?? null,
            "nohp_pic" => $model->nohp_pic ?? null,
            "alamat_pic" => $model->alamat_pic ?? null,
            "kelurahan_pic" => $model->kelurahan->name ?? null,
            "kelurahan_pic_id" => $model->kelurahan->id ?? null,
            "kecamatan_pic" => $model->kecamatan->name ?? null,
            "kecamatan_pic_id" => $model->kecamatan->id ?? null,
            "kabupaten_pic" => $model->kabupaten->name ?? null,
            "kabupaten_pic_id" => $model->kabupaten->id ?? null,
            "provinsi_pic" => $model->provinsi->name ?? null,
            "provinsi_pic_id" => $model->provinsi->id ?? null,
            "tempat_lahir" => $model->tempat_lahir ?? null,
            "tanggal_lahir" => $model->tanggal_lahir ?? null,
            "agama" => $model->agama->agama ?? null,
            "agama_id" => $model->agama->id ?? null,
            "nama_kontak_darurat" => $model->nama_kontak_darurat ?? null,
            "nomor_kontak_darurat" => $model->nomor_kontak_darurat ?? null,
            "alamat_kontak_darurat" => $model->alamat_kontak_darurat ?? null,
            "status_perkawinan" => $model->status_perkawinan->status_pernikahan ?? null,
            "status_perkawinan_id" => $model->status_perkawinan->id ?? null,
            "nama_gadis_ibu_kandung" => $model->nama_gadis_ibu_kandung ?? null,
            "jenis_pekerjaan" => $model->jenis_pekerjaan->jenis_pekerjaan ?? null,
            "jenis_pekerjaan_id" => $model->jenis_pekerjaan->id ?? null,
            'pendidikan'        => $model->pendidikan->pendidikan ?? null,
            'pendidikan_id'        => $model->pendidikan->id ?? null,
            'jenis_kelamin' => $model->jenis_kelamin ?? null,
            "foto"  => $model->foto()->whereIn('group', ['foto_ktp'])->get(),
            'profile_kelompok' => $model->foto()->where('group', ['profil_kelompok'])->latest()->first(),
            'verifikator_admin' => $data['user']->master_user_jenis_kelompok->isEmpty() ? true : false
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

    public function delete_profile($id, $data, $emailSend = true)
    {
        $read   =   $this->model->newQuery()->find($id);

        if (!$read) {
            \Sentry\captureMessage('Validate Message: ' . $data['user']->email . ' User tidak ditemukan', \Sentry\Severity::warning());
            return $this->sendError(null, 'Not Found ' . $data['user']->email . ' User tidak ditemukan', 422);
        }

        $pengajuan = $this->modelPengajuanKegiatan->newQuery()->find($data['pengajuan_kegiatan_id']);

        if (!$pengajuan) {
            \Sentry\captureMessage('Validate Message: ' . $data['user']->email . ' Pengajuan tidak ditemukan', \Sentry\Severity::warning());
            return $this->sendError(null, 'Not Found ' . $data['user']->email . ' Pengajuan tidak ditemukan', 422);
        }

        if ($pengajuan->flag != 1 || $pengajuan->flag != '1') {
            \Sentry\captureMessage('Validate Message: ' . $data['user']->email . ' Tolak profil tidak valid', \Sentry\Severity::warning());
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

            if ($emailSend) {
                # code...
                $this->emailService->profileDitolak($read->user_akseslh, 'Profile Ditolak', $dataSend, null, 'mail.verifikasi-profile-ditolak');
            }

            $pengajuan->update(['flag' => 20]);

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

    public function check_profile($data)
    {
        $model =   $this->model->newQuery()
            ->whereHas('user_akseslh', function ($q) use ($data) {
                $q->where('id', $data['user']->id);
            })
            ->first();
        $status_perubahan_profil = $model->profile_pic()->where('status_verifikasi', 'belum_verifikasi')->exists();

        if (!$model) {
            return $this->sendError(null, 'Profil tidak ditemukan', 422);
        }

        if ($status_perubahan_profil) {
            # code...
            return $this->sendError(null, 'Perubahan profil masih tahap verifikasi oleh pengelola', 422);
        }

        if ($model->is_accurate == false) {
            return $this->sendError(null, 'Data profil belum diperbarui, silahkan perbarui data profil terlebih dahulu', 422);
        }

        if (!$model->nomor_kontak_darurat || !$model->nama_kontak_darurat || !$model->alamat_kontak_darurat) {
            if ($status_perubahan_profil) {
                # code...
                return $this->sendError(null, 'Perubahan profil masih tahap verifikasi oleh pengelola', 422);
            } else {
                return $this->sendError(null, 'Profil belum lengkap, silahkan lengkapi profil terlebih dahulu', 422);
            }
        }

        if ($model->nohp_pic == $model->nomor_kontak_darurat) {
            if ($status_perubahan_profil) {
                # code...
                return $this->sendError(null, 'Perubahan profil masih tahap verifikasi oleh pengelola', 422);
            } else {
                return $this->sendError(null, 'Nomor HP dan Nomor Kontak Darurat tidak boleh sama', 422);
            }
        }

        $result = [
            "id" => $model->id,
            "kelompok_masyarakat_id" => $model->kelompok_masyarakat->id ?? null,
            "nama_pic" => $model->nama_pic ?? null,
            "email_pic" => $model->email_pic ?? null,
            "nohp_pic" => $model->nohp_pic ?? null,
        ];

        return $this->sendSuccess($result, 'Profile found', 200);
    }
}
