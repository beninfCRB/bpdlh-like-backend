<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/login', 'auth.login')->name('login');

Route::get('/', function () {
    return view('welcome');
});

Route::get('check-connection', function () {
    $id = '6684d93e8cb88';
    $secret = 'vc8U5EaZ3bUKV9ka4PsNLrpVGWZVVpyZsAhmnRWO';

    $data = DB::connection('mysql_api')->table('api_credentials')->where(['client_id' => $id, 'client_secret' => $secret])->first();
    return response()->json([
        'data' => $data
    ]);
});

Route::view('/blank', 'pages.blank.index')->name('blank');

Route::view('/home', 'pages.home.index')->name('home');

Route::prefix('akseslh')->group(function () {
    Route::resource('jenis-kegiatan', App\Http\Controllers\Cms\Akseslh\JenisKegiatanController::class);
    Route::resource('jenis-kelompok-masyarakat', App\Http\Controllers\Cms\Akseslh\JenisKelompokMasyarakat::class);

    // Datatable
    Route::get('/data-jenis-kegiatan', [App\Http\Controllers\Datatable\Akseslh\JenisKegiatanController::class, 'getAll']);
    Route::get('/data-jenis-kelompok-masyarakat', [App\Http\Controllers\Datatable\Akseslh\JenisKelompokMasyarakatController::class, 'getAll']);
    // Route::resource('jenis-kegiatan', App\Http\Controllers\Cms\Akseslh\JenisKegiatanController::class);

    // Datatable
    Route::get('/data-jenis-kegiatan', [App\Http\Controllers\Datatable\Akseslh\JenisKegiatanController::class, 'getAll']);
    Route::get('/data-jenis-kelompok-masyarakat', [App\Http\Controllers\Datatable\Akseslh\JenisKegiatanController::class, 'getAll']);

    // View Manual
    // Route::view('jenis-kegiatan', 'pages.akseslh.jenis-kegiatan.index');
    // Route::view('jenis-kegiatan/create', 'pages.akseslh.jenis-kegiatan.create');
    // Route::view('jenis-kegiatan/edit', 'pages.akseslh.jenis-kegiatan.edit');

    Route::view('jenis-kelompok-masyarakat', 'pages.akseslh.jenis-kelompok-masyarakat.index');
    Route::view('jenis-kelompok-masyarakat/create', 'pages.akseslh.jenis-kelompok-masyarakat.create');
    Route::view('jenis-kelompok-masyarakat/edit', 'pages.akseslh.jenis-kelompok-masyarakat.edit');

    Route::view('kelompok-masyarakat', 'pages.akseslh.kelompok-masyarakat.index');
    Route::view('kelompok-masyarakat/create', 'pages.akseslh.kelompok-masyarakat.create');
    Route::view('kelompok-masyarakat/edit', 'pages.akseslh.kelompok-masyarakat.edit');

    Route::view('paket-kegiatan', 'pages.akseslh.paket-kegiatan.index');
    Route::view('paket-kegiatan/create', 'pages.akseslh.paket-kegiatan.create');
    Route::view('paket-kegiatan/edit', 'pages.akseslh.paket-kegiatan.edit');
});
