<?php

namespace App\Http\Controllers\Cms\Akseslh;

use App\Http\Controllers\ApiController;
use App\Services\Akseslh\LaporanAkhirKegiatanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaporanAkhirKegiatanController extends ApiController
{
    protected $laporanAkhirKegiatanService;

    public function __construct(
        LaporanAkhirKegiatanService $laporanAkhirKegiatanService,
        Request $request
    ) {
        $this->laporanAkhirKegiatanService   =   $laporanAkhirKegiatanService;
        parent::__construct($request);
    }

    public function index()
    {
        return view("pages.akseslh.laporan-akhir-kegiatan.index");
    }

    public function create()
    {
        return view("pages.akseslh.laporan-kegiatan.create");
    }

    public function edit()
    {
        return view("pages.akseslh.laporan-akhir-kegiatan.edit");
    }

    public function show($id)
    {
        $data   =   $this->laporanAkhirKegiatanService->getById($id);
        return view("pages.akseslh.laporan-kegiatan.show", compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file'                      => 'required|file|mimes:pdf,docx,xlsx',
            'pengajuan_kegiatan_id'     => 'required|array',
            'pengajuan_kegiatan_id.*'   => 'exists:pengajuan_kegiatans,id',
        ], [
            'pengajuan_kegiatan_id.required' => 'Pengajuan kegiatan harus dipilih.',
        ]);

        if ($validator->fails()) {
            # code...
            return $this->sendError(null, $validator->errors()->all(), 422);
        }

        $input = $validator->validated();

        $result =   $this->laporanAkhirKegiatanService->create($input);

        try {
            if ($result->success) {
                $response = $result->data;
                return $this->sendSuccess($response, $result->message, $result->code);
            }

            return $this->sendError($result->data, $result->message, $result->code);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), "", 500);
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'jenis_kelompok_masyarakat'     => 'required|string|max:150',
            'short_id'                      => 'required|numeric|min:0',
            'code_id'                      => 'required|numeric|min:0',
        ]);

        $result =   $this->laporanAkhirKegiatanService->update($id, $input);

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
        $result =   $this->laporanAkhirKegiatanService->delete($id);
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
        $result = $this->laporanAkhirKegiatanService->restore($id);
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
