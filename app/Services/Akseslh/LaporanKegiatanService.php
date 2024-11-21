<?php


namespace App\Services\Akseslh;

use App\Models\Pengembalian;
use App\Models\PengajuanKegiatan;
use App\Models\LogTahapanPengajuanKegiatan;
use App\Services\AppService;
use App\Models\File as FileTable;
use App\Services\FileUploadService;
use App\Services\AppServiceInterface;
use Yajra\DataTables\Facades\DataTables;


class LaporanKegiatanService extends AppService implements AppServiceInterface
{
    protected $logTahapanPengajuanKegiatan;
    protected $fileUploadService;
    protected $fileTable;
    protected $modelPengembalian;

    public function __construct(
        PengajuanKegiatan $model,
        LogTahapanPengajuanKegiatan $logTahapanPengajuanKegiatan,
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        Pengembalian $modelPengembalian
    ) {
        parent::__construct($model);
        $this->logTahapanPengajuanKegiatan  = $logTahapanPengajuanKegiatan;
        $this->fileUploadService            =   $fileUploadService;
        $this->fileTable                    =   $fileTable;
        $this->modelPengembalian            = $modelPengembalian;
    }

    public function getAll()
    {
        $model = $this->model->query()->orderBy('short_id', 'ASC');

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
        try {
            $read->delete();
            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function apiUploadDokumenLaporanKegiatan($id, $input)
    {
        $user = $input['user_akseslh'];

        $model = $this->logTahapanPengajuanKegiatan->where('id', $id)
            ->whereHas('pengajuan_kegiatan', function ($query) use ($user) {
                $query->where('user_akseslh_id', $user->id);
            })->first();

        if (!$model) return $this->sendError(null, 'Not Found', 422);

        \DB::beginTransaction();

        try {

            // Save document 
            if ($input['file_dokumen']->getClientOriginalExtension() == 'pdf') {
                // upload document
                $upload = $this->fileUploadService->handleFile($input['file_dokumen'])->saveToDb($input['jenis_dokumen']);
            } else {
                $upload = $this->fileUploadService->handleImage($input['file_dokumen'])->saveToDb($input['jenis_dokumen']);
            }

            if (!empty($upload)) {
                $document = $this->fileTable->newQuery()->find($upload->id);
                $document->update([
                    'fileable_type' => get_class($model),
                    'fileable_id'   => $model->id,
                ]);
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess(null);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function apiGetDokumenLaporanKegiatan($id, $user)
    {
        $model = $this->logTahapanPengajuanKegiatan->with(['document_file', 'tahapan_pengajuan_kegiatan.jenis_dokumen'])->where('id', $id)
            ->whereHas('pengajuan_kegiatan', function ($query) use ($user) {
                $query->where('user_akseslh_id', $user->id);
            })->first();

        if (!$model) return $this->sendError(null, 'Not Found', 422);

        $result = $model->tahapan_pengajuan_kegiatan->jenis_dokumen()->orderBy('created_at', 'ASC')->get();
        $dokumen = $model->document_file;

        $result->transform(function ($item, $key) use ($dokumen) {

            return [
                'id'                    => $item->id,
                'jenis_dokumen'         => $item->jenis_dokumen,
                'url_dokumen'           => $item->document_file ? env('APP_URL') . '/storage/' . $item->document_file->file_path : null,
                'template_dokumen'      => $item->document_file ? $item->document_file->real_name : null,
                'dokumen'               => $dokumen ? $this->mapDokumen($dokumen, $item->jenis_dokumen) : null,
            ];
        });

        return $this->sendSuccess($result);
    }

    private function mapDokumen($dokument, $jenis_dokumen)
    {
        $document = $dokument->where('group', $jenis_dokumen);
        if (!$document) return null;
        $document->transform(function ($item, $key) {
            return [
                'id'    => $item->id,
                'url'   => $item->file_path,
                'name'  => $item->real_name,
                'size'  => $item->size,
            ];
        });
        return $document->values()->all();
    }

    public function apiDeleteDokumenLaporanKegiatan($id)
    {
        \DB::beginTransaction();
        try {
            $read   =   \DB::table('files')->where('id', $id)->first();
            $filePath = $read->file_path;

            $this->fileUploadService->deleteFiles($filePath);

            \DB::table('files')->where('id', $id)->delete();
            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }

    public function laporan_akhir($data)
    {
        $read   =   $this->model->newQuery()->find($data['pengajuan_kegiatan_id']);

        if (!$read) return $this->sendError(null, 'Not Found', 422);

        if ($read->flag != 9) return $this->sendError(null, 'Not Allowed', 403);

        \DB::beginTransaction();

        try {

            $this->logTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $data['pengajuan_kegiatan_id'])
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Laporan Akhir Kegiatan');
                })
                ->update(['tanggal_selesai' => date("Y-m-d")]);

            $this->logTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $data['pengajuan_kegiatan_id'])
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Verifikasi Laporan Akhir Kegiatan');
                })
                ->update(['tanggal_masuk' => date("Y-m-d")]);

            if (isset($data['jumlah_pengembalian'])) {
                # code...
                $pengembalian = $this->modelPengembalian->newQuery()->create([
                    'pengajuan_kegiatan_id'     =>  $data['pengajuan_kegiatan_id'],
                    'jumlah_pengembalian'       =>  $data['jumlah_pengembalian'],
                ]);

                // Save document 
                if ($data['bukti_pengembalian']->getClientOriginalExtension() == 'pdf') {
                    // upload document
                    $upload = $this->fileUploadService->handleFile($data['bukti_pengembalian'])->saveToDb('bukti_pengembalian');
                } else {
                    $upload = $this->fileUploadService->handleImage($data['bukti_pengembalian'])->saveToDb('bukti_pengembalian');
                }

                if (!empty($upload)) {
                    $document = $this->fileTable->newQuery()->find($upload->id);
                    $document->update([
                        'fileable_type' => get_class($pengembalian),
                        'fileable_id'   => $pengembalian->id,
                    ]);
                }
            }

            if (isset($data['laporan_akhir'])) {
                # code...
                $laporan_akhir_model = $this->logTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $data['pengajuan_kegiatan_id'])
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Laporan Akhir Kegiatan');
                    })
                    ->first();

                if ($data['laporan_akhir']->getClientOriginalExtension() == 'pdf') {
                    // upload document
                    $upload = $this->fileUploadService->handleFile($data['laporan_akhir'])->saveToDb('Laporan Akhir Kegiatan');
                } else {
                    $upload = $this->fileUploadService->handleImage($data['laporan_akhir'])->saveToDb('Laporan Akhir Kegiatan');
                }

                if (!empty($upload)) {
                    $document = $this->fileTable->newQuery()->find($upload->id);
                    $document->update([
                        'fileable_type' => get_class($laporan_akhir_model),
                        'fileable_id'   => $laporan_akhir_model->id,
                    ]);
                }
            }

            $read->flag      =   9;
            $read->save();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }
}
