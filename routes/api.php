<?php

use Illuminate\Support\Facades\Route;

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

Route::get('jenisKelompokMasyarakat', [App\Http\Controllers\Api\Akseslh\JenisKelompokMasyarakatController::class, 'index']);
Route::get('kelompokMasyarakat/{id}/byIdJenisKelompokMasyarakat', [App\Http\Controllers\Api\Akseslh\KelompokMasyarakatController::class, 'byIdJenisKelompokMasyarakat']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [App\Http\Controllers\Authapi\LogoutController::class, 'logout']);

    Route::get('tematikKegiatan', [App\Http\Controllers\Api\Akseslh\TematikKegiatanController::class, 'index']);
    Route::Post('subTematikKegiatan', [App\Http\Controllers\Api\Akseslh\SubTematikKegiatanController::class, 'index']);

    Route::post('paketKegiatan', [App\Http\Controllers\Api\Akseslh\PaketKegiatanController::class, 'index']);
    Route::post('pengajuanKegiatan', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'store']);
});
