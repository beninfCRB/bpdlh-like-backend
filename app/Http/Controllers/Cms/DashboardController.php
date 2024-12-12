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

class DashboardController extends Controller
{
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
        $request->validate(['group' => 'required']);

        // Ambil file berdasarkan group
        $files = File::where('group', $request->group)->get();

        if ($files->isEmpty()) {
            return response()->json(['message' => 'No files found for this group.'], 404);
        }

        // Buat nama file zip
        $zipFileName = $request->group . '_files_' . time() . '.zip';

        // Tentukan path sementara untuk menyimpan file zip
        $zipFilePath = storage_path('app/public/' . $zipFileName);

        // Membuat instance ZipArchive
        $zip = new ZipArchive();

        // Membuka file zip untuk ditulis
        if ($zip->open($zipFilePath, ZipArchive::CREATE) !== TRUE) {
            return response()->json(['message' => 'Could not create zip file.'], 500);
        }

        // Tambahkan file-file ke dalam zip
        foreach ($files as $file) {
            $filePath = storage_path('app/public/' . $file->file_path);
            if (file_exists($filePath)) {
                $zip->addFile($filePath, $file->real_name);
            }
        }

        // Menutup file zip
        $zip->close();

        // Mengembalikan file zip untuk di-download
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
