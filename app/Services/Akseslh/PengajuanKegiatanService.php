<?php


namespace App\Services\Akseslh;


use App\Services\AppService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\File as FileTable;
use App\Models\PengajuanKegiatan;
use App\Services\FileUploadService;
use App\Services\AppServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\TahapanPengajuanKegiatan;
use Yajra\DataTables\Facades\DataTables;
use App\Models\LogTahapanPengajuanKegiatan;
use App\Services\PdfService;

class PengajuanKegiatanService extends AppService implements AppServiceInterface
{
    protected $modelTahapanPengajuanKegiatan;
    protected $modelLogTahapanPengajuanKegiatan;
    protected $fileUploadService;
    protected $fileTable;
    protected $pdfService;

    public function __construct(
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        PengajuanKegiatan $model,
        TahapanPengajuanKegiatan $modelTahapanPengajuanKegiatan,
        LogTahapanPengajuanKegiatan $modelLogTahapanPengajuanKegiatan,
        PdfService $pdfService
    ) {
        parent::__construct($model);
        $this->modelTahapanPengajuanKegiatan = $modelTahapanPengajuanKegiatan;
        $this->modelLogTahapanPengajuanKegiatan = $modelLogTahapanPengajuanKegiatan;
        $this->fileUploadService    =   $fileUploadService;
        $this->fileTable            =   $fileTable;
        $this->pdfService           =   $pdfService;
    }

    public function getAll()
    {
        $model = $this->model->query()->orderBy('created_at', 'DESC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function getAllAttr()
    {
        $result  = $this->model->newQuery()
            ->orderBy('created_at', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'                        => $items->id,
                'judul_pengajuan_kegiatan'  => $items->judul_pengajuan_kegiatan,
                'alamat_kegiatan'           => $items->alamat_kegiatan,
                'tanggal_mulai_kegiatan'    => $items->tanggal_mulai_kegiatan,
                'tanggal_akhir_kegiatan'    => $items->tanggal_akhir_kegiatan,
                'time_mulai_kegiatan'       => $items->time_mulai_kegiatan,
                'time_akhir_kegiatan'       => $items->time_akhir_kegiatan,
                'proposal_kegiatan'         => $items->proposal_kegiatan,
                'tujuan_kegiatan'           => $items->tujuan_kegiatan,
                'ruang_lingkup_kegiatan'    => $items->ruang_lingkup_kegiatan,
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
        $result =   $this->model->newQuery()->with(['paket_kegiatan'])->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {
            // Menghasilkan nomor pengajuan otomatis
            $data['nomor_pengajuan'] = PengajuanKegiatan::generateNomorPengajuan($data['paket_kegiatan_id'], $data['user']);

            $dataTahapanPengajuanKegiatan = $this->modelTahapanPengajuanKegiatan->newQuery()
                ->orderBy('created_at', 'DESC')->get();

            $newData = $this->model->newQuery()->create([
                'nomor_pengajuan'               => $data['nomor_pengajuan'],
                'paket_kegiatan_id'             => $data['paket_kegiatan_id'],
                'user_akseslh_id'               => $data['user_akseslh_id'],
                'judul_pengajuan_kegiatan'      => $data['judul_pengajuan_kegiatan'],
                'provinsi_kegiatan'             => $data['provinsi_kegiatan'],
                'kabupaten_kegiatan'            => $data['kabupaten_kegiatan'],
                'kecamatan_kegiatan'            => $data['kecamatan_kegiatan'],
                'kelurahan_kegiatan'            => $data['kelurahan_kegiatan'],
                'alamat_kegiatan'               => $data['alamat_kegiatan'],
                'proposal_kegiatan'             => $data['proposal_kegiatan'],
                'tujuan_kegiatan'               => $data['tujuan_kegiatan'],
                'ruang_lingkup_kegiatan'        => $data['ruang_lingkup_kegiatan'],
                'tanggal_mulai_kegiatan'        => date_create($data['tanggal_mulai_kegiatan']),
                'tanggal_akhir_kegiatan'        => date_create($data['tanggal_akhir_kegiatan']),
                'time_mulai_kegiatan'           => $data['time_mulai_kegiatan'],
                'time_akhir_kegiatan'           => $data['time_akhir_kegiatan'],
                'lokasi_bidang_folu_id'         => $data['lokasi_bidang_folu_id'],
            ]);

            foreach ($dataTahapanPengajuanKegiatan as $dt) {
                $this->modelLogTahapanPengajuanKegiatan->newQuery()->create([
                    'pengajuan_kegiatan_id'         => $newData->id,
                    'tahapan_pengajuan_kegiatan_id' => $dt->id,
                    'tanggal_masuk'                 => date("Y-m-d"),
                    'tanggal_selesai'               => ($dt->deskripsi_kegiatan == "Pengajuan" ? date("Y-m-d") : NULL)
                ]);
            }

            $dataSend = array('nomor_pengajuan' => $data['nomor_pengajuan']);

            // Save document 
            // upload document
            $upload = $this->fileUploadService->handleFile($data['fileDocument'])->saveToDb('document');

            if (!empty($upload)) {
                $image = $this->fileTable->newQuery()->find($upload->id);
                $image->update([
                    'fileable_type' => get_class($newData),
                    'fileable_id'   => $newData->id,
                ]);
            }

            // Save the PDF to the storage folder
            // Dicomment dulu,
            // $pdf = $this->pdfService->generateAndSavePdf('pdf.template-small-grant', get_class($newData), $newData, $data['nomor_pengajuan']);

            \DB::commit(); // commit the changes
            return $this->sendSuccess($dataSend);
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

            $read->jenis_kegiatan    =   $data['jenis_kegiatan'];
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

    public function apiGetBydId($id)
    {
        $model = $this->model->newQuery()->find($id);

        if (!$model)  return $this->sendError(null, 'Not Found');

        $result = [
            'id'                        => $model->id,
            'paket_kegiatan_id'         => $model->paket_kegiatan->id,
            'tematik_kegiatan_id'       => $model->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan_id,
            'sub_tematik_kegiatan_id'   => $model->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan_id,
            'document'                  => $model->document,
        ];

        return $this->sendSuccess($result);
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
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
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
