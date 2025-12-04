<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('test-uhuy', function (Request $request) {
    return $request->headers->all();
});

Route::post('register', [App\Http\Controllers\Authapi\RegisterController::class, 'register'])->middleware(['pembukaan']);
Route::post('registerdua', [App\Http\Controllers\Authapi\RegisterController::class, 'register_2_temp']);
Route::post('login', [App\Http\Controllers\Authapi\LoginController::class, 'authenticate']);
Route::post('changePassword', [App\Http\Controllers\Authapi\PasswordController::class, 'changePassword']);
Route::post('getKodeAktivasi', [App\Http\Controllers\Authapi\RegisterController::class, 'getKodeAktivasi']);

Route::post('/resetPassword', [App\Http\Controllers\Authapi\PasswordController::class, 'resetPassword'])->name('password.update');

Route::get('jenisKelompokMasyarakat', [App\Http\Controllers\Api\Akseslh\JenisKelompokMasyarakatController::class, 'index']);
Route::get('kelompokMasyarakat/{id}/byIdJenisKelompokMasyarakat', [App\Http\Controllers\Api\Akseslh\KelompokMasyarakatController::class, 'byIdJenisKelompokMasyarakat']);
Route::get('provinsi', [App\Http\Controllers\Api\Akseslh\ProvinsiController::class, 'index']);
Route::get('provinsi/{id}', [App\Http\Controllers\Api\Akseslh\ProvinsiController::class, 'show']);
Route::get('kota', [App\Http\Controllers\Api\Akseslh\KotaController::class, 'index']);
Route::get('kota/{id}', [App\Http\Controllers\Api\Akseslh\KotaController::class, 'show']);
Route::get('kecamatan', [App\Http\Controllers\Api\Akseslh\KecamatanController::class, 'index']);
Route::get('kecamatan/{id}', [App\Http\Controllers\Api\Akseslh\KecamatanController::class, 'show']);
Route::get('kelurahan', [App\Http\Controllers\Api\Akseslh\KelurahanController::class, 'index']);
Route::get('kelurahan/{id}', [App\Http\Controllers\Api\Akseslh\KelurahanController::class, 'show']);
Route::get('kelompokMasyarakat/{id}/byIdJenisKelompokMasyarakat', [App\Http\Controllers\Api\Akseslh\KelompokMasyarakatController::class, 'byIdJenisKelompokMasyarakat']);
Route::get('getRangeOpening', [App\Http\Controllers\Api\Akseslh\LogJadwalPembukaanController::class, 'index']);
Route::get('log-masa-sanggah', [App\Http\Controllers\Api\Akseslh\LogMasaSanggahController::class, 'index']);
Route::get('jenis-pekerjaan', [App\Http\Controllers\Api\Akseslh\JenisPekerjaanController::class, 'index']);
Route::get('pendidikan', [App\Http\Controllers\Api\Akseslh\PendidikanController::class, 'index']);
Route::get('agama', [App\Http\Controllers\Api\Akseslh\AgamaController::class, 'index']);
Route::get('status-pernikahan', [App\Http\Controllers\Api\Akseslh\StatusPernikahanController::class, 'index']);

Route::get('getDataBank', [App\Http\Controllers\Api\Akseslh\MasterDataBankController::class, 'index']);

Route::get('getRiwayatPengajuan', [App\Http\Controllers\Api\Akseslh\RiwayatPengajuanController::class, 'index']);
Route::get('getDetailRiwayatPengajuan/{id}', [App\Http\Controllers\Api\Akseslh\RiwayatPengajuanController::class, 'show']);
Route::get('getLogKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\DashboardPenerimaManfaatController::class, 'getLogKegiatan']);

Route::get('getTahapanKegiatan', [App\Http\Controllers\Api\Akseslh\TahapanPengajuanKegiatanController::class, 'index']);

Route::get('banner-informasi', [App\Http\Controllers\Api\Akseslh\BannerInformasiController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('getJenisDokumen', [App\Http\Controllers\Api\Akseslh\JenisDokumenController::class, 'index']);

    Route::get('getNotification', [App\Http\Controllers\Api\Akseslh\NotificationController::class, 'index']);

    Route::post('logout', [App\Http\Controllers\Authapi\LogoutController::class, 'logout']);

    Route::get('tematikKegiatan', [App\Http\Controllers\Api\Akseslh\TematikKegiatanController::class, 'index']);
    Route::get('subTematikKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\SubTematikKegiatanController::class, 'show']);
    Route::Post('subTematikKegiatan', [App\Http\Controllers\Api\Akseslh\SubTematikKegiatanController::class, 'index']);

    Route::get('paketKegiatan/{tematik_kegiatan_id}/{sub_tematik_kegiatan_id}', [App\Http\Controllers\Api\Akseslh\PaketKegiatanController::class, 'byIdTematik']);
    // Route::post('paketKegiatan', [App\Http\Controllers\Api\Akseslh\PaketKegiatanController::class, 'index']);

    Route::post('pengajuanKegiatan', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'store']);
    Route::middleware(['pembukaan'])->group(function () {
        Route::put('pengajuanKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'update']);
    });

    Route::middleware(['masa_sanggah'])->group(function () {
        Route::post('revisiPengajuanKegiatan', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'revisi_pengajuan_kegiatan_create']);
        Route::put('revisiPengajuanKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'revisi_pengajuan_kegiatan_update']);
    });

    Route::get('getDataRab/{id}', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'getDataRab']);
    Route::get('getDataRealisasiRab/{id}', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'getDataRealisasiRab']);
    Route::get('getDraftPengajuan', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'show']);

    Route::get('getLokasiBidangFolu', [App\Http\Controllers\Api\Akseslh\LokasiBidangFoluController::class, 'index']);

    Route::get('getDataProsesKegiatan', [App\Http\Controllers\Api\Akseslh\DashboardPenerimaManfaatController::class, 'getDataProsesKegiatan']);
    Route::get('getDataRiwayatPengajuan', [App\Http\Controllers\Api\Akseslh\DashboardPenerimaManfaatController::class, 'getDataRiwayatPengajuan']);

    // Route::post('informasiPencairanDana', [App\Http\Controllers\Api\Akseslh\InformasiPencairanDanaController::class, 'store']);
    Route::put('informasiPencairanDana/{id}', [App\Http\Controllers\Api\Akseslh\InformasiPencairanDanaController::class, 'update']);
    Route::delete('deleteDokumenInformasiPencairanDana/{id}', [App\Http\Controllers\Api\Akseslh\InformasiPencairanDanaController::class, 'deleteDokumen']);

    Route::get('downloadSk/{id}', [App\Http\Controllers\Api\Akseslh\InformasiPencairanDanaController::class, 'showSk']);
    Route::get('downloadProposal/{id}', [App\Http\Controllers\Api\Akseslh\InformasiPencairanDanaController::class, 'showProposal']);

    // User PIC
    Route::get('profile/{id}', [App\Http\Controllers\Api\Akseslh\ProfileController::class, 'show']);
    Route::put('profile/{id}', [App\Http\Controllers\Api\Akseslh\ProfileController::class, 'updateProfilePic'])->middleware(['pembukaan']);
    Route::get('checkProfile', [App\Http\Controllers\Api\Akseslh\ProfileController::class, 'checkProfile']);

    Route::get('getDataDashboardVerifikator', [App\Http\Controllers\Api\Akseslh\DashboardController::class, 'index']);
    Route::get('getDataPenyerapanDana', [App\Http\Controllers\Api\Akseslh\DashboardController::class, 'getDataPenyerapanDana']);

    Route::middleware(['ensurerole:verifikator'])->group(function () {
        Route::get('getDataVerifikasiPengajuan', [App\Http\Controllers\Api\Akseslh\VerifikasiPengajuanKegiatanController::class, 'index']);
        Route::get('getDataVerifikasiPengajuanById/{id}', [App\Http\Controllers\Api\Akseslh\VerifikasiPengajuanKegiatanController::class, 'show']);
        Route::put('verifikasiPengajuanKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\VerifikasiController::class, 'update']);
        Route::delete('profile/{id}', [App\Http\Controllers\Api\Akseslh\ProfileController::class, 'destroy']);
    });

    Route::middleware(['ensurerole:approver'])->group(function () {
        Route::get('getDataValidasiPengajuan', [App\Http\Controllers\Api\Akseslh\ValidasiPengajuanKegiatanController::class, 'index']);
        Route::put('validasiPengajuanKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\ValidasiPengajuanKegiatanController::class, 'update']);
        Route::put('validasiSPTJM/{id}', [App\Http\Controllers\Api\Akseslh\ValidasiPengajuanKegiatanController::class, 'update_sptjm']);
        Route::put('validasiPengajuanKegiatanTermin1/{id}', [App\Http\Controllers\Api\Akseslh\ValidasiPengajuanKegiatanController::class, 'update_termin_1']);
        Route::put('validasiPengajuanKegiatanTermin1TanpaPencairanTermin2/{id}', [App\Http\Controllers\Api\Akseslh\ValidasiPengajuanKegiatanController::class, 'update_termin_1_tanpa_pencairan_termin_2']);
        Route::put('validasiPengajuanKegiatanTahapAkhir/{id}', [App\Http\Controllers\Api\Akseslh\ValidasiPengajuanKegiatanController::class, 'update_tahap_akhir']);
        Route::put('retur-pengajuan-kegiatan/{id}', [App\Http\Controllers\Api\Akseslh\ValidasiPengajuanKegiatanController::class, 'retur_pengajuan_kegiatan']);
    });

    Route::middleware(['ensurerole:pmu-bpdlh'])->group(function () {
        Route::get('getDataPencairan', [App\Http\Controllers\Api\Akseslh\TransaksiPenyaluranController::class, 'getPengajuanKegiatan']);
        Route::post('detailInformasiPencairan', [App\Http\Controllers\Api\Akseslh\TransaksiPenyaluranController::class, 'store']);
    });

    // Laporan Termin 1
    Route::put('realisasiRab/{id}', [App\Http\Controllers\Api\Akseslh\RealisasiRabController::class, 'update']);

    Route::get('getDataMasterDataIndikatorLaporan/{id}', [App\Http\Controllers\Api\Akseslh\MasterDataIndikatorLaporanController::class, 'index']);
    Route::put('indikatorLaporan/{id}', [App\Http\Controllers\Api\Akseslh\IndikatorLaporanKegiatanController::class, 'update']);

    Route::get('getDataValidasiPengajuanById/{id}', [App\Http\Controllers\Api\Akseslh\ValidasiPengajuanKegiatanController::class, 'show']);

    // Laporan Kegiatan
    Route::get('getDokumenLaporanKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\LaporanKegiatanController::class, 'getDokumenLaporanKegiatan']);
    Route::put('uploadDokumenLaporanKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\LaporanKegiatanController::class, 'uploadDokumenLaporanKegiatan']);
    Route::delete('deleteDokumenLaporanKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\LaporanKegiatanController::class, 'deleteDokumenLaporanKegiatan']);

    // Laporan Akhir Kegiatan
    Route::post('laporanAkhir', [App\Http\Controllers\Api\Akseslh\LaporanKegiatanController::class, 'laporan_akhir']);
});
