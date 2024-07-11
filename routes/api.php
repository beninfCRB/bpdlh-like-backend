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
Route::post('logout', [App\Http\Controllers\Authapi\LogoutController::class, 'logout']);

Route::get('jenisKelompokMasyarakat', [App\Http\Controllers\Api\Akseslh\JenisKelompokMasyarakatController::class, 'index']);
Route::get('kelompokMasyarakat/{id}/byIdJenisKelompokMasyarakat', [App\Http\Controllers\Api\Akseslh\KelompokMasyarakatController::class, 'byIdJenisKelompokMasyarakat']);
Route::get('tematikKegiatan', [App\Http\Controllers\Api\Akseslh\TematikKegiatanController::class, 'index']);
Route::get('paketKegiatan/{id}/byIdTematikKegiatan', [App\Http\Controllers\Api\Akseslh\PaketKegiatanController::class, 'byIdTematikKegiatan']);

// Route::get('pengajuan-kegiatan', function () {
//     $user = App\Models\AkseslhUserEksternal::find('220d0de5-7cd8-4986-8595-56e70478decc');
//     // $user->notify(new App\Notifications\PengajuanKegiatanNotification());
//     foreach ($user->Notifications as $notification) {
//         return response()->json($notification->data);
//     }
// });

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('pengajuan-kegiatan', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'store']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
