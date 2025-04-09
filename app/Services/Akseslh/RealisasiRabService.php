<?php


namespace App\Services\Akseslh;

use App\Models\PengajuanKegiatan;
use App\Models\TahapanPengajuanKegiatan;
use App\Models\RabPengajuanPaketKegiatan;
use App\Models\LogTahapanPengajuanKegiatan;
use App\Models\DetailLogTahapanPengajuanKegiatan;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use App\Notifications\LaporanNotification;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RealisasiRabService extends AppService implements AppServiceInterface
{
    protected $modelRabPengajuanPaketKegiatan;
    protected $modelLogTahapanPengajuanKegiatan;
    protected $modelTahapanPengajuanKegiatan;
    protected $modelDetailLogTahapanPengajuanKegiatan;

    public function __construct(
        PengajuanKegiatan $model,
        RabPengajuanPaketKegiatan $modelRabPengajuanPaketKegiatan,
        LogTahapanPengajuanKegiatan $modelLogTahapanPengajuanKegiatan,
        TahapanPengajuanKegiatan $modelTahapanPengajuanKegiatan,
        DetailLogTahapanPengajuanKegiatan $modelDetailLogTahapanPengajuanKegiatan
    ) {
        parent::__construct($model);
        $this->modelRabPengajuanPaketKegiatan = $modelRabPengajuanPaketKegiatan;
        $this->modelLogTahapanPengajuanKegiatan = $modelLogTahapanPengajuanKegiatan;
        $this->modelTahapanPengajuanKegiatan    = $modelTahapanPengajuanKegiatan;
        $this->modelDetailLogTahapanPengajuanKegiatan   = $modelDetailLogTahapanPengajuanKegiatan;
    }

    public function getAll()
    {
        $model = $this->model->query()->orderBy('short_id', 'ASC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function getPaginated($search = null, $page = null, $perPage = null, $lang = null)
    {
        $result =   $this->switchLang($search, $page, $perPage, $lang);

        return $this->sendSuccess($result);
    }

    public function getById($id)
    {
        $result =   $this->model->newQuery()->with(['kota'])->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        \DB::beginTransaction();

        try {

            $data = $this->model->newQuery()->create([
                'jenis_kelompok_masyarakat'     =>  $data['jenis_kelompok_masyarakat'],
                'short_id'                      =>  $data['short_id'],
                'flag'                          =>  1,
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

            $read->jenis_kelompok_masyarakat    =   $data['jenis_kelompok_masyarakat'];
            $read->short_id                     =   $data['short_id'];
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

    public function updateRab($id, $dataKomponenRab, $user)
    {
        $result = $this->model->newQuery()->find($id);

        // Memeriksa apakah model ditemukan dan valid
        if (!$result) {
            \Sentry\captureMessage('Validate Message: ' . $user->email_pic . ' Pengajuan tidak ditemukan', \Sentry\Severity::warning());
            return $this->sendError(null, 'Not found', 422);
        }

        // Validasi setiap komponen_rab apakah ada dalam relasi rab_pengajuan_paket_kegiatan
        $komponenIds = collect($dataKomponenRab['komponen_rab'])->pluck('id_komponen_rab');
        $validKomponenRabs = $this->modelRabPengajuanPaketKegiatan->newQuery()->where('pengajuan_kegiatan_id', $id)
            ->whereIn('id', $komponenIds)
            ->pluck('id')
            ->toArray();

        // Memeriksa apakah ada komponen yang tidak ada dalam relasi
        $invalidKomponenRabs = array_diff($komponenIds->toArray(), $validKomponenRabs);

        if (count($invalidKomponenRabs) > 0) {
            return response()->json(['message' => 'Beberapa id_komponen_rab tidak valid untuk paket kegiatan ini', 'invalid_ids' => $invalidKomponenRabs], 422);
        }

        \DB::beginTransaction();

        try {

            $laporan_kegiatan_termin_1 = $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $id)
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Laporan Kegiatan Termin 1');
                })->first();

            if (empty($laporan_kegiatan_termin_1->tanggal_masuk)) {
                # code...
                $konfirmasi_pencairan_dana_termin_1 = $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Konfirmasi Pencairan Dana Termin 1');
                    })->first();
                $laporan_kegiatan_termin_1->tanggal_masuk = $konfirmasi_pencairan_dana_termin_1->tanggal_selesai;
            }

            $laporan_kegiatan_termin_1->tanggal_selesai = date('Y-m-d');
            $laporan_kegiatan_termin_1->save();

            // Create Log Tahapan Pengajuan
            $this->modelDetailLogTahapanPengajuanKegiatan->newQuery()->create([
                'pengajuan_kegiatan_id' => $result->id,
                'tahapan_pengajuan_kegiatan_id' => $laporan_kegiatan_termin_1->tahapan_pengajuan_kegiatan_id,
                'tanggal_masuk' => date("Y-m-d"),
                'tanggal_selesai' => date("Y-m-d")
            ]);

            if (empty($this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $id)
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Verifikasi Laporan Kegiatan Termin 1');
                })->first())) {

                $dataTahapanPengajuanKegiatan = $this->modelTahapanPengajuanKegiatan->newQuery()
                    ->where('deskripsi_kegiatan', 'Verifikasi Laporan Kegiatan Termin 1')->first();

                $this->modelLogTahapanPengajuanKegiatan->newQuery()->create([
                    'pengajuan_kegiatan_id'         => $id,
                    'tahapan_pengajuan_kegiatan_id' => $dataTahapanPengajuanKegiatan->id,
                ]);
            }

            $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $id)
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Verifikasi Laporan Kegiatan Termin 1');
                })
                ->update(['tanggal_masuk' => date("Y-m-d")]);

            $result->user_akseslh->unreadNotifications->markAsRead();

            $result->user_akseslh->notify(new LaporanNotification($result->nomor_pengajuan, $result->user_akseslh->data_pic_kelompok_masyarakat->nama_pic));

            $result->flag  =  6;
            $result->save();

            foreach ($dataKomponenRab['komponen_rab'] as $item) {
                $this->modelRabPengajuanPaketKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->where('id', $item['id_komponen_rab'])
                    ->update([
                        'harga_unit_realisasi'  => $item['harga_unit_realisasi'],
                        'qty_realisasi'         => $item['qty_realisasi'],
                    ]);
            }

            \DB::commit();
            return $this->sendSuccess($result);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : 'Internal Server Error', 500);
        }
    }
}
