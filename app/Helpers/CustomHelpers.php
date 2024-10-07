<?php

if (!function_exists('cekStandarRabPaketKegiatanQty')) {
    function cekStandarRabPaketKegiatanQty($id, $komponenRab)
    {
        return 14;
    }
}

if (!function_exists('tahapanPengajuan')) {
    function tahapanPengajuan($flag)
    {
        // \DB::table('');
        switch ($flag) {
            case 1:
                # code...
                return 'Pengajuan';
                break;

            case 2:
                # code...
                return 'Verifikasi';
                break;

            case 3:
                # code...
                return 'Validasi';
                break;

            case 4:
                # code...
                return 'Informasi Pencairan Dana';
                break;

            case 5:
                return 'Konfirmasi Pencairan Dana Termin 1';
                break;

            case 6:
                return 'Laporan Kegiatan Termin 1';
                break;

            case 7:
                return 'Verifikasi Laporan Kegiatan Termin 1';
                break;

            case 8:
                return 'Konfirmasi Pencairan Dana Termin II';
                break;

            case 9:
                return 'Laporan Akhir Kegiatan';
                break;

            case 10:
                return 'Verifikasi Laporan Akhir Kegiatan';
                break;

            default:
                # code...
                break;
        }
        return 14;
    }
}

if (!function_exists('tahapanPengajuanFlag')) {
    function tahapanPengajuanFlag($deskripsi_kegiatan)
    {
        // \DB::table('');
        switch ($deskripsi_kegiatan) {
            case 'Pengajuan':
                # code...
                return '1';
                break;

            case 'Verifikasi':
                # code...
                return '2';
                break;

            case 'Validasi':
                # code...
                return '3';
                break;

            case 'Informasi Pencairan Dana':
                # code...
                return '4';
                break;

            case 'Konfirmasi Pencairan Dana Termin 1':
                return '5';
                break;

            case 'Laporan Kegiatan Termin 1':
                return '6';
                break;

            case 'Verifikasi Laporan Kegiatan Termin 1':
                return '7';
                break;

            case 'Konfirmasi Pencairan Dana Termin II':
                return '8';
                break;

            case 'Laporan Akhir Kegiatan':
                return '9';
                break;

            case 'Verifikasi Laporan Akhir Kegiatan':
                return '10';
                break;

            default:
                # code...
                return null;
                break;
        }
        return null;
    }
}
