<?php

namespace App\Http\Controllers\Cms\Akseslh;

use Illuminate\Http\Request;
use App\Models\PengajuanKegiatan;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ApiController;
use App\Exports\TransaksiPenyaluranExport;
use App\Imports\TransaksiPenyaluranImport;
use App\Exports\TemplateTransaksiPenyaluranExport;
use App\Models\MasterDataBank;
use App\Models\TransaksiPenyaluran;
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

    public function import_view(Request $request)
    {
        $query = PengajuanKegiatan::query();

        if ($request->has('search') && !empty($request->search)) {

            $search = strtolower(str_replace(' ', '', $request->search));

            if ($search === 'ditolak') {
                # code...
                $query->where('flag', 20);
            } else {
                $query
                    ->whereHas('user_akseslh', function ($q) use ($request) {
                        $q->where('email', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('user_akseslh.data_pic_kelompok_masyarakat', function ($q) use ($request) {
                        $q->where('nama_pic', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('user_akseslh.data_pic_kelompok_masyarakat', function ($q) use ($request) {
                        $q->where('nohp_pic', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('user_akseslh.data_pic_kelompok_masyarakat', function ($q) use ($request) {
                        $q->where('nomor_identitas_pic', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat.jenis', function ($q) use ($request) {
                        $q->where('jenis_kelompok_masyarakat', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('user_akseslh.data_pic_kelompok_masyarakat.kelompok_masyarakat', function ($q) use ($request) {
                        $q->where('kelompok_masyarakat', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('tahapan', function ($q) use ($request) {
                        $q->where('deskripsi_kegiatan', 'like', '%' . $request->search . '%');
                    })
                    ->orWhere('nomor_pengajuan', 'like', '%' . $request->search . '%');
            }
        }

        $datas = $query->with('transaksi_penyaluran')
            ->where('flag', 4)->has('transaksi_penyaluran')
            ->orWhere('flag', 7)->has('transaksi_penyaluran', '==', 2)
            ->orderBy('created_at', 'DESC')->paginate(10);

        return view('pages.akseslh.transaksi-penyaluran.import', compact('datas'));
    }

    public function import_edit($id, Request $request)
    {
        $data = TransaksiPenyaluran::with('pengajuan_kegiatan')->where('id', $id)->first();
        $master_data_bank = MasterDataBank::all();
        return view('pages.akseslh.transaksi-penyaluran.import-edit', compact('data', 'master_data_bank'));
    }

    public function import_update($id, Request $request)
    {
        $input  =   $request->validate([
            'master_data_bank_id'       => 'required|exists:master_data_banks,id',
            'pengajuan_kegiatan_id'     => 'required|exists:pengajuan_kegiatans,id',
            'nomor_rekening'            => 'required',
            'nama_pemilik_rekening'     => 'required',
            'nilai_penyaluran'          => 'required',
            'tanggal_penyaluran'        => 'required',
            'surat_keterangan'          => 'required|file|mimes:pdf'
        ]);

        $input['username'] = auth()->user()->id;

        $result =   $this->transaksiPenyaluranService->updateImport($id, $input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('transaksi-penyaluran.import-view');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
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

    public function upload_surat_keterangan(Request $request)
    {
        $input  =   $request->validate([
            'termin'                => 'required|numeric',
            'surat_keterangan.*'      => 'required|file|mimes:pdf|max:4096'
        ]);

        $input['username'] = auth()->user()->id;

        $result =   $this->transaksiPenyaluranService->uploadSuratKeterangan($input);

        try {
            if ($result->success) {
                // Contoh menyimpan session flash
                session()->flash('success', $result->message);
                return redirect()->route('transaksi-penyaluran.import-view');
            }

            return back()->with('error', $result->message);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
