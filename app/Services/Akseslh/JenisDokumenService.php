<?php


namespace App\Services\Akseslh;


use App\Models\JenisDokumen;
use App\Services\AppService;
use App\Services\FileUploadService;
use App\Services\AppServiceInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Models\File as FileTable;

class JenisDokumenService extends AppService implements AppServiceInterface
{
    protected $fileTable;
    protected $fileUploadService;

    public function __construct(
        JenisDokumen $model,
        FileUploadService $fileUploadService,
        FileTable $fileTable
    ) {
        $this->fileUploadService    =   $fileUploadService;
        $this->fileTable            =   $fileTable;
        parent::__construct($model);
    }

    public function getAll()
    {
        $model = $this->model->query()->with(['tahapan_pengajuan_kegiatan', 'document_file'])->orderBy('created_at', 'ASC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function apiGetAll()
    {
        $result  = $this->model->newQuery()
            ->with(['tahapan_pengajuan_kegiatan', 'document_file'])
            ->orderBy('created_at', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'                            => $items->id,
                'tahapan_pengajuan_kegiatan'    => $items->tahapan_pengajuan_kegiatan->deskripsi_kegiatan,
                'jenis_dokumen'                 => $items->jenis_dokumen,
                'dokumen_url'                   => env('APP_URL') . '/storage/' . $items->document_file()->first()->file_path,
                'dokumen'                       => $items->document_file()->first(),
            ];
        });

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

            $newData = $this->model->newQuery()->create([
                'jenis_dokumen'                 =>  $data['jenis_dokumen'],
                'tahapan_pengajuan_kegiatan_id' =>  $data['tahapan_pengajuan_kegiatan_id'],
            ]);

            if (isset($data['dokumen'])) {
                // upload dokumen
                if (
                    $data['dokumen']->getClientOriginalExtension() == 'pdf' ||
                    $data['dokumen']->getClientOriginalExtension() == 'docx' ||
                    $data['dokumen']->getClientOriginalExtension() == 'xlsx'
                ) {
                    $upload = $this->fileUploadService->handleFile($data['dokumen'])->saveToDb($newData->jenis_dokumen);
                } else {
                    $upload = $this->fileUploadService->handleImage($data['dokumen'])->saveToDb($newData->jenis_dokumen);
                }

                if (!empty($upload)) {
                    $image = $this->fileTable->newQuery()->find($upload->id);
                    $image->update([
                        'fileable_type' => get_class($newData),
                        'fileable_id'   => $newData->id,
                    ]);
                }
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess($data);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function update($id, $data)
    {
        $read           = $this->model->newQuery()->find($id);
        $oldDocument    = $read->document_file()->first();

        \DB::beginTransaction();

        try {

            $read->jenis_dokumen    =   $data['jenis_dokumen'];
            $read->tahapan_pengajuan_kegiatan_id = $data['tahapan_pengajuan_kegiatan_id'];

            if ($read->save()) {
                if (isset($data['dokumen'])) {

                    if (isset($oldDocument)) {
                        $this->fileUploadService->deleteFiles($oldDocument->file_path);
                        $oldDocument->delete();
                    }

                    // upload dokumen
                    if (
                        $data['dokumen']->getClientOriginalExtension() == 'pdf' ||
                        $data['dokumen']->getClientOriginalExtension() == 'docx' ||
                        $data['dokumen']->getClientOriginalExtension() == 'xlsx'
                    ) {
                        $upload = $this->fileUploadService->handleFile($data['dokumen'])->saveToDb($read->jenis_dokumen);
                    } else {
                        $upload = $this->fileUploadService->handleImage($data['dokumen'])->saveToDb($read->jenis_dokumen);
                    }

                    if (!empty($upload)) {
                        $image = $this->fileTable->newQuery()->find($upload->id);
                        $image->update([
                            'fileable_type' => get_class($read),
                            'fileable_id'   => $read->id,
                        ]);
                    }
                }
            }

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
        $oldDocument    = $read->document_file()->first();

        try {
            $this->fileUploadService->deleteFiles($oldDocument->file_path);
            \DB::table('files')->where('id', $oldDocument->id)->delete();
            $oldDocument->delete();
            $read->delete();
            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function deleteDokumen($id)
    {
        $read   =   $this->model->newQuery()->find($id);
        $oldDocument    = $read->document_file()->first();

        try {
            $this->fileUploadService->deleteFiles($oldDocument->file_path);
            \DB::table('files')->where('id', $oldDocument->id)->delete();
            $oldDocument->delete();

            \DB::commit(); // commit the changes
            return $this->sendSuccess($read);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }
}
