<?php


namespace App\Services\Akseslh;


use App\Services\AppService;
use App\Services\PdfService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\File as FileTable;
use App\Models\PengajuanKegiatan;
use App\Services\FileUploadService;
use App\Services\AppServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\TahapanPengajuanKegiatan;
use Yajra\DataTables\Facades\DataTables;
use App\Models\RabPengajuanPaketKegiatan;
use App\Models\LogTahapanPengajuanKegiatan;
use App\Models\UserAkseslh;
use App\Services\EmailPhpService;
use Carbon\Carbon;

class PengajuanKegiatanService extends AppService implements AppServiceInterface
{
    protected $modelTahapanPengajuanKegiatan;
    protected $modelLogTahapanPengajuanKegiatan;
    protected $fileUploadService;
    protected $fileTable;
    protected $pdfService, $emailPhpService;

    public function __construct(
        FileUploadService $fileUploadService,
        FileTable $fileTable,
        PengajuanKegiatan $model,
        TahapanPengajuanKegiatan $modelTahapanPengajuanKegiatan,
        LogTahapanPengajuanKegiatan $modelLogTahapanPengajuanKegiatan,
        PdfService $pdfService,
        EmailPhpService $emailPhpService
    ) {
        parent::__construct($model);
        $this->modelTahapanPengajuanKegiatan = $modelTahapanPengajuanKegiatan;
        $this->modelLogTahapanPengajuanKegiatan = $modelLogTahapanPengajuanKegiatan;
        $this->fileUploadService    =   $fileUploadService;
        $this->fileTable            =   $fileTable;
        $this->pdfService           =   $pdfService;
        $this->emailPhpService      =   $emailPhpService;
    }

    public function getAll()
    {
        $model = $this->model->query()->with([
            'provinsi',
            'kabupaten',
            'kecamatan',
            'kelurahan',
            'rab_pengajuan_paket_kegiatans',
            'user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat.jenis',
            'user_akseslh.data_pic_kelompok_masyarakat.provinsi',
            'user_akseslh.data_pic_kelompok_masyarakat.kabupaten',
            'user_akseslh.data_pic_kelompok_masyarakat.kecamatan',
            'user_akseslh.data_pic_kelompok_masyarakat.kelurahan',
            'paket_kegiatan.jenis_kegiatan',
            'paket_kegiatan.master_sub_tematik_kegiatan.tematik_kegiatan',
            'paket_kegiatan.master_sub_tematik_kegiatan.sub_tematik_kegiatan'
        ])->orderBy('created_at', 'DESC');

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

    public function apiGetAll()
    {
        $result = $this->model->newQuery()->get();

        return $this->sendSuccess($result);
    }

    public function getDataProsesKegiatan($user_akseslh_id)
    {
        $result =   $this->model->newQuery()->where(['user_akseslh_id' => $user_akseslh_id])->latest()->first();

        $data = [];

        if (!$result) return $this->sendSuccess(collect($data));

        if ($result) {
            # code...
            $data[] = [
                'nomor_pengajuan'   => $result->nomor_pengajuan,
                'tematik_kegiatan'          => $result->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan,
                'sub_tematik_kegiatan'      => $result->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan,
                'jenis_kegiatan'            => $result->paket_kegiatan->jenis_kegiatan->jenis_kegiatan,
                'jumlah'                    => $result->paket_kegiatan->jumlah_peserta . " " . ($result->paket_kegiatan->jumlah_peserta >= 50 ? "Orang" : "Hectare"),
                'lokasi'                    => $result->alamat_kegiatan ?? 'Alamat',
                'tahapan_pengajuan'         => $result->flag,
                'persentase_pengajuan'      => $this->checkAngkaPengajuan($result->flag, $result->log_tahapan_pengajuan),
                'dana_yang_disetujui'       => 0,
                'dana_yang_dicairkan'       => 0,
                'tanggal_kegiatan'          => $result->tanggal_mulai_kegiatan,
            ];
        }

        return $this->sendSuccess($data);
    }

    public function getDataRiwayatPengajuan($user_akseslh_id)
    {
        $result =   $this->model->newQuery()->with(['log_tahapan_pengajuan'])->where(['user_akseslh_id' => $user_akseslh_id])->get();

        if (!$result)  return $this->sendSuccess(null);

        $result->transform(function ($items, $key) {

            return [
                'id'                        => $items->id,
                'nomor_pengajuan'           => $items->nomor_pengajuan,
                'tematik_kegiatan'          => $items->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan,
                'sub_tematik_kegiatan'      => $items->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan->sub_tematik_kegiatan,
                'jenis_kegiatan'            => $items->paket_kegiatan->jenis_kegiatan->jenis_kegiatan,
                'jumlah'                    => $items->paket_kegiatan->jumlah_peserta . " " . ($items->paket_kegiatan->jumlah_peserta >= 50 ? "Orang" : "Hectare"),
                'lokasi'                    => $items->alamat_kegiatan ?? 'Alamat',
                'tahapan_pengajuan'         => $items->flag,
                'persentase_pengajuan'      => $this->checkAngkaPengajuan($items->flag, $items->log_tahapan_pengajuan),
                'dana_yang_disetujui'       => 0,
                'dana_yang_dicairkan'       => 0,
                'tanggal_kegiatan'          => $items->tanggal_mulai_kegiatan,
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
        $items =   $this->model->newQuery()->with(['document'])->find($id);

        $result = [
            'id'    => $items->id,
            'judul_pengajuan_kegiatan'  => $items->judul_pengajuan_kegiatan,
            'provinsi_kegiatan' => $items->provinsi_kegiatan,
            'kabupaten_kegiatan'    => $items->kabupaten_kegiatan,
            'kecamatan_kegiatan'    => $items->kecamatan_kegiatan,
            'kelurahan_kegiatan'    => $items->kelurahan_kegiatan,
            'alamat_kegiatan'   => $items->alamat_kegiatan,
            'tanggal_kegiatan' => $items->tanggal_mulai_kegiatan . ' - ' . $items->tanggal_akhir_kegiatan,
            'waktu_kegiatan'    => $items->time_mulai_kegiatan . ' - ' . $items->time_akhir_kegiatan,
            'proposal_kegiatan' => $items->proposal_kegiatan,
            'tujuan_kegiatan'   => $items->tujuan_kegiatan,
            'ruang_lingkup_kegiatan' => $items->ruang_lingkup_kegiatan,
            'paket_kegiatan_id' => $items->paket_kegiatan_id,
            'fileDocument'  => $items->document,
        ];

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {
            $cekData = PengajuanKegiatan::where(['user_akseslh_id' => $data['user_akseslh_id']])->latest()->first();

            if ($cekData) {
                # code...
                if ($cekData->flag < 9) {
                    # code...
                    return $this->sendError(null, 'Masih ada pengajuan yang berlangsung', 422);
                }

                // if ($cekData->rab_pengajuan_paket_kegiatans->count() <= 0 || $cekData->flag == 0) {
                //     # code...
                //     $cekData->forceDelete();
                // }
            }

            // Menghasilkan nomor pengajuan otomatis
            $data['nomor_pengajuan'] = PengajuanKegiatan::generateNomorPengajuan($data['paket_kegiatan_id'], $data['user']);

            $newData = $this->model->newQuery()->create([
                'nomor_pengajuan'               => $data['nomor_pengajuan'],
                'paket_kegiatan_id'             => $data['paket_kegiatan_id'],
                'user_akseslh_id'               => $data['user_akseslh_id'],
                'judul_pengajuan_kegiatan'      => $data['judul_pengajuan_kegiatan'] ?? null,
                'provinsi_kegiatan'             => $data['provinsi_kegiatan'] ?? null,
                'kabupaten_kegiatan'            => $data['kabupaten_kegiatan'] ?? null,
                'kecamatan_kegiatan'            => $data['kecamatan_kegiatan'] ?? null,
                'kelurahan_kegiatan'            => $data['kelurahan_kegiatan'] ?? null,
                'alamat_kegiatan'               => $data['alamat_kegiatan'] ?? null,
                'proposal_kegiatan'             => $data['proposal_kegiatan'] ?? null,
                'tujuan_kegiatan'               => $data['tujuan_kegiatan'] ?? null,
                'ruang_lingkup_kegiatan'        => $data['ruang_lingkup_kegiatan'] ?? null,
                'tanggal_mulai_kegiatan'        => isset($data['tanggal_mulai_kegiatan']) ? date_create($data['tanggal_mulai_kegiatan']) : Carbon::now()->format('Y-m-d'),
                'tanggal_akhir_kegiatan'        => isset($data['tanggal_akhir_kegiatan']) ? date_create($data['tanggal_akhir_kegiatan']) : Carbon::now()->format('Y-m-d'),
                'time_mulai_kegiatan'           => isset($data['time_mulai_kegiatan']) ? $data['time_mulai_kegiatan'] : '08:00',
                'time_akhir_kegiatan'           => isset($data['time_akhir_kegiatan']) ? $data['time_akhir_kegiatan'] : '16:00',
                'lokasi_bidang_folu_id'         => $data['lokasi_bidang_folu_id'] ?? null,
                'flag'                          => 0
            ]);

            $dataSend = array('nomor_pengajuan' => $data['nomor_pengajuan']);

            if (isset($data['fileDocument'])) {
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
            }

            // $dataSend = $this->getDataRab($newData->id, true);
            foreach ($newData->paket_kegiatan->standar_rab_paket_kegiatan as $item) {
                # code...
                $rab[] = [
                    'id_komponen'           => $item->master_komponen_rab_id,
                    'jenis_komponen_rab'    => $item->master_komponen_rab->jenis_komponen->jenis_komponen_rab,
                    'komponen_rab'          => $item->master_komponen_rab->komponen_rab,
                    'satuan'                => $item->master_komponen_rab->satuan->satuan,
                    'harga_unit'            => $item->standar_harga_unit,
                    'nilai_standar'         => $item->standar_harga_unit,
                    'qty'                   => $item->standar_qty,
                ];
            }

            $dataSend = [
                'id_pengajuan'      => $newData->id,
                'nomor_pengajuan'   => $data['nomor_pengajuan'],
                'komponen_rab'      => collect($rab)->groupBy('jenis_komponen_rab'),
            ];


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
        $read   =   $this->model->newQuery()->where(['nomor_pengajuan' => $id, 'user_akseslh_id' => $data['user_akseslh_id']])->first();

        if (!$read) return $this->sendError('Not Found');

        if ($read->flag > 0) {
            # code...
            return $this->sendError(null, 'Data bukan draft', 422);
        }

        \DB::beginTransaction();

        try {
            $read->paket_kegiatan_id         = $data['paket_kegiatan_id'];
            $read->user_akseslh_id           = $data['user_akseslh_id'];
            $read->judul_pengajuan_kegiatan  = $data['judul_pengajuan_kegiatan'] ?? null;
            $read->provinsi_kegiatan         = $data['provinsi_kegiatan'] ?? null;
            $read->kabupaten_kegiatan        = $data['kabupaten_kegiatan'] ?? null;
            $read->kecamatan_kegiatan        = $data['kecamatan_kegiatan'] ?? null;
            $read->kelurahan_kegiatan        = $data['kelurahan_kegiatan'] ?? null;
            $read->alamat_kegiatan           = $data['alamat_kegiatan'] ?? null;
            $read->proposal_kegiatan         = $data['proposal_kegiatan'] ?? null;
            $read->tujuan_kegiatan           = $data['tujuan_kegiatan'] ?? null;
            $read->ruang_lingkup_kegiatan    = $data['ruang_lingkup_kegiatan'] ?? null;
            $read->tanggal_mulai_kegiatan    = isset($data['tanggal_mulai_kegiatan']) ? date_create($data['tanggal_mulai_kegiatan']) : Carbon::now()->format('Y-m-d');
            $read->tanggal_akhir_kegiatan    = isset($data['tanggal_akhir_kegiatan']) ? date_create($data['tanggal_akhir_kegiatan']) : Carbon::now()->format('Y-m-d');
            $read->time_mulai_kegiatan       = isset($data['time_mulai_kegiatan']) ? $data['time_mulai_kegiatan'] : '08:00';
            $read->time_akhir_kegiatan       = isset($data['time_akhir_kegiatan']) ? $data['time_akhir_kegiatan'] : '16:00';
            $read->save();

            if (isset($data['fileDocument'])) {
                // Save document 
                // upload document
                $upload = $this->fileUploadService->handleFile($data['fileDocument'])->saveToDb('document');

                if (!empty($upload)) {
                    $image = $this->fileTable->newQuery()->find($upload->id);
                    $image->update([
                        'fileable_type' => get_class($read),
                        'fileable_id'   => $read->id,
                    ]);
                }
            }

            foreach ($read->paket_kegiatan->standar_rab_paket_kegiatan as $item) {
                # code...
                $rab[] = [
                    'id_komponen'           => $item->master_komponen_rab_id,
                    'jenis_komponen_rab'    => $item->master_komponen_rab->jenis_komponen->jenis_komponen_rab,
                    'komponen_rab'          => $item->master_komponen_rab->komponen_rab,
                    'satuan'                => $item->master_komponen_rab->satuan->satuan,
                    'harga_unit'            => $item->standar_harga_unit,
                    'nilai_standar'         => $item->standar_harga_unit,
                    'qty'                   => $item->standar_qty,
                ];
            }

            $dataSend = [
                'id_pengajuan'  => $read->id,
                'nomor_pengajuan'   => $read->nomor_pengajuan,
                'komponen_rab'  => collect($rab)->groupBy('jenis_komponen_rab'),
            ];

            \DB::commit(); // commit the changes
            return $this->sendSuccess($dataSend);
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

    public function getDataRab($id, $inController = false)
    {
        $result = $this->model->find($id);

        if (!$result || count($result->rab_pengajuan_paket_kegiatans) <= 0)  return $this->sendError(null, 'Not Found');

        $rab = null;
        foreach ($result->rab_pengajuan_paket_kegiatans as $item) {
            # code...
            $rab[] = [
                'id_komponen'           => $item->master_komponen_rab->id,
                'jenis_komponen_rab'    => $item->master_komponen_rab->jenis_komponen->jenis_komponen_rab,
                'komponen_rab'          => $item->master_komponen_rab->komponen_rab,
                'satuan'                => $item->master_komponen_rab->satuan->satuan,
                'harga_unit'            => $item->harga_unit,
                'nilai_standar'         => $item->harga_unit,
                'qty'                   => $item->qty,
            ];
        }
        $collectRab = collect($rab);

        $result = [
            'id_pengajuan'  => $id,
            'komponen_rab'  => $collectRab->groupBy('jenis_komponen_rab'),
        ];

        if ($inController) {
            # code...
            return $result;
        }
        return $this->sendSuccess($result);
    }

    private function checkStatusPengajuan($angka, $logTahapanPengajuanKegiatan)
    {
        if ($logTahapanPengajuanKegiatan->count() == 9) {
            # code...
            switch ($angka) {
                case 1:
                    # code...
                    return 'Dalam Proses Verifikasi';
                    break;

                case 20:
                    return 'Ditolak';
                    break;

                default:
                    # code...
                    0;
                    break;
            }
        }
    }

    private function checkAngkaPengajuan($angka, $logTahapanPengajuanKegiatan)
    {
        if ($logTahapanPengajuanKegiatan->count() == 9) {
            # code...
            switch ($angka) {
                case 1:
                    # code...
                    return 6;
                    break;

                default:
                    # code...
                    0;
                    break;
            }
        }
    }

    public function updateRab($id, $dataKomponenRab)
    {
        $model = $this->model->newQuery()->where(['nomor_pengajuan' => $id])->first();

        if (!$model) return $this->sendError(null, 'Not found');

        if ($model->rab_pengajuan_paket_kegiatans->count() > 0) return $this->sendError(null, 'Rab sudah ada', 422);

        \DB::beginTransaction();

        try {
            //code...

            $dataTahapanPengajuanKegiatan = $this->modelTahapanPengajuanKegiatan->newQuery()
                ->orderBy('created_at', 'DESC')->get();

            foreach ($dataTahapanPengajuanKegiatan as $dt) {
                $this->modelLogTahapanPengajuanKegiatan->newQuery()->create([
                    'pengajuan_kegiatan_id'         => $model->id,
                    'tahapan_pengajuan_kegiatan_id' => $dt->id,
                    'tanggal_masuk'                 => ($dt->deskripsi_kegiatan == "Pengajuan" || $dt->deskripsi_kegiatan == "Verifikasi"  ? date("Y-m-d") : NULL),
                    'tanggal_selesai'               => ($dt->deskripsi_kegiatan == "Pengajuan" ? date("Y-m-d") : NULL)
                ]);
            }

            $total = 0;
            $dataKomponenRabInput = null;
            foreach ($dataKomponenRab as $item) {
                # code...
                $dataKomponenRabInput[] = [
                    'pengajuan_kegiatan_id' => $model->id,
                    'komponen_rab_id'       => $item['id_komponen'],
                    'harga_unit'            => $item['harga_unit'],
                    'qty'                   => $item['qty'],
                ];
                $total += ($item['qty'] * $item['harga_unit']);
            }

            $result = [
                'nomor_pengajuan'   => $model->nomor_pengajuan,
                'sebesar'           => $total,
                'atas_nama'         => $model->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat
            ];

            $model->rab_pengajuan_paket_kegiatans()->saveMany(
                collect($dataKomponenRabInput)->map(function ($tahapSalur) {
                    return new RabPengajuanPaketKegiatan($tahapSalur);
                })
            );

            $model->flag = 1;
            $model->save();

            // Get data verifikator
            $verifikator = UserAkseslh::where('role_user', 'verifikator')->get();
            // Kirim Email ke verifikator
            foreach ($verifikator as $user) {
                # code...
                $this->emailPhpService->verifikasiPengajuanKegiatan($user, 'Verifikasi Pengajuan Kegiatan', $model, null, 'mail.verifikasi-pengajuan-kegiatan');
            }

            \DB::commit();
            return $this->sendSuccess($result);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }

    public function getDraftPengajuan($user_id)
    {
        $model = $this->model->newQuery()->where([
            'user_akseslh_id' => $user_id,
            'flag' => 0
        ])->latest()->first();

        if (!$model) return $this->sendSuccess(collect([]));

        $result = [
            'id'                        => $model->id,
            'judul_pengajuan_kegiatan'  => $model->judul_pengajuan_kegiatan,
            'provinsi_kegiatan'         => $model->provinsi_kegiatan,
            'kabupaten_kegiatan'        => $model->kabupaten_kegiatan,
            'kecamatan_kegiatan'        => $model->kecamatan_kegiatan,
            'kelurahan_kegiatan'        => $model->kelurahan_kegiatan,
            'alamat_kegiatan'           => $model->alamat_kegiatan,
            'tanggal_kegiatan'          => $model->tanggal_mulai_kegiatan . ' - ' . $model->tanggal_akhir_kegiatan,
            'waktu_kegiatan'            => $model->time_mulai_kegiatan . ' - ' . $model->time_akhir_kegiatan,
            'proposal_kegiatan'         => $model->proposal_kegiatan,
            'tujuan_kegiatan'           => $model->tujuan_kegiatan,
            'ruang_lingkup_kegiatan'    => $model->ruang_lingkup_kegiatan,
            'paket_kegiatan_id'         => $model->paket_kegiatan_id,
            'fileDocument'              => $model->document,
            'nomor_pengajuan'           => $model->nomor_pengajuan,
        ];

        return $this->sendSuccess($result);
    }

    public function updateInformasiPencairanDana($id, $data)
    {
        $model = $this->model->newQuery()->where(['id' => $id])->first();

        if (!$model) return $this->sendError(null, 'Not found');

        \DB::beginTransaction();

        try {
            //code...

            if (isset($data['perjanjian_kerjasama'])) {
                // Save document 
                // upload document
                $upload = $this->fileUploadService->handleFile($data['perjanjian_kerjasama'])->saveToDb('perjanjian_kerjasama');

                if (!empty($upload)) {
                    $document = $this->fileTable->newQuery()->find($upload->id);
                    $document->update([
                        'fileable_type' => get_class($model),
                        'fileable_id'   => $model->id,
                    ]);
                }
            }

            if (
                isset($data['tanggal_mulai_kegiatan']) &&
                isset($data['tanggal_akhir_kegiatan']) &&
                isset($data['time_mulai_kegiatan']) &&
                isset($data['time_akhir_kegiatan'])
            ) {
                # code...
                $model->tanggal_mulai_kegiatan  = $data["tanggal_mulai_kegiatan"];
                $model->tanggal_akhir_kegiatan  = $data["tanggal_akhir_kegiatan"];
                $model->time_mulai_kegiatan     = $data["time_mulai_kegiatan"];
                $model->time_akhir_kegiatan     = $data["time_akhir_kegiatan"];

                $model->save();
            }

            \DB::commit();
            return $this->sendSuccess();
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null);
        }
    }
}
