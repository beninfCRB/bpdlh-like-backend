<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\TahapanSalurValidation;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\JenisKegiatanService;
use App\Services\Akseslh\PaketKegiatanService;
use App\Services\Akseslh\MasterKomponenRabService;
use App\Services\Akseslh\MasterSubTematikKegiatanService;

class PaketKegiatanController extends ApiController
{
    protected $paketKegiatanService;
    protected $jenisKegiatanService;
    protected $masterSubTematikKegiatanService;
    protected $masterKomponenRabService;

    public function __construct(
        PaketKegiatanService $paketKegiatanService,
        JenisKegiatanService $jenisKegiatanService,
        MasterSubTematikKegiatanService $masterSubTematikKegiatanService,
        MasterKomponenRabService $masterKomponenRabService,
        Request $request
    ) {
        $this->paketKegiatanService   =   $paketKegiatanService;
        $this->jenisKegiatanService   =   $jenisKegiatanService;
        $this->masterSubTematikKegiatanService   =   $masterSubTematikKegiatanService;
        $this->masterKomponenRabService             =   $masterKomponenRabService;
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
        $masterKomponenRab          = $this->masterKomponenRabService->getAllAttr()->data;
        return view("pages.akseslh.paket-kegiatan.create", compact('jenisKegiatan', 'masterSubTematikKegiatan', 'masterKomponenRab'));
    }

    public function edit($id)
    {
        $data                       = $this->paketKegiatanService->getById($id)->data;
        $jenisKegiatan              = $this->jenisKegiatanService->getAllAttr()->data;
        $masterSubTematikKegiatan   = $this->masterSubTematikKegiatanService->getAllAttr()->data;
        $masterKomponenRab          = $this->masterKomponenRabService->getAllAttr()->data;
        // dd($data->standar_rab_paket_kegiatan->where('master_komponen_rab_id', 'c3a12676-b793-41bd-924e-e489aca11588')->first());
        // dd(array_search('c3a12676-b793-41bd-924e-e489aca11588', $data->standar_rab_paket_kegiatan->toArray()));
        foreach ($masterKomponenRab as $item) {
            # code...
            // dd($item);
        }
        // dd($masterKomponenRab, $data->standar_rab_paket_kegiatan->toArray());
        return view("pages.akseslh.paket-kegiatan.edit", compact('data', 'jenisKegiatan', 'masterSubTematikKegiatan', 'masterKomponenRab'));
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
            'porsi_pencairan'                   => ['nullable', new TahapanSalurValidation],
            'komponen_rab'                      => 'nullable',
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
            'porsi_pencairan'                   => ['nullable', new TahapanSalurValidation],
            'komponen_rab'                      => 'nullable'
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
