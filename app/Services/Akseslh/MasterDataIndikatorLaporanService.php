<?php


namespace App\Services\Akseslh;

use App\Models\LogJadwalPembukaan;
use App\Models\MasterDataIndikatorLaporan;
use App\Models\MasterIndikator;
use App\Models\PengajuanKegiatan;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class MasterDataIndikatorLaporanService extends AppService implements AppServiceInterface
{
    protected $modelPengajuanKegiatan, $modelLogJadwalPembukaan, $modelMasterIndikator;

    public function __construct(MasterDataIndikatorLaporan $model, PengajuanKegiatan $pengajuanKegiatan, LogJadwalPembukaan $modelLogJadwalPembukaan, MasterIndikator $modelMasterIndikator)
    {
        parent::__construct($model);
        $this->modelPengajuanKegiatan = $pengajuanKegiatan;
        $this->modelLogJadwalPembukaan  = $modelLogJadwalPembukaan;
        $this->modelMasterIndikator     = $modelMasterIndikator;
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

        // $indikator = $pengajuan->indikator_laporan_kegiatan;

        /**
         *
         * every() digunakan untuk memastikan semua item memiliki master_indikator_id yang valid.
         *Jika ada satu saja item yang master_data_indikator_laporan-nya null atau master_indikator_id-nya null, maka blok else akan dijalankan.
         */
        // if ($indikator && $indikator->count() > 0 && $indikator->every(function ($item) {
        //     return $item->master_data_indikator_laporan && $item->master_data_indikator_laporan->master_indikator_id !== null;
        // })) {
        //     # code...
        //     $result = $pengajuan->indikator_laporan_kegiatan->map(function ($item, $key) {
        //         return [
        //             'id'                => $item->master_data_indikator_laporan->master_indikator_id,
        //             'nama_indikator'    => $item->master_data_indikator_laporan->nama_indikator,
        //             'satuan'            => $item->master_data_indikator_laporan->satuan,
        //             'tipe_data'         => $item->master_data_indikator_laporan->tipe_data,
        //             'nilai_laporan'     => $item->nilai_laporan,
        //         ];
        //     });
        // } else {
        //     $result = $this->modelMasterIndikator->newQuery()->get();

        //     $result->transform(function ($item, $key) {
        //         return [
        //             'id'                => $item->id,
        //             'nama_indikator'    => $item->nama_indikator,
        //             'satuan'            => $item->satuan,
        //             'tipe_data'         => $item->tipe_data,
        //             'nilai_laporan'     => null,
        //         ];
        //     });
        // }
        $result = $this->modelMasterIndikator->newQuery()->orderBy('sort', 'ASC')->get();

        $result->transform(function ($item, $key) {
            return [
                'id'                => $item->id,
                'nama_indikator'    => $item->nama_indikator,
                'satuan'            => $item->satuan,
                'tipe_data'         => $item->tipe_data,
                'nilai_laporan'     => null,
            ];
        });

        $return = [
            'tanggal_mulai_kegiatan'    => $pengajuan->tanggal_mulai_kegiatan,
            'tanggal_akhir_kegiatan'    => $pengajuan->tanggal_akhir_kegiatan,
            'alamat_kegiatan_realisasi' => $pengajuan->alamat_kegiatan_realisasi,
            'longitude'                 => $pengajuan->longitude,
            'latitude'                  => $pengajuan->latitude,
            'capaian_output'            => $pengajuan->capaian_output,
            'capaian_outcome'           => $pengajuan->capaian_outcome,
            'kendala_kegiatan'          => $pengajuan->kendala_kegiatan,
            'testimonial'               => $pengajuan->testimonial->testimonial ?? null,
            'jumlah_pengembalian'       => $pengajuan->pengembalian()->sum('jumlah_pengembalian') ?? 0,
            'indikator_kegiatan'        => $result,
        ];


        return $this->sendSuccess($return);
    }
}
