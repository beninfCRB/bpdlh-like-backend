<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\JenisKegiatanService;
use App\Services\Akseslh\PaketKegiatanService;
use App\Services\Akseslh\MasterSubTematikKegiatanService;

class PaketKegiatanController extends ApiController
{
    protected $paketKegiatanService;
    protected $jenisKegiatanService;
    protected $masterSubTematikKegiatanService;

    public function __construct(
        PaketKegiatanService $paketKegiatanService,
        JenisKegiatanService $jenisKegiatanService,
        MasterSubTematikKegiatanService $masterSubTematikKegiatanService,
        Request $request
    ) {
        $this->paketKegiatanService   =   $paketKegiatanService;
        $this->jenisKegiatanService   =   $jenisKegiatanService;
        $this->masterSubTematikKegiatanService   =   $masterSubTematikKegiatanService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.paket-kegiatan.index");
    }

    public function create()
    {
        $jenisKegiatan              = $this->jenisKegiatanService->getAllAttr()->data;
        $masterSubTematikKegiatan   = $this->masterSubTematikKegiatanService->getAllAttr()->data;
        return view("pages.akseslh.paket-kegiatan.create", compact('jenisKegiatan', 'masterSubTematikKegiatan'));
    }

    public function edit($id)
    {
        $data                       = $this->paketKegiatanService->getById($id);
        $jenisKegiatan              = $this->jenisKegiatanService->getAllAttr()->data;
        $masterSubTematikKegiatan   = $this->masterSubTematikKegiatanService->getAllAttr()->data;
        return view("pages.akseslh.paket-kegiatan.edit", compact('data', 'jenisKegiatan', 'masterSubTematikKegiatan'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'jenis_kegiatan_id'                 => 'required|exists:jenis_kegiatans,id',
            'master_sub_tematik_kegiatan_id'    => 'required|exists:master_sub_tematik_kegiatans,id',
            'nama_paket_kegiatan'               => 'required|string',
            'deskripsi_paket_kegiatan'          => 'required|string',
            'jumlah_peserta'                    => 'required',
            'quota_paket_kegiatan'              => 'required|numeric',
            'pagu_paket_kegiatan'               => 'required',
            'tahap_pencairan_paket_kegiatan'    => 'required|numeric',
        ]);

        $result =   $this->paketKegiatanService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('paket-kegiatan.index');
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
            'master_sub_tematik_kegiatan_id'    => 'required|exists:master_sub_tematik_kegiatans,id',
            'nama_paket_kegiatan'               => 'required|string',
            'deskripsi_paket_kegiatan'          => 'required|string',
            'jumlah_peserta'                    => 'required|string',
            'quota_paket_kegiatan'              => 'required|numeric',
            'pagu_paket_kegiatan'               => 'required',
            'tahap_pencairan_paket_kegiatan'    => 'required|numeric',
        ]);

        $result =   $this->paketKegiatanService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('paket-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->paketKegiatanService->delete($id);
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
