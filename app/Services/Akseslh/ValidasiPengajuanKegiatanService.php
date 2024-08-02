<?php


namespace App\Services\Akseslh;

use App\Models\PengajuanKegiatan;
use App\Models\TahapanPengajuanKegiatan;
use App\Models\LogTahapanPengajuanKegiatan;
use App\Models\CatatanLogTahapanPengajuanKegiatan;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class ValidasiPengajuanKegiatanService extends AppService implements AppServiceInterface
{
    private $modelTahapanPengajuanKegiatan;
    protected $modelLogTahapanPengajuanKegiatan;
    protected $modelCatatanLogTahapanPengajuanKegiatan;

    public function __construct(
        PengajuanKegiatan $model,
        TahapanPengajuanKegiatan $modelTahapanPengajuanKegiatan,
        LogTahapanPengajuanKegiatan $modelLogTahapanPengajuanKegiatan,
        CatatanLogTahapanPengajuanKegiatan $modelCatatanLogTahapanPengajuanKegiatan
    ) {
        parent::__construct($model);
        $this->modelTahapanPengajuanKegiatan = $modelTahapanPengajuanKegiatan;
        $this->modelLogTahapanPengajuanKegiatan = $modelLogTahapanPengajuanKegiatan;
        $this->modelCatatanLogTahapanPengajuanKegiatan = $modelCatatanLogTahapanPengajuanKegiatan;
    }

    public function getAll()
    {
        $model = $this->model->query()->orderBy('created_at', 'DESC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function apiGetAll()
    {
        $result  = $this->model->newQuery()
            ->where('flag', 3)
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

    public function getAllAttr()
    {
        $result  = $this->model->newQuery()
            ->whereHas('log_tahapan_pengajuan', function ($q) {
                $q->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where(['deskripsi_kegiatan' => 'Validasi',]);
                })->whereNotNull('tanggal_masuk')
                    ->whereNull('tanggal_selesai');
            })
            ->orderBy('created_at', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'                        => $items->id,
                'kelompok_masyarakat'       => $items->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat,
                'tematik_kegiatan'          => $items->paket_kegiatan->master_sub_tematik_kegiatan->tematik_kegiatan->tematik_kegiatan,
                'kegiatan'                  => $items->paket_kegiatan->jenis_kegiatan->jenis_kegiatan . " " . $items->paket_kegiatan->jumlah_peserta . " " . ($items->paket_kegiatan->jumlah_peserta > 50 ? "Orang" : "Hektare"),
                'tanggal_pengajuan'         => $items->created_at->format('d M Y H:i'),
                'tanggal_akhir_verifikasi'  => Carbon::parse($items->created_at)->locale('id')->addDays(7)->format('d M Y'),
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
                'flag'                 => 1,
            ]);

            \DB::commit(); // commit the changes
            return $this->sendSuccess($data);
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

            $this->modelCatatanLogTahapanPengajuanKegiatan->newQuery()
                ->create([
                    'pengajuan_kegiatan_id' => $id,
                    'catatan_log'           => $data['catatan_log'],
                    'flag'                  => "2"
                ]);

            $dataTahapanPengajuanKegiatan = $this->modelTahapanPengajuanKegiatan->newQuery()
                ->orderBy('created_at', 'DESC')->get();
            $dataLogTahapanPengajuanKegiatan = $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->with(['tahapan_pengajuan_kegiatan'])
                ->where('pengajuan_kegiatan_id', $id)
                ->orderBy('created_at', 'DESC')->get();
            if ($data['status'] == 0) {
                $read->flag = '20';
                $read->save();

                $dataSend = array(
                    'nomor_pengajuan' => $read->nomor_pengajuan,
                    'keterangan'      => 'Ditolak',
                    'status'          => '20'
                );
            } else {

                // Update data langsung berdasarkan pengajuan_kegiatan_id
                $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->where('deskripsi_kegiatan', 'Validasi')
                    ->update(['tanggal_selesai' => date("Y-m-d")]);
                $read->flag = '3';
                $read->save();

                $dataSend = array(
                    'nomor_pengajuan' => $read->nomor_pengajuan,
                    'keterangan'      => 'Disetujui',
                    'status'          => '3'
                );
            }

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
}
