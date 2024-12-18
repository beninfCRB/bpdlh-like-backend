<?php


namespace App\Services\Akseslh;

use App\Models\LogJadwalPembukaan;
use App\Models\MasterDataIndikatorLaporan;
use App\Models\PengajuanKegiatan;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class MasterDataIndikatorLaporanService extends AppService implements AppServiceInterface
{
    protected $modelPengajuanKegiatan, $modelLogJadwalPembukaan;

    public function __construct(MasterDataIndikatorLaporan $model, PengajuanKegiatan $pengajuanKegiatan, LogJadwalPembukaan $modelLogJadwalPembukaan)
    {
        parent::__construct($model);
        $this->modelPengajuanKegiatan = $pengajuanKegiatan;
        $this->modelLogJadwalPembukaan  = $modelLogJadwalPembukaan;
    }

    public function getAll()
    {
        $model = $this->model->query()->with(['jenis_kegiatan', 'sub_tematik_kegiatan'])->orderBy('created_at', 'DESC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
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
                'jenis_kegiatan_id'         =>  $data['jenis_kegiatan_id'],
                'sub_tematik_kegiatan_id'   =>  $data['sub_tematik_kegiatan_id'],
                'nama_indikator'            =>  $data['nama_indikator'],
                'satuan'                    =>  $data['satuan'],
                'tipe_data'                 =>  $data['tipe_data'],
                'flag'                      => 1,
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

            $read->jenis_kegiatan_id        =   $data['jenis_kegiatan_id'];
            $read->sub_tematik_kegiatan_id  =   $data['sub_tematik_kegiatan_id'];
            $read->nama_indikator           =   $data['nama_indikator'];
            $read->satuan                   =   $data['satuan'];
            $read->tipe_data                =   $data['tipe_data'];
            $read->flag                     =   1;
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

    public function apiGetAll($id)
    {
        $pengajuan = $this->modelPengajuanKegiatan->newQuery()->find($id);

        if (!$pengajuan) return $this->sendError(null, 'Not Found', 422);

        $jenis_kegiatan_id          = $pengajuan->paket_kegiatan->jenis_kegiatan_id;
        $sub_tematik_kegiatan_id    = $pengajuan->paket_kegiatan->master_sub_tematik_kegiatan->sub_tematik_kegiatan_id;
        $result = $this->model->newQuery()->where(['jenis_kegiatan_id' => $jenis_kegiatan_id, 'sub_tematik_kegiatan_id' => $sub_tematik_kegiatan_id])->get();
        $tanggal_awal = $this->modelLogJadwalPembukaan->latest()->first()->tanggal_awal ?? null;

        $result->transform(function ($item, $key) {
            return [
                'id'                => $item->id,
                'nama_indikator'    => $item->nama_indikator,
                'satuan'            => $item->satuan,
                'tipe_data'         => $item->tipe_data,
            ];
        });
        $return = [
            'tanggal_awal'  => $tanggal_awal,
            'indikator'     => $result
        ];

        return $this->sendSuccess($return);
    }
}
