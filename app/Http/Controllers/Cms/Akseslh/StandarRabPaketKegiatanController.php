<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\JenisKegiatanService;
use App\Services\Akseslh\PaketKegiatanService;
use App\Services\Akseslh\MasterSubTematikKegiatanService;
use App\Http\Requests\Cms\Announcement\UpdateCareerRequest;
use App\Services\Akseslh\MasterKomponenRabService;

class StandarRabPaketKegiatanController extends ApiController
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
        $this->masterSubTematikKegiatanService      =   $masterSubTematikKegiatanService;
        $this->masterKomponenRabService             =   $masterKomponenRabService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.jenis-kegiatan.index");
    }

    public function create()
    {
        return view("pages.akseslh.jenis-kegiatan.create");
    }

    public function edit($id)
    {
        $data                       = $this->paketKegiatanService->getById($id);
        $jenisKegiatan              = $this->jenisKegiatanService->getAllAttr()->data;
        $masterSubTematikKegiatan   = $this->masterSubTematikKegiatanService->getAllAttr()->data;
        $masterKomponenRab          = $this->masterKomponenRabService->getAllAttr()->data;
        return view("pages.akseslh.standar-rab-paket-kegiatan.edit", compact('data', 'jenisKegiatan', 'masterSubTematikKegiatan', 'masterKomponenRab'));
    }

    public function show($id)
    {
        $data   =   $this->jenisKegiatanService->getById($id);
        return view("pages.akseslh.jenis-kegiatan.show", compact('data'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        $input  =   $request->validate([
            'jenis_kegiatan'    => 'required'
        ]);

        $result =   $this->jenisKegiatanService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('jenis-kegiatan.index');
            }
            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'jenis_kegiatan'    => 'required'
        ]);

        $result =   $this->jenisKegiatanService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('jenis-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->jenisKegiatanService->delete($id);
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
