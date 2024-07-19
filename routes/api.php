<?php

use App\Models\DataPicKelompokMasyarakat;
use App\Models\User;
use App\Models\UserAkseslh;
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
// Route::post('register', function (Request $request) {
//     $user = DataPicKelompokMasyarakat::where(['email_pic' => $request->email_pic])->first();
//     $token = $user->user_akseslh->createToken('auth')->plainTextToken;

//     return response()->json([
//         'token' => $token
//     ]);
// });
Route::post('login', [App\Http\Controllers\Authapi\LoginController::class, 'authenticate']);

Route::get('tematikKegiatan', [App\Http\Controllers\Api\Akseslh\TematikKegiatanController::class, 'index']);

Route::post('subTematikKegiatan', function () {
    $result = [
        [
            'id'    => '562e4f13-57fd-4c1f-8b72-18b90c11a8ee',
            'tematik_kegiatan_id'   => '562e4f13-57fd-4c1f-8b72-18b90c11a8ee',
            'sub_tematik_kegiatan' => 'Penghijauan',
            'image' => [
                'id'    => 'a713a38a-a23e-4cc5-adea-78c4c9adb65f',
                'group' => 'image',
                'visibility' => 'private',
                'file_name' => '9f234eb2-ef68-4707-95a3-49ad6a5e9868.png',
                'file_path' => 'uploads/2024/07/9f234eb2-ef68-4707-95a3-49ad6a5e9868.png',
                'fileable_id'   => '562e4f13-57fd-4c1f-8b72-18b90c11a8ee',
            ],
        ], [
            'id'    => '562e4f13-57fd-4c1f-8b72-18b90c11a8ee',
            'tematik_kegiatan_id'   => '562e4f13-57fd-4c1f-8b72-18b90c11a8ee',
            'sub_tematik_kegiatan' => 'Energy',
            'image' => [
                'id'    => 'a713a38a-a23e-4cc5-adea-78c4c9adb65f',
                'group' => 'image',
                'visibility' => 'private',
                'file_name' => '9f234eb2-ef68-4707-95a3-49ad6a5e9868.png',
                'file_path' => 'uploads/2024/07/9f234eb2-ef68-4707-95a3-49ad6a5e9868.png',
                'fileable_id'   => '562e4f13-57fd-4c1f-8b72-18b90c11a8ee',
            ],
        ]
    ];

    return response()->json([
        'code' => 200,
        'success' => true,
        'message'   => 'success',
        'data'  => $result
    ]);
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [App\Http\Controllers\Authapi\LogoutController::class, 'logout']);

    Route::get('jenisKelompokMasyarakat', [App\Http\Controllers\Api\Akseslh\JenisKelompokMasyarakatController::class, 'index']);
    Route::get('kelompokMasyarakat/{id}/byIdJenisKelompokMasyarakat', [App\Http\Controllers\Api\Akseslh\KelompokMasyarakatController::class, 'byIdJenisKelompokMasyarakat']);
    Route::get('tematikKegiatan', [App\Http\Controllers\Api\Akseslh\TematikKegiatanController::class, 'index']);
    Route::get('subTematikKegiatan/{id}/byIdTematikKegiatan', function () {
        $result = [
            [
                'id'    => '562e4f13-57fd-4c1f-8b72-18b90c11a8ee',
                'sub_tematik_kegiatan' => 'Penghijauan',
                'image' => [
                    'id'    => 'a713a38a-a23e-4cc5-adea-78c4c9adb65f',
                    'group' => 'image',
                    'visibility' => 'private',
                    'file_name' => '9f234eb2-ef68-4707-95a3-49ad6a5e9868.png',
                    'file_path' => 'uploads/2024/07/9f234eb2-ef68-4707-95a3-49ad6a5e9868.png',
                    'fileable_id'   => '562e4f13-57fd-4c1f-8b72-18b90c11a8ee',
                ],
            ], [
                'id'    => '562e4f13-57fd-4c1f-8b72-18b90c11a8ee',
                'sub_tematik_kegiatan' => 'Energy',
                'image' => [
                    'id'    => 'a713a38a-a23e-4cc5-adea-78c4c9adb65f',
                    'group' => 'image',
                    'visibility' => 'private',
                    'file_name' => '9f234eb2-ef68-4707-95a3-49ad6a5e9868.png',
                    'file_path' => 'uploads/2024/07/9f234eb2-ef68-4707-95a3-49ad6a5e9868.png',
                    'fileable_id'   => '562e4f13-57fd-4c1f-8b72-18b90c11a8ee',
                ],
            ]
        ];

        return response()->json([
            'code' => 200,
            'success' => true,
            'message'   => 'success',
            'data'  => $result
        ]);
    });
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
    Route::post('pengajuanKegiatan', function (Request $request) {
        return response()->json([
            'code'      => 200,
            'success'   => true,
            'message'   => 'success',
            'data'      => [
                'nomor_pengajuan'   => '12345678',
            ]
        ]);
    });
});

Route::get('/user', function (Request $request) {
    return User::all();
    return $request->user();
});
