<?php

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Middleware\EnsureHeaderIsValid;
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


Route::get('generate-pdf', function () {
    // Generate the PDF from a view
    $pdf = Pdf::loadView('pdf.template-small-grant', []);

    // Path to save the PDF
    $filePath = 'public/uploads/' . '123' . '.pdf';

    // Save the PDF to the storage folder
    Storage::put($filePath, $pdf->output());
});

Route::get('test-email', [App\Http\Controllers\Authapi\RegisterController::class, 'test_email'])->excludedMiddleware(EnsureHeaderIsValid::class);

Route::post('register', [App\Http\Controllers\Authapi\RegisterController::class, 'register']);
Route::post('login', [App\Http\Controllers\Authapi\LoginController::class, 'authenticate']);

Route::get('jenisKelompokMasyarakat', [App\Http\Controllers\Api\Akseslh\JenisKelompokMasyarakatController::class, 'index']);
Route::get('kelompokMasyarakat/{id}/byIdJenisKelompokMasyarakat', [App\Http\Controllers\Api\Akseslh\KelompokMasyarakatController::class, 'byIdJenisKelompokMasyarakat']);
Route::get('provinsi', [App\Http\Controllers\Api\Akseslh\ProvinsiController::class, 'index']);
Route::get('kota', [App\Http\Controllers\Api\Akseslh\KotaController::class, 'index']);
Route::get('kecamatan', [App\Http\Controllers\Api\Akseslh\KecamatanController::class, 'index']);
Route::get('kelurahan', [App\Http\Controllers\Api\Akseslh\KelurahanController::class, 'index']);
Route::get('kelompokMasyarakat/{id}/byIdJenisKelompokMasyarakat', [App\Http\Controllers\Api\Akseslh\KelompokMasyarakatController::class, 'byIdJenisKelompokMasyarakat']);
Route::put('validasiPengajuanKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\ValidasiPengajuanKegiatanController::class, 'update']);
Route::put('verifikasiPengajuanKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\VerifikasiController::class, 'update']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('check-role', function () {
        return response()->json([
            'message'   => 'Ini Maker'
        ]);
    })->middleware('ensurerole:maker');

    Route::post('logout', [App\Http\Controllers\Authapi\LogoutController::class, 'logout']);

    Route::get('tematikKegiatan', [App\Http\Controllers\Api\Akseslh\TematikKegiatanController::class, 'index']);
    Route::get('subTematikKegiatan/{id}', [App\Http\Controllers\Api\Akseslh\SubTematikKegiatanController::class, 'show']);
    Route::Post('subTematikKegiatan', [App\Http\Controllers\Api\Akseslh\SubTematikKegiatanController::class, 'index']);

    Route::get('paketKegiatan/{tematik_kegiatan_id}/{sub_tematik_kegiatan_id}', [App\Http\Controllers\Api\Akseslh\PaketKegiatanController::class, 'byIdTematik']);
    // Route::post('paketKegiatan', [App\Http\Controllers\Api\Akseslh\PaketKegiatanController::class, 'index']);
    Route::post('pengajuanKegiatan', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'store']);
    Route::get('getDataRab/{id}', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'getDataRab']);

    Route::get('getLokasiBidangFolu', [App\Http\Controllers\Api\Akseslh\LokasiBidangFoluController::class, 'index']);

    Route::middleware(['ensurerole:verifikator'])->group(function () {
        Route::get('getDataVerifikasiPengajuan', [App\Http\Controllers\Api\Akseslh\VerifikasiPengajuanKegiatanController::class, 'index']);
        Route::get('getDataVerifikasiPengajuanById/{id}', [App\Http\Controllers\Api\Akseslh\VerifikasiPengajuanKegiatanController::class, 'show']);
    });
    Route::middleware(['ensurerole:approver'])->group(function () {
        Route::get('getDataValidasiPengajuan', [App\Http\Controllers\Api\Akseslh\ValidasiPengajuanKegiatanController::class, 'index']);
        Route::get('getDataValidasiPengajuanById/{id}', [App\Http\Controllers\Api\Akseslh\ValidasiPengajuanKegiatanController::class, 'show']);
    });
});
