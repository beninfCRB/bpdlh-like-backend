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

    Route::get('paketKegiatan/{id}', function () {
        $result = [
            [
                'nama_paket_kegiatan' => 'Paket Sosialisasi',
                'deskripsi_paket_kegiatan'  => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis, quos?',
                'jumlah_peserta'    => [
                    [
                        'id'                => '562e4f13-57fd-4c1f-8b72-18b90c11a8ee',
                        'jumlah_peserta'    => 100,
                    ],
                    [
                        'id'                => '562e4f13-57fd-4c1f-8b72-18b90c11a8ee',
                        'jumlah_peserta'    => 200,
                    ],
                ],
            ],
            [
                'nama_paket_kegiatan' => 'Paket Pelatihan',
                'deskripsi_paket_kegiatan'  => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Omnis, quos?',
                'jumlah_peserta'    => [
                    [
                        'id'                => '562e4f13-57fd-4c1f-8b72-18b90c11a8ee',
                        'jumlah_peserta'    => 100,
                    ],
                    [
                        'id'                => '562e4f13-57fd-4c1f-8b72-18b90c11a8ee',
                        'jumlah_peserta'    => 200,
                    ],
                ],
            ],
        ];

        return response()->json([
            'code' => 200,
            'success'   => true,
            'message'   => 'success',
            'data'      => $result
        ]);
    });
    Route::post('pengajuanKegiatan', [App\Http\Controllers\Api\Akseslh\PengajuanKegiatanController::class, 'store']);
});
