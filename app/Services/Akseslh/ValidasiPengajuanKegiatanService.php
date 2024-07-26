<?php


namespace App\Services\Akseslh;

use App\Models\LogTahapanPengajuanKegiatan;
use App\Models\PengajuanKegiatan;
use App\Models\TahapanPengajuanKegiatan;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class ValidasiPengajuanKegiatanService extends AppService implements AppServiceInterface
{
    private $modelTahapanPengajuanKegiatan;

    public function __construct(PengajuanKegiatan $model, TahapanPengajuanKegiatan $modelTahapanPengajuanKegiatan)
    {
        parent::__construct($model);
        $this->modelTahapanPengajuanKegiatan = $modelTahapanPengajuanKegiatan;
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

            if ($read->paket_kegiatan_id != $data['paket_kegiatan_id']) {
                # code...
                $temp = $read->log_tahapan_pengajuan;
                $arrayTemp = $temp->toArray();

                $tahapanPengajuanKegiatan = $this->modelTahapanPengajuanKegiatan->get();

                $dataTahapanPengajuanKegiatan = [];
                foreach ($tahapanPengajuanKegiatan as $item) {
                    # code...
                    if ($item->deskripsi_kegiatan == "Validasi") {
                        # code...
                        $dataTahapanPengajuanKegiatan[] = [
                            'tahapan_pengajuan_kegiatan_id' => $item->id,
                            'tanggal_masuk'                 => isset($arrayTemp[array_search($item->id, $temp->pluck('tahapan_pengajuan_kegiatan_id')->toArray())]['tanggal_masuk']) ?  $arrayTemp[array_search($item->id, $temp->pluck('tahapan_pengajuan_kegiatan_id')->toArray())]['tanggal_masuk'] : null,
                            'tanggal_selesai'               => Carbon::now()->format('Y-m-d'),
                            'flag'                          => 1,
                        ];
                    } else {
                        $dataTahapanPengajuanKegiatan[] = [
                            'tahapan_pengajuan_kegiatan_id' => $item->id,
                            'tanggal_masuk'                 => isset($arrayTemp[array_search($item->id, $temp->pluck('tahapan_pengajuan_kegiatan_id')->toArray())]['tanggal_masuk']) ?  $arrayTemp[array_search($item->id, $temp->pluck('tahapan_pengajuan_kegiatan_id')->toArray())]['tanggal_masuk'] : null,
                            'tanggal_selesai'               => isset($arrayTemp[array_search($item->id, $temp->pluck('tahapan_pengajuan_kegiatan_id')->toArray())]['tanggal_selesai']) ?  $arrayTemp[array_search($item->id, $temp->pluck('tahapan_pengajuan_kegiatan_id')->toArray())]['tanggal_selesai'] : null,
                            'flag'                          => 1,
                        ];
                    }
                }

                $read->log_tahapan_pengajuan()->saveMany(
                    collect($dataTahapanPengajuanKegiatan)->map(function ($tahapanPengajuanKegiatan) {
                        return new LogTahapanPengajuanKegiatan($tahapanPengajuanKegiatan);
                    })
                );

                dd($read->log_tahapan_pengajuan);
            } else {
            }

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
}
