<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Exports\PengajuanKegiatanExport;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\File;
use App\Models\PengajuanKegiatan;
use App\Models\TahapanPengajuanKegiatan;
use App\Services\Akseslh\PengajuanKegiatanService;
use App\Services\Akseslh\PaketKegiatanService;
use App\Services\Akseslh\UserEksternalService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PengajuanKegiatanController extends ApiController
{
    protected $PengajuanKegiatanService;
    protected $PaketKegiatanService;
    protected $UserEksternalService;

    public function __construct(
        PengajuanKegiatanService $PengajuanKegiatanService,
        PaketKegiatanService $PaketKegiatanService,
        UserEksternalService $UserEksternalService,
        Request $request
    ) {
        $this->PengajuanKegiatanService     =   $PengajuanKegiatanService;
        $this->PaketKegiatanService         =   $PaketKegiatanService;
        $this->UserEksternalService         =   $UserEksternalService;
        parent::__construct($request);
    }

    public function index(Request $request)
    {
        $group = File::select('group')->distinct()->get();
        $query = PengajuanKegiatan::query();

        if ($request->has('search') && !empty($request->search)) {

            $search = strtolower(str_replace(' ', '', $request->search));

            if ($search === 'ditolak') {
                # code...
                $query->where('flag', 20);
            } else {
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
        }

        if ($request->has('tahapan') && !empty($request->tahapan)) {
            $query->where('flag', (int) $request->tahapan);
        }

        // 🔥 Tambahkan subquery untuk cek rab double
        $query->addSelect([
            'rab_double' => \DB::table('rab_pengajuan_paket_kegiatans')
                ->selectRaw('CASE WHEN COUNT(*) > COUNT(DISTINCT komponen_rab_id) THEN 1 ELSE 0 END')
                ->whereColumn('pengajuan_kegiatan_id', 'pengajuan_kegiatans.id')
                ->groupBy('pengajuan_kegiatan_id')
                ->limit(1)
        ]);

        $pengajuan_kegiatan = $query->orderBy('created_at', 'DESC')->paginate(10);
        $flag = TahapanPengajuanKegiatan::orderBy('sort')->get();
        return view("pages.akseslh.pengajuan-kegiatan.index", compact('group', 'pengajuan_kegiatan', 'flag'));
    }

    public function create()
    {
        $PaketKegiatan = $this->PaketKegiatanService->getAllAttr()->data;
        $UserEksternal = $this->UserEksternalService->getAllAttr()->data;
        return view("pages.akseslh.pengajuan-kegiatan.create", compact('PaketKegiatan', 'UserEksternal'));
    }

    public function edit($id)
    {
        $data   =   $this->PengajuanKegiatanService->getById($id);
        $PaketKegiatan = $this->PaketKegiatanService->getAllAttr()->data;
        $UserEksternal = $this->UserEksternalService->getAllAttr()->data;
        return view("pages.akseslh.pengajuan-kegiatan.edit", compact('data', 'PaketKegiatan', 'UserEksternal'));
    }

    public function show($id)
    {
        $data   =   $this->PengajuanKegiatanService->getById($id);
        return view("pages.akseslh.pengajuan-kegiatan.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'jenis_kegiatan_id'                 => 'required|exists:jenis_kegiatans,id',
            'tematik_kegiatan_id'               => 'required|exists:tematik_kegiatans,id',
            'nama_paket_kegiatan'               => 'required|string',
            'deskripsi_paket_kegiatan'          => 'required|string',
            'jumlah_peserta'                    => 'required',
            'quota_paket_kegiatan'              => 'required|numeric',
            'pagu_paket_kegiatan'               => 'required',
            'tahap_pencairan_paket_kegiatan'    => 'required|numeric',
        ]);

        $result =   $this->PengajuanKegiatanService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('pengajuan-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'jenis_kegiatan_id'                 => 'required|exists:jenis_kegiatans,id',
            'nama_paket_kegiatan'               => 'required|string',
            'deskripsi_paket_kegiatan'          => 'required|string',
            'jumlah_peserta'                    => 'required|string',
            'quota_paket_kegiatan'              => 'required|numeric',
            'pagu_paket_kegiatan'               => 'required',
            'tahap_pencairan_paket_kegiatan'    => 'required|numeric',
        ]);

        $result =   $this->PengajuanKegiatanService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('pengajuan-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->PengajuanKegiatanService->delete($id);
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

    public function export(Request $request)
    {
        $input = $request->validate([
            'tanggal_awal'      => 'required',
            'tanggal_akhir'     => 'required|after:tanggal_awal',
            'flag'              => 'nullable',
        ]);

        return Excel::download(new PengajuanKegiatanExport($input), 'pengajuan_kegiatan.xlsx');
    }

    public function export_proposal($id)
    {
        $data   =   $this->PengajuanKegiatanService->getById($id);
        if (!$data->data) return back()->with('error', 'Data tidak ditemukan');
        $data = $data->data;

        $pdf = Pdf::loadView('pdf.proposal', compact('data'));
        return $pdf->stream();
    }

    public function export_rab($id)
    {
        $result   =   $this->PengajuanKegiatanService->getDataRab($id);
        if (!$result->data) return back()->with('error', 'Data tidak ditemukan');
        $result = $result->data;

        $pdf = Pdf::loadView('pdf.rab', compact('result'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream();
    }

    public function dokumen($id)
    {
        // $data = $this->PengajuanKegiatanService->getDokumen($id);
        $data = PengajuanKegiatan::with(['user_akseslh' => function ($q) {
            $q->withTrashed();
        }, 'user_akseslh.data_pic_kelompok_masyarakat' => function ($q) {
            $q->withTrashed();
        }])->find($id);
        return view('pages.akseslh.pengajuan-kegiatan.dokumen', compact('data'));
    }

    public function update_dokumen($id, Request $request)
    {
        $input  =   $request->validate([
            'jenis_dokumen'     => 'required',
            'document'          => 'required|file|mimes:pdf|max:10192',
            'dokumen_pendukung' => 'required|file|mimes:pdf|max:2048'
        ]);

        $result =   $this->PengajuanKegiatanService->updateDokumen($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('pengajuan-kegiatan.document', $id);
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update_sptjm($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomor_sptjm' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError($validator->errors(), "Validation Error", 422);
        }

        $result = $this->PengajuanKegiatanService->updateSptjm($id, $validator->validated());

        try {
            if (!$result->success) {
                return $this->sendError($result->data, $result->message, $result->code);
            }
            return $this->sendSuccess(null, "Berhasil update sptjm " . $id, 200);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }
}
