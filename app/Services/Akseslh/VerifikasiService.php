<?php


namespace App\Services\Akseslh;


use App\Models\PengajuanKegiatan;
use App\Models\TahapanPengajuanKegiatan;
use App\Models\LogTahapanPengajuanKegiatan;
use App\Models\CatatanLogTahapanPengajuanKegiatan;
use App\Models\DetailLogTahapanPengajuanKegiatan;
use App\Notifications\VerifikasiValidasiDitolakNotification;
use App\Notifications\VerifikasiValidasiNotification;
use App\Services\AppService;
use App\Services\AppServiceInterface;
use App\Services\EmailPhpService;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Facades\DataTables;

class VerifikasiService extends AppService implements AppServiceInterface
{
    protected $modelTahapanPengajuanKegiatan;
    protected $modelLogTahapanPengajuanKegiatan;
    protected $modelCatatanLogTahapanPengajuanKegiatan;
    protected $emailService;
    protected $modelDetailLogTahapanPengajuanKegiatan;

    public function __construct(
        PengajuanKegiatan $model,
        TahapanPengajuanKegiatan $modelTahapanPengajuanKegiatan,
        LogTahapanPengajuanKegiatan $modelLogTahapanPengajuanKegiatan,
        CatatanLogTahapanPengajuanKegiatan $modelCatatanLogTahapanPengajuanKegiatan,
        EmailPhpService $emailPhpService,
        DetailLogTahapanPengajuanKegiatan $modelDetailLogTahapanPengajuanKegiatan
    ) {
        parent::__construct($model);
        $this->modelTahapanPengajuanKegiatan = $modelTahapanPengajuanKegiatan;
        $this->modelLogTahapanPengajuanKegiatan = $modelLogTahapanPengajuanKegiatan;
        $this->modelCatatanLogTahapanPengajuanKegiatan = $modelCatatanLogTahapanPengajuanKegiatan;
        $this->emailService = $emailPhpService;
        $this->modelDetailLogTahapanPengajuanKegiatan   = $modelDetailLogTahapanPengajuanKegiatan;
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
        $result =   $this->model->newQuery()->find($id);

        return $this->sendSuccess($result);
    }

    public function create($data)
    {
        return $this->sendSuccess();
    }

    public function update($id, $data)
    {
        $read   =   $this->model->newQuery()->find($id);

        if (!$read) return $this->sendError(null, 'Not Found');

        if ($read->flag != 1) {
            # code...
            return $this->sendError(null, 'Not Allowed', 403);
        }

        $total = 0;

        foreach ($read->rab_pengajuan_paket_kegiatans as $items) {
            # code...
            $total += ($items->qty * $items->harga_unit);
        }

        \DB::beginTransaction();

        try {

            $idLog = $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $id)
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Verifikasi');
                })->first()->id;

            $this->modelCatatanLogTahapanPengajuanKegiatan->newQuery()
                ->create([
                    'log_tahapan_pengajuan_kegiatan_id' => $idLog,
                    'catatan_log'                       => $data['catatan_log']
                ]);

            if ($data['status'] == 0) {
                // $read->user_akseslh = $data['user_akselh_id'];
                $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Verifikasi');
                    })
                    ->update(['tanggal_selesai' => date("Y-m-d"), 'user_akseslh_id' => $data['user_akselh_id']]);
                $read->flag = '20';
                $read->save();

                $dataSend = array(
                    'nomor_pengajuan' => $read->nomor_pengajuan,
                    'catatan_log'       => $data['catatan_log'],
                    'keterangan'      => 'Ditolak',
                    'status'          => 20
                );
                $read->user_akseslh->unreadNotifications->markAsRead();

                $read->user_akseslh->notify(new VerifikasiValidasiDitolakNotification($read->nomor_pengajuan, $read->user_akseslh->data_pic_kelompok_masyarakat->nama_pic, $total, $data['catatan_log']));

                $this->emailService->verifikasiValidasiDitolak($read->user_akseslh, 'Pengajuan Ditolak', $dataSend, null, 'mail.verifikasi-pengajuan-kegiatan-ditolak');
            } else {

                // Update data langsung berdasarkan pengajuan_kegiatan_id
                $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Verifikasi');
                    })
                    ->update(['tanggal_selesai' => date("Y-m-d"), 'user_akseslh_id' => $data['user_akselh_id']]);

                $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Validasi');
                    })
                    ->update(['tanggal_masuk' => date("Y-m-d")]);

                $read->flag = '2';
                $read->save();

                $dataSend = array(
                    'nomor_pengajuan' => $read->nomor_pengajuan,
                    'keterangan'      => 'Disetujui',
                    'status'          => 2
                );
                $read->user_akseslh->unreadNotifications->markAsRead();

                $read->user_akseslh->notify(new VerifikasiValidasiNotification($read->nomor_pengajuan, $read->user_akseslh->data_pic_kelompok_masyarakat->nama_pic, $total));
            }


            \DB::commit(); // commit the changes
            return $this->sendSuccess($dataSend);
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

    public function updateTemp($id, $data, $emailSend = false)
    {
        $read = $this->model->newQuery()->find($id);

        if (!$read) {
            \Sentry\captureMessage('Validate Message: ' . $data['user_akseslh']->email . ' Pengajuan tidak ditemukan', \Sentry\Severity::warning());
            return $this->sendError(null, 'Not Found', 422);
        }

        if ($read->flag != 1) {
            \Sentry\captureMessage('Validate Message: ' . $data['user_akseslh']->email . ' Flag pengajuan tidak sesuai', \Sentry\Severity::warning());
            return $this->sendError(null, 'Not Allowed', 403);
        }

        // Menghitung total dari rab_pengajuan_paket_kegiatans dengan eager loading
        $total = $read->rab_pengajuan_paket_kegiatans->sum(function ($items) {
            return $items->qty * $items->harga_unit;
        });

        \DB::beginTransaction();

        try {
            // Mengambil LogTahapanPengajuanKegiatan untuk deskripsi 'Verifikasi'
            $logTahapan = $this->modelLogTahapanPengajuanKegiatan->newQuery()
                ->where('pengajuan_kegiatan_id', $id)
                ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                    $q->where('deskripsi_kegiatan', 'Verifikasi');
                })
                ->first();

            if (!$logTahapan) {
                \DB::rollBack();
                return $this->sendError(null, 'Tahapan tidak ditemukan', 422);
            }

            // Membuat Catatan Log Tahapan Pengajuan Kegiatan
            $this->modelCatatanLogTahapanPengajuanKegiatan->create([
                'log_tahapan_pengajuan_kegiatan_id' => $logTahapan->id,
                'catatan_log'                       => $data['catatan_log']
            ]);

            // Create Log Tahapan Pengajuan
            $this->modelDetailLogTahapanPengajuanKegiatan->newQuery()->create([
                'pengajuan_kegiatan_id'         => $read->id,
                'tahapan_pengajuan_kegiatan_id' => $logTahapan->tahapan_pengajuan_kegiatan_id,
                'tanggal_masuk'                 => date("Y-m-d"),
                'tanggal_selesai'               => date("Y-m-d"),
                'user_akseslh_id'               => $data['user_akseslh_id']
            ]);

            // Update status tergantung dari status yang diberikan
            $statusUpdate = $data['status'] == 0 ? 20 : 2;
            $keterangan = $data['status'] == 0 ? 'Ditolak' : 'Disetujui';

            // Update log tahapan berdasarkan status
            $logTahapan->update(['tanggal_selesai' => now(), 'user_akseslh_id' => $data['user_akseslh_id']]);

            // Update status pengajuan
            $read->update(['flag' => $statusUpdate]);

            // Persiapkan data untuk pengiriman notifikasi dan email
            $dataSend = [
                'nomor_pengajuan' => $read->nomor_pengajuan,
                'catatan_log'     => $data['catatan_log'] ?? null,
                'keterangan'      => $keterangan,
                'status'          => $statusUpdate
            ];

            // Mark notifications as read and send notification
            $read->user_akseslh->unreadNotifications->markAsRead();
            if ($emailSend) {
                # code...
                $notification = $data['status'] == 0
                    ? new VerifikasiValidasiDitolakNotification($read->nomor_pengajuan, $read->user_akseslh->data_pic_kelompok_masyarakat->nama_pic, $total, $data['catatan_log'])
                    : new VerifikasiValidasiNotification($read->nomor_pengajuan, $read->user_akseslh->data_pic_kelompok_masyarakat->nama_pic, $total, $data['catatan_log']);
                $read->user_akseslh->notify($notification);
            }

            if ($data['status'] != 0) {
                $this->modelLogTahapanPengajuanKegiatan->newQuery()
                    ->where('pengajuan_kegiatan_id', $id)
                    ->whereHas('tahapan_pengajuan_kegiatan', function ($q) {
                        $q->where('deskripsi_kegiatan', 'Validasi');
                    })
                    ->update(['tanggal_masuk' => now()]);
                if ($emailSend) {
                    # code...
                    $this->emailService->verifikasiPengajuanKegiatan($read->user_akseslh, 'Pengajuan Kegiatan Terverifikasi', $dataSend, '', 'mail.verifikasi-pengajuan-kegiatan-diterima');
                }
            } else {
                if ($emailSend) {
                    $dataSend['judul_pengajuan_kegiatan'] = $read->judul_pengajuan_kegiatan;
                    $dataSend['kelompok_masyarakat'] = $read->user_akseslh->data_pic_kelompok_masyarakat->kelompok_masyarakat->kelompok_masyarakat;
                    $dataSend['nama_pic'] = $read->user_akseslh->data_pic_kelompok_masyarakat->nama_pic;
                    $dataSend['total'] = $read->rab_pengajuan_paket_kegiatans->sum(function ($item) {
                        return $item->qty * $item->harga_unit;
                    });
                    // Kirim email
                    $this->emailService->verifikasiValidasiDitolak(
                        $read->user_akseslh,
                        'Pengajuan Ditolak',
                        $dataSend,
                        null,
                        'mail.verifikasi-pengajuan-kegiatan-ditolak'
                    );
                }
            }

            \DB::commit(); // commit the changes
            return $this->sendSuccess($dataSend);
        } catch (\Exception $exception) {
            \DB::rollBack(); // rollback the changes
            return $this->sendError(null, $this->debug ? $exception->getMessage() : null, 500);
        }
    }
}
