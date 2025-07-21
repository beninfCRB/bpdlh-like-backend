<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\LaporanKegiatanService;
use Illuminate\Http\Request;

class LaporanKegiatanController extends ApiController
{
    protected $laporanKegiatanService;

    public function __construct(
        LaporanKegiatanService $laporanKegiatanService,
        Request $request
    ) {
        $this->laporanKegiatanService   =   $laporanKegiatanService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.laporan-kegiatan.index");
    }

    public function create()
    {
        return view("pages.akseslh.laporan-kegiatan.create");
    }

    public function edit($id)
    {
        $data   =   $this->laporanKegiatanService->getById($id);
        return view("pages.akseslh.laporan-kegiatan.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->laporanKegiatanService->getById($id);
        return view("pages.akseslh.laporan-kegiatan.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'jenis_kelompok_masyarakat'     => 'required|string|max:150',
            'short_id'                      => 'required|numeric|min:0',
            'code_id'                       => 'required|numeric|min:0',
        ]);

        $result =   $this->laporanKegiatanService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('laporan-kegiatan.index');
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

        $result =   $this->laporanKegiatanService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('laporan-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id)
    {
        $result =   $this->laporanKegiatanService->delete($id);
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
        $result = $this->laporanKegiatanService->restore($id);
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
