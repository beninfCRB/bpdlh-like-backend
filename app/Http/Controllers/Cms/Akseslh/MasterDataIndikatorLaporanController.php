<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Services\Akseslh\JenisKegiatanService;
use App\Services\Akseslh\MasterDataIndikatorLaporanService;
use App\Services\Akseslh\SubTematikKegiatanService;

class MasterDataIndikatorLaporanController extends ApiController
{
    protected $masterDataIndikatorLaporanService;
    protected $subTematikKegiatanService;
    protected $jenisKegiatanService;

    public function __construct(
        MasterDataIndikatorLaporanService $masterDataIndikatorLaporanService,
        JenisKegiatanService $jenisKegiatanService,
        SubTematikKegiatanService $subTematikKegiatanService,
        Request $request
    ) {
        $this->masterDataIndikatorLaporanService    =   $masterDataIndikatorLaporanService;
        $this->jenisKegiatanService                 =   $jenisKegiatanService;
        $this->subTematikKegiatanService            = $subTematikKegiatanService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.master-data-indikator-laporan.index");
    }

    public function create()
    {
        $jenisKegiatan = $this->jenisKegiatanService->getAllAttr()->data;
        $subTematikKegiatan = $this->subTematikKegiatanService->getAllAttr()->data;
        return view("pages.akseslh.master-data-indikator-laporan.create", compact('jenisKegiatan', 'subTematikKegiatan'));
    }

    public function edit($id)
    {
        $data           =   $this->masterDataIndikatorLaporanService->getById($id)->data;
        $jenisKegiatan = $this->jenisKegiatanService->getAllAttr()->data;
        $subTematikKegiatan = $this->subTematikKegiatanService->getAllAttr()->data;
        return view("pages.akseslh.master-data-indikator-laporan.edit", compact('data', 'jenisKegiatan', 'subTematikKegiatan'));
    }

    public function show($id)
    {
        $data   =   $this->masterDataIndikatorLaporanService->getById($id);
        return view("pages.akseslh.master-data-indikator-laporan.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'jenis_kegiatan_id'         => 'required|exists:jenis_kegiatans,id',
            'sub_tematik_kegiatan_id'   => 'required|exists:sub_tematik_kegiatans,id',
            'nama_indikator'            => 'required|max:100',
            'satuan'                    => 'required|max:10',
            'tipe_data'                 => 'required|max:10',
        ]);

        $result =   $this->masterDataIndikatorLaporanService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('master-data-indikator-laporan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'jenis_kegiatan_id'         => 'required|exists:jenis_kegiatans,id',
            'sub_tematik_kegiatan_id'   => 'required|exists:sub_tematik_kegiatans,id',
            'nama_indikator'            => 'required|max:100',
            'satuan'                    => 'required|max:10',
            'tipe_data'                 => 'required|max:10',
        ]);

        $result =   $this->masterDataIndikatorLaporanService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('master-data-indikator-laporan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->masterDataIndikatorLaporanService->delete($id);
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
