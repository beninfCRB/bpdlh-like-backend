<?php

namespace App\Http\Controllers\Cms;

use ZipArchive;
use App\Models\File;
use App\Models\UserAkseslh;
use Illuminate\Http\Request;
use App\Models\PengajuanKegiatan;
use App\Models\KelompokMasyarakat;
use App\Http\Controllers\Controller;
use App\Models\DataPicKelompokMasyarakat;
use App\Services\Akseslh\PengajuanKegiatanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    protected $pengajuanKegiatanService;

    public function __construct(PengajuanKegiatanService $pengajuanKegiatanService)
    {
        $this->pengajuanKegiatanService = $pengajuanKegiatanService;
    }

    public function index(Request $request)
    {
        $flag = $request->query('flag');
        switch ($flag) {
            case 'user':
                # code...
                $total = UserAkseslh::count();
                break;

            case 'pic':
                # code...
                $total = DataPicKelompokMasyarakat::count();
                break;

            case 'kelompok':
                # code...
                $total = KelompokMasyarakat::count();
                break;

            case 'pengajuan':
                # code...
                $total = PengajuanKegiatan::count();
                break;


            default:
                # code...
                $total = 0;
                break;
        }
        return response()->json(['total' => $total]);
    }

    public function download_zip(Request $request)
    {
        $request->validate([
            'tanggal_awal_download'     => 'required|date',
            'tanggal_akhir_download'    => 'required|date',
            'group'                     => 'required'
        ]);

        $input = $request->all();

        if (in_array($request->group, ['proposal'])) {
            # code...
            $loadView = $request->group == 'proposal' ? 'pdf.proposal' : 'pdf.rab';
            $datas = PengajuanKegiatan::whereBetween('created_at', [$input['tanggal_awal_download'], $input['tanggal_akhir_download']])->get();

            foreach ($datas as $data) {
                # code...
                $pdfPath = 'pdfs/' . $data->nomor_pengajuan . '.pdf';
                $pdf = Pdf::loadView($loadView, compact('data'));
                Storage::put($pdfPath, $pdf->output());
            }

            // Buat nama file zip
            $zipFileName = $request->group . '_files_' . time() . '.zip';

            // Tentukan path sementara untuk menyimpan file zip
            $zipFilePath = storage_path('app/public/' . $zipFileName);

            // Membuat instance ZipArchive
            $zip = new ZipArchive();

            // Membuka file zip untuk ditulis
            if ($zip->open($zipFilePath, ZipArchive::CREATE) !== TRUE) {
                return back()->withErrors(['group' => 'Tidak dapat membuat zip file']);
            }

            // Tambahkan file-file ke dalam zip
            foreach ($datas as $data) {
                $filePath = storage_path('app/public/pdfs/' . $data->nomor_pengajuan . '.pdf');
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $data->nomor_pengajuan . '.pdf');
                }
            }

            // Menutup file zip
            $zip->close();
            foreach ($datas as $data) {
                # code...
                $pdfPath = 'pdfs/' . $data->nomor_pengajuan . '.pdf';
                Storage::delete($pdfPath);
            }

            // Mengembalikan file zip untuk di-download
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } elseif (in_array($request->group, ['rab'])) {
            # code...
            $loadView = $request->group == 'proposal' ? 'pdf.proposal' : 'pdf.rab';
            $datas = PengajuanKegiatan::whereBetween('created_at', [$input['tanggal_awal_download'], $input['tanggal_akhir_download']])->get();

            foreach ($datas as $data) {
                # code...
                $rab = null;
                foreach ($data->rab_pengajuan_paket_kegiatans as $item) {
                    # code...
                    $rab[] = [
                        'id_komponen'           => $item->master_komponen_rab->id,
                        'jenis_komponen_rab'    => $item->master_komponen_rab->jenis_komponen->jenis_komponen_rab,
                        'komponen_rab'          => $item->master_komponen_rab->komponen_rab,
                        'satuan'                => $item->master_komponen_rab->satuan->satuan,
                        'harga_unit'            => $item->harga_unit,
                        'nilai_standar'         => $item->harga_unit,
                        'qty'                   => $item->qty,
                    ];
                }
                $collectRab = collect($rab);

                $result = [
                    'komponen_rab'  => $collectRab->groupBy('jenis_komponen_rab'),
                ];

                $pdfPath = 'pdfs/' . $data->nomor_pengajuan . '.pdf';
                $pdf = Pdf::loadView($loadView, compact('result'));
                Storage::put($pdfPath, $pdf->output());
            }

            // Buat nama file zip
            $zipFileName = $request->group . '_files_' . time() . '.zip';

            // Tentukan path sementara untuk menyimpan file zip
            $zipFilePath = storage_path('app/public/' . $zipFileName);

            // Membuat instance ZipArchive
            $zip = new ZipArchive();

            // Membuka file zip untuk ditulis
            if ($zip->open($zipFilePath, ZipArchive::CREATE) !== TRUE) {
                return back()->withErrors(['group' => 'Tidak dapat membuat zip file']);
            }

            // Tambahkan file-file ke dalam zip
            foreach ($datas as $data) {
                $filePath = storage_path('app/public/pdfs/' . $data->nomor_pengajuan . '.pdf');
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $data->nomor_pengajuan . '.pdf');
                }
            }

            // Menutup file zip
            $zip->close();
            foreach ($datas as $data) {
                # code...
                $pdfPath = 'pdfs/' . $data->nomor_pengajuan . '.pdf';
                Storage::delete($pdfPath);
            }

            // Mengembalikan file zip untuk di-download
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } elseif (in_array($request->group, ['foto_ktp', 'profil_kelompok'])) {
            // Ambil file berdasarkan group
            $files = File::where('group', $request->group)
                ->whereHas('pic_kelompok', function ($query) use ($input) {
                    $query->whereHas('user_akseslh.pengajuan_kegiatan', function ($query) use ($input) {
                        // Memastikan tanggal dalam format yang benar dengan menggunakan PHP date()
                        $tanggalAwal = date('Y-m-d 00:00:00', strtotime($input['tanggal_awal_download']));
                        $tanggalAkhir = date('Y-m-d 23:59:59', strtotime($input['tanggal_akhir_download']));

                        // Menambahkan filter dengan tanggal yang sudah diformat
                        $query->whereBetween('created_at', [
                            $tanggalAwal, // Mulai dari awal hari
                            $tanggalAkhir // Sampai akhir hari
                        ]);
                        // $query->whereBetween('created_at', [$input['tanggal_awal_download'] . ' 00:00:00', $input['tanggal_akhir_download'] . ' 23:59:59']);
                    });
                })
                ->get();

            if ($files->isEmpty()) {
                return back()->withErrors(['group' => 'Document Tidak Tersedia']);
            }

            // Buat nama file zip
            $zipFileName = $request->group . '_files_' . time() . '.zip';

            // Tentukan path sementara untuk menyimpan file zip
            $zipFilePath = storage_path('app/public/' . $zipFileName);

            // Membuat instance ZipArchive
            $zip = new ZipArchive();

            // Membuka file zip untuk ditulis
            if ($zip->open($zipFilePath, ZipArchive::CREATE) !== TRUE) {
                return back()->withErrors(['group' => 'Tidak dapat membuat zip file']);
            }

            // Tambahkan file-file ke dalam zip
            $name = 1;
            foreach ($files as $file) {
                $filePath = storage_path('app/public/' . $file->file_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $file->real_name . ' ' . $name . '.' . $file->extension);
                }
                $name++;
            }

            // Menutup file zip
            $zip->close();

            // Mengembalikan file zip untuk di-download
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } else {
            // Ambil file berdasarkan group
            $files = File::where('group', $request->group)
                ->whereHas('pengajuan_kegiatan', function ($query) use ($input) {
                    // Memastikan tanggal dalam format yang benar dengan menggunakan PHP date()
                    $tanggalAwal = date('Y-m-d 00:00:00', strtotime($input['tanggal_awal_download']));
                    $tanggalAkhir = date('Y-m-d 23:59:59', strtotime($input['tanggal_akhir_download']));

                    // Menambahkan filter dengan tanggal yang sudah diformat
                    $query->whereBetween('created_at', [
                        $tanggalAwal, // Mulai dari awal hari
                        $tanggalAkhir // Sampai akhir hari
                    ]);
                    // $query->whereBetween('created_at', [$input['tanggal_awal_download'] . ' 00:00:00', $input['tanggal_akhir_download'] . ' 23:59:59']);
                })
                ->get();

            if ($files->isEmpty()) {
                return back()->withErrors(['group' => 'Document Tidak Tersedia']);
            }

            // Buat nama file zip
            $zipFileName = $request->group . '_files_' . time() . '.zip';

            // Tentukan path sementara untuk menyimpan file zip
            $zipFilePath = storage_path('app/public/' . $zipFileName);

            // Membuat instance ZipArchive
            $zip = new ZipArchive();

            // Membuka file zip untuk ditulis
            if ($zip->open($zipFilePath, ZipArchive::CREATE) !== TRUE) {
                return back()->withErrors(['group' => 'Tidak dapat membuat zip file']);
            }

            // Tambahkan file-file ke dalam zip
            $name = 1;
            foreach ($files as $file) {
                $filePath = storage_path('app/public/' . $file->file_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $file->real_name . ' ' . $name . '.' . $file->extension);
                }
                $name++;
            }

            // Menutup file zip
            $zip->close();

            // Mengembalikan file zip untuk di-download
            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        }
    }
}
