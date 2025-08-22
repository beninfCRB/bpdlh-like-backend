<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Models\PengajuanKegiatan;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Services\Akseslh\VerifikasiPengajuanKegiatanService;
use App\Services\Akseslh\VerifikasiService;

class VerifikasiPengajuanKegiatanController extends ApiController
{
    protected $verifikasiPengajuanKegiatanService;
    protected $verifikasiService;
    public function __construct(
        VerifikasiService $verifikasiService,
        VerifikasiPengajuanKegiatanService $verifikasiPengajuanKegiatanService,
        Request $request
    ) {
        parent::__construct($request);
        $this->verifikasiPengajuanKegiatanService   =   $verifikasiPengajuanKegiatanService;
        $this->verifikasiService   =   $verifikasiService;
    }

    public function index(Request $request)
    {
        $query = PengajuanKegiatan::query()
            ->select([
                'id',
                'nomor_pengajuan',
                'tanggal_mulai_kegiatan',
                'tanggal_akhir_kegiatan',
                'alamat_kegiatan',
                'paket_kegiatan_id',
                'user_akseslh_id',
                'created_at'
            ])
            ->with([
                'user_akseslh:id,data_pic_kelompok_masyarakat_id',
                'user_akseslh.data_pic_kelompok_masyarakat:id,nama_pic,email_pic,kelompok_masyarakat_id,nomor_identitas_pic,nomor_npwp_pic,alamat_pic,tempat_lahir,tanggal_lahir,nohp_pic,provinsi_pic,kabupaten_pic,kecamatan_pic,kelurahan_pic,agama_id,status_perkawinan_id,jenis_pekerjaan_id,pendidikan_id',
                'user_akseslh.data_pic_kelompok_masyarakat.foto',
                'user_akseslh.data_pic_kelompok_masyarakat.provinsi:id,name',
                'user_akseslh.data_pic_kelompok_masyarakat.kabupaten:id,name',
                'user_akseslh.data_pic_kelompok_masyarakat.kecamatan:id,name',
                'user_akseslh.data_pic_kelompok_masyarakat.kelurahan:id,name',
                'user_akseslh.data_pic_kelompok_masyarakat.agama:id,agama',
                'user_akseslh.data_pic_kelompok_masyarakat.status_perkawinan:id,status_pernikahan',
                'user_akseslh.data_pic_kelompok_masyarakat.jenis_pekerjaan:id,jenis_pekerjaan',
                'user_akseslh.data_pic_kelompok_masyarakat.pendidikan:id,pendidikan',
                'user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat:id,jenis_kelompok_masyarakat_id,kelompok_masyarakat',
                'paket_kegiatan:id,jenis_kegiatan_id,master_sub_tematik_kegiatan_id,jumlah_peserta',
                'paket_kegiatan.jenis_kegiatan' => function ($query) {
                    $query->withTrashed()->select('id', 'jenis_kegiatan');
                },
                'paket_kegiatan.master_sub_tematik_kegiatan:id,tematik_kegiatan_id,sub_tematik_kegiatan_id',
                'paket_kegiatan.master_sub_tematik_kegiatan.tematik_kegiatan:id,tematik_kegiatan',
                'paket_kegiatan.master_sub_tematik_kegiatan.sub_tematik_kegiatan' => function ($query) {
                    $query->withTrashed()->select('id', 'sub_tematik_kegiatan');
                },
                'document:fileable_id,id,group,file_name,file_path'
            ]);

        if ($request->has('search') && !empty($request->search)) {

            $query
                ->whereHas('user_akseslh', function ($q) use ($request) {
                    $q->where('email', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('user_akseslh.data_pic_kelompok_masyarakat', function ($q) use ($request) {
                    $q->where('nama_pic', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('user_akseslh.data_pic_kelompok_masyarakat', function ($q) use ($request) {
                    $q->where('nohp_pic', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('user_akseslh.data_pic_kelompok_masyarakat', function ($q) use ($request) {
                    $q->where('nomor_identitas_pic', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat.jenis', function ($q) use ($request) {
                    $q->where('jenis_kelompok_masyarakat', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat', function ($q) use ($request) {
                    $q->where('kelompok_masyarakat', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('tahapan', function ($q) use ($request) {
                    $q->where('deskripsi_kegiatan', 'like', '%' . $request->search . '%');
                })
                ->orWhere('nomor_pengajuan', 'like', '%' . $request->search . '%');
        }
        $pengajuan_kegiatan = $query->where(['is_active' => 'INACTIVE', 'flag' => 1])->orderBy('created_at', 'DESC')->paginate(10);

        return view("pages.akseslh.verifikasi-pengajuan-kegiatan.index", compact('pengajuan_kegiatan'));
    }

    public function create()
    {
        return view("pages.akseslh.verifikasi-pengajuan-kegiatan.create");
    }

    public function edit($id)
    {
        $data   =   $this->verifikasiPengajuanKegiatanService->getById($id);
        return view("pages.akseslh.verifikasi-pengajuan-kegiatan.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->verifikasiPengajuanKegiatanService->getById($id);
        return view("pages.akseslh.verifikasi-pengajuan-kegiatan.show", compact('data'));
    }

    public function verifikasiPengajuan($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'                    => 'required'
        ]);

        $validator->sometimes('catatan_log', 'required|string|max:10192', function ($input) {
            return $input->status == 0;
        });

        if ($validator->fails()) {
            # code...
            \Sentry\captureMessage('Validate Message: ' . $request->user()->email_pic . ' ' . json_encode($validator->errors()->all()), \Sentry\Severity::warning());
            return $this->sendError(null, $validator->getMessageBag(), 422);
        }

        $input  = $validator->validated();

        $input['user_akseslh_id']      = $request->user()->id;
        $input['user_akseslh']        = $request->user();

        if ($input['status'] == 1 || $input['status'] == '1') {
            $result = $this->verifikasiPengajuanKegiatanService->verifikasiPengajuan($id, $input);
        } elseif ($input['status'] == 0 || $input['status'] == '0') {
            $result = $this->verifikasiService->updateTemp($id, $input);
        } else {
            return $this->sendError(null, 'Verifikasi gagal', 422);
        }

        try {
            if ($result->success) {

                return $this->sendSuccess($result->data, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'jenis_kelompok_masyarakat'     => 'required|string|max:150',
            'short_id'                      => 'required|numeric|min:0',
            'code_id'                       => 'required|numeric|min:0',
        ]);

        $result =   $this->verifikasiPengajuanKegiatanService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('verifikasi-pengajuan-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'jenis_kelompok_masyarakat'     => 'required|string|max:150',
            'short_id'                      => 'required|numeric|min:0',
            'code_id'                      => 'required|numeric|min:0',
        ]);

        $result =   $this->verifikasiPengajuanKegiatanService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('verifikasi-pengajuan-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->verifikasiPengajuanKegiatanService->delete($id);
        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function restore($id)
    {
        $result = $this->verifikasiPengajuanKegiatanService->restore($id);
        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            $this->sendError($exception->getMessage(), "", 500);
        }
    }
}
