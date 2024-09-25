<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Middleware\EnsureHeaderIsValid;
use App\Notifications\PengajuanKegiatanNotification;
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

Route::post('register', [App\Http\Controllers\Authapi\RegisterController::class, 'register']);
Route::post('login', [App\Http\Controllers\Authapi\LoginController::class, 'authenticate']);
Route::post('changePassword', [App\Http\Controllers\Authapi\PasswordController::class, 'changePassword']);

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

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('getNotification', [App\Http\Controllers\Api\Akseslh\NotificationController::class, 'index']);

    Route::post('logout', [App\Http\Controllers\Authapi\LogoutController::class, 'logout']);

    Route::get('tematikKegiatan', [App\Http\Controllers\Api\Akseslh\TematikKegiatanController::class, 'index']);
    Route::get('subTematikKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\SubTematikKegiatanController::class, 'show']);
    Route::Post('subTematikKegiatan', [App\Http\Controllers\Api\Akseslh\SubTematikKegiatanController::class, 'index']);

    Route::get('paketKegiatan/{tematik_kegiatan_id}/{sub_tematik_kegiatan_id}', [App\Http\Controllers\Api\Akseslh\PaketKegiatanController::class, 'byIdTematik']);
    // Route::post('paketKegiatan', [App\Http\Controllers\Api\Akseslh\PaketKegiatanController::class, 'index']);

    Route::middleware(['pembukaan'])->group(function () {
        Route::post('pengajuanKegiatan', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'store']);
        Route::put('pengajuanKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'update']);
    });

    Route::get('getDataRab/{id}', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'getDataRab']);
    Route::get('getDraftPengajuan', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'show']);

    Route::get('getLokasiBidangFolu', [App\Http\Controllers\Api\Akseslh\LokasiBidangFoluController::class, 'index']);

    Route::get('getDataProsesKegiatan', [App\Http\Controllers\Api\Akseslh\DashboardPenerimaManfaatController::class, 'getDataProsesKegiatan']);
    Route::get('getDataRiwayatPengajuan', [App\Http\Controllers\Api\Akseslh\DashboardPenerimaManfaatController::class, 'getDataRiwayatPengajuan']);

    Route::post('informasiPencairanDana', [App\Http\Controllers\Api\Akseslh\InformasiPencairanDanaController::class, 'store']);
    Route::put('informasiPencairanDana/{id}', [App\Http\Controllers\Api\Akseslh\InformasiPencairanDanaController::class, 'update']);

    Route::get('downloadSk/{id}', [App\Http\Controllers\Api\Akseslh\InformasiPencairanDanaController::class, 'showSk']);

    Route::middleware(['ensurerole:verifikator'])->group(function () {
        Route::get('getDataVerifikasiPengajuan', [App\Http\Controllers\Api\Akseslh\VerifikasiPengajuanKegiatanController::class, 'index']);
        Route::get('getDataVerifikasiPengajuanById/{id}', [App\Http\Controllers\Api\Akseslh\VerifikasiPengajuanKegiatanController::class, 'show']);
        Route::put('verifikasiPengajuanKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\VerifikasiController::class, 'update']);
    });
    Route::middleware(['ensurerole:approver'])->group(function () {
        Route::get('getDataValidasiPengajuan', [App\Http\Controllers\Api\Akseslh\ValidasiPengajuanKegiatanController::class, 'index']);
        Route::put('validasiPengajuanKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\ValidasiPengajuanKegiatanController::class, 'update']);
    });

    Route::get('getDataDashboardVerifikator', [App\Http\Controllers\Api\Akseslh\DashboardController::class, 'index']);
    Route::get('getDataValidasiPengajuanById/{id}', [App\Http\Controllers\Api\Akseslh\ValidasiPengajuanKegiatanController::class, 'show']);
});
