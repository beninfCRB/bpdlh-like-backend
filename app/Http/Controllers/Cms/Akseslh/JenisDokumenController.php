<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\JenisDokumenService;
use App\Services\Akseslh\TahapanPengajuanKegiatanService;
use Illuminate\Http\Request;

class JenisDokumenController extends ApiController
{
    protected $jenisDokumenService;
    protected $tahapanPengajuanKegiatanService;

    public function __construct(
        JenisDokumenService $jenisDokumenService,
        TahapanPengajuanKegiatanService $tahapanPengajuanKegiatanService,
        Request $request
    ) {
        $this->jenisDokumenService          =   $jenisDokumenService;
        $this->tahapanPengajuanKegiatanService     = $tahapanPengajuanKegiatanService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.jenis-dokumen.index");
    }

    public function create()
    {
        $tahapanPengajuanKegiatan = $this->tahapanPengajuanKegiatanService->getAllAttr()->data;
        return view("pages.akseslh.jenis-dokumen.create", compact('tahapanPengajuanKegiatan'));
    }

    public function edit($id)
    {
        $data   =   $this->jenisDokumenService->getById($id)->data;
        $tahapanPengajuanKegiatan = $this->tahapanPengajuanKegiatanService->getAllAttr()->data;
        return view("pages.akseslh.jenis-dokumen.edit", compact('data', 'tahapanPengajuanKegiatan'));
    }

    public function show($id)
    {
        $data   =   $this->jenisDokumenService->getById($id);
        return view("pages.akseslh.jenis-dokumen.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'tahapan_pengajuan_kegiatan_id' => 'required|exists:tahapan_pengajuan_kegiatans,id',
            'jenis_dokumen'                 => 'required',
            'dokumen'                       => 'nullable|file|mimes:pdf,docx,xlsx,jpg,png|max:30495'
        ]);

        $result =   $this->jenisDokumenService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('jenis-dokumen.index');
            }
            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'tahapan_pengajuan_kegiatan_id' => 'required|exists:tahapan_pengajuan_kegiatans,id',
            'jenis_dokumen'                 => 'required',
            'dokumen'                       => 'nullable|file|mimes:pdf,docx,xlsx,jpg,png|max:30495'
        ]);

        $result =   $this->jenisDokumenService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('jenis-dokumen.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->jenisDokumenService->delete($id);
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

    public function delete_dokumen($id)
    {
        $result = $this->jenisDokumenService->deleteDokumen($id);
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
        $result = $this->jenisDokumenService->restore($id);
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
