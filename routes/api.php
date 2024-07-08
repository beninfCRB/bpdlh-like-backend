<?php

use App\Models\User;
use Illuminate\Http\Request;
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

Route::apiResource('jenisKelompokMasyarakat', App\Http\Controllers\Api\Akseslh\JenisKelompokMasyarakatController::class)->except(['store', 'update', 'edit', 'show', 'destroy']);
Route::get('kelompok-masyarakat/{id}/byIdJenisKelompokMasyarakat', [App\Http\Controllers\Api\Akseslh\KelompokMasyarakatController::class, 'byIdJenisKelompokMasyarakat']);

Route::post('test-post', function (Request $request) {
    dd($request->all());
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('pengajuan-kegiatan', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'store']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
