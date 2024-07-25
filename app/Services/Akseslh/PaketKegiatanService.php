<?php


namespace App\Services\Akseslh;

use App\Models\MasterSubTematikKegiatan;
use App\Models\PaketKegiatan;
use App\Models\TahapSalurPaketKegiatan;
use App\Models\StandarRabPaketKegiatan;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;
use Ramsey\Uuid\Uuid;

class PaketKegiatanService extends AppService implements AppServiceInterface
{
    protected $modelTahapSalurPaketKegiatan;
    public function __construct(PaketKegiatan $model, TahapSalurPaketKegiatan $tahapSalurPaketKegiatan)
    {
        $this->modelTahapSalurPaketKegiatan = $tahapSalurPaketKegiatan;
        parent::__construct($model);
    }

    public function getAll()
    {
        $model = $this->model->query()->with(['jenis_kegiatan', 'master_sub_tematik_kegiatan.tematik_kegiatan', 'master_sub_tematik_kegiatan.sub_tematik_kegiatan'])->orderBy('created_at', 'DESC');

        return DataTables::eloquent($model)->addIndexColumn()->toJson();
    }

    public function getAllAttr()
    {
        $result  = $this->model->newQuery()
            ->orderBy('created_at', 'ASC')
            ->get();

        $result->transform(function ($items, $key) {
            return [
                'id'                    => $items->id,
                'nama_paket_kegiatan'   => $items->nama_paket_kegiatan,
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
            $dataPaketKegiatan = $this->model->newQuery()->create([
                'jenis_kegiatan_id'                 => $data['jenis_kegiatan_id'],
                'master_sub_tematik_kegiatan_id'    => $data['master_sub_tematik_kegiatan_id'],
                'nama_paket_kegiatan'               => $data['nama_paket_kegiatan'],
                'deskripsi_paket_kegiatan'          => $data['deskripsi_paket_kegiatan'],
                'jumlah_peserta'                    => $data['jumlah_peserta'],
                'quota_paket_kegiatan'              => $data['quota_paket_kegiatan'],
                'pagu_paket_kegiatan'               => $data['pagu_paket_kegiatan'],
                'tahap_pencairan_paket_kegiatan'    => $data['tahap_pencairan_paket_kegiatan'],
                'flag'                              => 1,
            ]);

            $tahap_salur = 1;
            $dataTahapSalur = [];
            foreach ($data['porsi_pencairan'] as $item) {
                # code...
                $dataTahapSalur[] = [
                    'tahap_salur'       => $tahap_salur,
                    'porsi_pencairan'   => (int) $item,
                    'flag' => 1,
                ];

                $tahap_salur++;
            }

            foreach ($data['komponen_rab'] as $item) {
                # code...
                if (isset($item['id']) && isset($item['qty']) && isset($item['harga_unit'])) {
                    # code...
                    $dataKomponenRab[] = [
                        'master_komponen_rab_id'    => $item['id'],
                        'standar_qty'               => $item['qty'],
                        'standar_harga_unit'        => $item['harga_unit'],
                        'flag'                      => 1,
                    ];
                }
            }

            $dataPaketKegiatan->tahap_salur_paket_kegiatan()->saveMany(
                collect($dataTahapSalur)->map(function ($tahapSalur) {
                    return new TahapSalurPaketKegiatan($tahapSalur);
                })
            );

            $dataPaketKegiatan->standar_rab_paket_kegiatan()->saveMany(
                collect($dataKomponenRab)->map(function ($komponenRab) {
                    return new StandarRabPaketKegiatan($komponenRab);
                })
            );

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

            $read->jenis_kegiatan_id                 = $data['jenis_kegiatan_id'];
            $read->master_sub_tematik_kegiatan_id    = $data['master_sub_tematik_kegiatan_id'];
            $read->nama_paket_kegiatan               = $data['nama_paket_kegiatan'];
            $read->deskripsi_paket_kegiatan          = $data['deskripsi_paket_kegiatan'];
            $read->jumlah_peserta                    = $data['jumlah_peserta'];
            $read->quota_paket_kegiatan              = $data['quota_paket_kegiatan'];
            $read->pagu_paket_kegiatan               = $data['pagu_paket_kegiatan'];
            $read->tahap_pencairan_paket_kegiatan    = $data['tahap_pencairan_paket_kegiatan'];
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

    function apiGetAll($data)
    {
        $result  = $this->model
            ->select('nama_paket_kegiatan')

            ->whereHas('master_sub_tematik_kegiatan', function ($q) use ($data) {
                $q->where([
                    'tematik_kegiatan_id'       => $data['tematik_kegiatan_id'],
                    'sub_tematik_kegiatan_id'   => $data['sub_tematik_kegiatan_id'],
                ]);
            })
            ->with(['peserta' => function ($query) {
                $query->select('nama_paket_kegiatan', 'id', 'deskripsi_paket_kegiatan', 'jumlah_peserta')->orderBy('jumlah_peserta', 'ASC');
            }])
            ->distinct()
            ->get();

        return $this->sendSuccess($result);
    }
}
