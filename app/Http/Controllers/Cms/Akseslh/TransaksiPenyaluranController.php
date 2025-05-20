<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Models\PengajuanKegiatan;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ApiController;
use App\Exports\TransaksiPenyaluranExport;
use App\Imports\TransaksiPenyaluranImport;
use App\Exports\TemplateTransaksiPenyaluranExport;
use App\Services\Akseslh\TransaksiPenyaluranService;

class TransaksiPenyaluranController extends ApiController
{
    protected $transaksiPenyaluranService;

    public function __construct(
        TransaksiPenyaluranService $transaksiPenyaluranService,
        Request $request
    ) {
        $this->transaksiPenyaluranService   =   $transaksiPenyaluranService;
        parent::__construct($request);
    }

    public function export(Request $request)
    {
        $input = $request->validate([
            'tanggal_awal'      => 'required',
            'tanggal_akhir'     => 'required|after_or_equal:tanggal_awal'
        ]);

        return Excel::download(new TransaksiPenyaluranExport($input), 'transaksi_penyaluran.xlsx');
    }

    public function template(Request $request)
    {
        return Excel::download(new TemplateTransaksiPenyaluranExport(), 'template_transaksi_penyaluran.xlsx');
    }

    public function import_view()
    {
        $datas = PengajuanKegiatan::with('transaksi_penyaluran')->whereIn('flag', [4, 7])->has('transaksi_penyaluran')->paginate(10);
        return view('pages.akseslh.transaksi-penyaluran.import', compact('datas'));
    }

    public function import(Request $request)
    {
        try {
            //code...
            $request->validate([
                'file'      => 'required|mimes:xlsx,xls',
                'termin'    => 'required|numeric',
            ]);

            Excel::import(new TransaksiPenyaluranImport, $request->file('file'));

            return back()->with('success', 'Data berhasil diimpor!');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', 'Gagal mengimpor data: ' . $th->getMessage());
        }
    }

    public function index()
    {
        return view("pages.akseslh.transaksi-penyaluran.index");
    }

    public function create()
    {
        return view("pages.akseslh.transaksi-penyaluran.create");
    }

    public function edit($id)
    {
        $data   =   $this->transaksiPenyaluranService->getById($id);
        return view("pages.akseslh.transaksi-penyaluran.edit", compact('data'));
    }

    public function show($id)
    {
        $data   =   $this->transaksiPenyaluranService->getById($id);
        return view("pages.akseslh.transaksi-penyaluran.show", compact('data'));
    }

    public function store(Request $request)
    {
        $input  =   $request->validate([
            'jenis_kegiatan'    => 'required',
            'short_id'                      => 'required|numeric|min:0',
            'code_id'                       => 'required|numeric|min:0',
        ]);

        $result =   $this->transaksiPenyaluranService->create($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('transaksi-penyaluran.index');
            }
            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        $input  =   $request->validate([
            'jenis_kegiatan'    => 'required',
            'short_id'                      => 'required|numeric|min:0',
            'code_id'                       => 'required|numeric|min:0',
        ]);

        $result =   $this->transaksiPenyaluranService->update($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('transaksi-penyaluran.index');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result =   $this->transaksiPenyaluranService->delete($id);
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
