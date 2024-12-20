<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerifyLogMasaSanggah
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
        $jadwal_pembukaan = \DB::table('log_masa_sanggahs')->latest()->first();

        if (!$jadwal_pembukaan) return response()->json([
            'code'      => 403,
            'status'    => false,
            'message'   => 'Masa sanggah belum dibuka atau sudah ditutup.',
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
            'message'   => 'Masa sanggah belum dibuka atau sudah ditutup.',
        ], 403);
    }
}
