<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class VerifyLogJadwalPembukaan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $jadwal_pembukaan = \DB::table('log_jadwal_pembukaans')->latest()->first();

        if (!$jadwal_pembukaan) return response()->json([
            'code'      => 403,
            'status'    => false,
            'message'   => 'Pembukaan pengajuan telah ditutup. Proposal tidak dapat diajukan.',
        ], 403);

        $date = Carbon::now()->timezone('Asia/Jakarta');
        $awal = $jadwal_pembukaan->tanggal_awal . " " . $jadwal_pembukaan->jam_awal;
        $akhir = $jadwal_pembukaan->tanggal_akhir . " " . $jadwal_pembukaan->jam_akhir;

        if ($date->between($awal, $akhir)) {
            # code...
            return $next($request);
        }
        return response()->json([
            'code'      => 403,
            'status'    => false,
            'message'   => 'Pembukaan pengajuan telah ditutup. Proposal tidak dapat diajukan.',
        ], 403);
    }
}
