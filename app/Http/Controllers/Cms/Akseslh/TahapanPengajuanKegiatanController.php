<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\TahapanPengajuanKegiatanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TahapanPengajuanKegiatanController extends ApiController
{
    protected $tahapanPengajuanKegiatanService;

    public function __construct(
        TahapanPengajuanKegiatanService $tahapanPengajuanKegiatanService,
        Request $request
    ) {
        $this->tahapanPengajuanKegiatanService   =   $tahapanPengajuanKegiatanService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.tahapan-pengajuan-kegiatan.index");
    }

    public function create()
    {
        return view("pages.akseslh.tahapan-pengajuan-kegiatan.create");
    }

    public function edit($id)
    {
        $data   =   $this->tahapanPengajuanKegiatanService->getById($id)->data;
        return view("pages.akseslh.tahapan-pengajuan-kegiatan.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->tahapanPengajuanKegiatanService->getById($id);
        return view("pages.akseslh.tahapan-pengajuan-kegiatan.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'deskripsi_tahapan'    => 'required'
        ]);

        $result =   $this->tahapanPengajuanKegiatanService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('tahapan-pengajuan-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'deskripsi_tahapan'    => 'required'
        ]);

        $result =   $this->tahapanPengajuanKegiatanService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('tahapan-pengajuan-kegiatan.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->tahapanPengajuanKegiatanService->delete($id);
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
