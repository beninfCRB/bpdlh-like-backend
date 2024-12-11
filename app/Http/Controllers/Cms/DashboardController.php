<?php

namespace App\Http\Controllers\Cms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DataPicKelompokMasyarakat;
use App\Models\KelompokMasyarakat;
use App\Models\PengajuanKegiatan;
use App\Models\UserAkseslh;

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
}
