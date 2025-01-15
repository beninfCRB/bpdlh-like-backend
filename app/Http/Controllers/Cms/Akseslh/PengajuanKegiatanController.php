<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Exports\PengajuanKegiatanExport;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\File;
use App\Services\Akseslh\PengajuanKegiatanService;
use App\Services\Akseslh\PaketKegiatanService;
use App\Services\Akseslh\UserEksternalService;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function index()
    {
        $group = File::select('group')->distinct()->get();
        return view("pages.akseslh.pengajuan-kegiatan.index", compact('group'));
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
            'tanggal_akhir'     => 'required|after:tanggal_awal'
        ]);

        return Excel::download(new PengajuanKegiatanExport($input), 'pengajuan_kegiatan.xlsx');
    }

    public function export_proposal($id)
    {
        $data   =   $this->PengajuanKegiatanService->getById($id);
        // dd($data);
        $pdf = Pdf::loadView('pdf.proposal', compact('data'));
        return $pdf->stream();
    }

    public function export_rab($id)
    {
        $data   =   $this->PengajuanKegiatanService->getDataRab($id);
        // dd($data);
        $pdf = Pdf::loadView('pdf.rab', compact('data'));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream();
    }

    public function dokumen($id)
    {
        $data = $this->PengajuanKegiatanService->getDokumen($id);
        return view('pages.akseslh.pengajuan-kegiatan.dokumen', compact('data'));
    }
}
