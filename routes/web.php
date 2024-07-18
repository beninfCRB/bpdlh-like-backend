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
Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'authenticate']);

Route::get('/', function () {
        return redirect()->route('home');
});
Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->middleware('auth');
Route::view('/blank', 'pages.blank.index')->name('blank');

Route::middleware(['auth'])->group(function () {


        Route::view('/home', 'pages.home.index')->name('home');

        Route::prefix('akseslh')->group(function () {
                Route::resource('jenis-kegiatan', App\Http\Controllers\Cms\Akseslh\JenisKegiatanController::class);
                Route::resource('jenis-kelompok-masyarakat', App\Http\Controllers\Cms\Akseslh\JenisKelompokMasyarakat::class);
                Route::resource('kelompok-masyarakat', App\Http\Controllers\Cms\Akseslh\KelompokMasyarakatController::class);
                Route::resource('paket-kegiatan', App\Http\Controllers\Cms\Akseslh\PaketKegiatanController::class);
                Route::resource('pic-kelompok-masyarakat', App\Http\Controllers\Cms\Akseslh\DataPicKelompokMasyarakatController::class);
                Route::resource('tahapan-pengajuan-kegiatan', App\Http\Controllers\Cms\Akseslh\TahapanPengajuanKegiatanController::class);
                Route::resource('tematik-kegiatan', App\Http\Controllers\Cms\Akseslh\TematikKegiatanController::class);
                Route::resource('sub-tematik-kegiatan', App\Http\Controllers\Cms\Akseslh\SubTematikKegiatanController::class);
                Route::resource('satuan', App\Http\Controllers\Cms\Akseslh\SatuanController::class);
                Route::resource('jenis-komponen-rab', App\Http\Controllers\Cms\Akseslh\JenisKomponenRabController::class);
                Route::resource('master-komponen-rab', App\Http\Controllers\Cms\Akseslh\MasterKomponenRabController::class);


                // Datatable
                Route::get('/data-jenis-kegiatan', [App\Http\Controllers\Datatable\Akseslh\JenisKegiatanController::class, 'getAll'])->name('data-jenis-kegiatan');
                Route::get('/data-tahapan-pengajuan-kegiatan', [App\Http\Controllers\Datatable\Akseslh\TahapanPengajuanKegiatanController::class, 'getAll'])->name('data-tahapan-pengajuan-kegiatan');
                Route::get('/data-jenis-kelompok-masyarakat', [App\Http\Controllers\Datatable\Akseslh\JenisKelompokMasyarakatController::class, 'getAll'])->name('data-jenis-kelompok-masyarakat');
                Route::get('/data-kelompok-masyarakat', [App\Http\Controllers\Datatable\Akseslh\KelompokMasyarakatController::class, 'getAll'])->name('data-kelompok-masyarakat');
                Route::get('/data-paket-kegiatan', [App\Http\Controllers\Datatable\Akseslh\PaketKegiatanController::class, 'getAll'])->name('data-paket-kegiatan');
                Route::get('/data-pic-kelompok-masyarakat', [App\Http\Controllers\Datatable\Akseslh\DataPicKelompokMasyarakatController::class, 'getAll'])->name('data-pic-kelompok-masyarakat');
                Route::get('/data-tematik-kegiatan', [App\Http\Controllers\Datatable\Akseslh\TematikKegiatanController::class, 'getAll'])->name('data-tematik-kegiatan');
                Route::get('/data-sub-tematik-kegiatan', [App\Http\Controllers\Datatable\Akseslh\SubTematikKegiatanController::class, 'getAll'])->name('data-sub-tematik-kegiatan');
                Route::get('/data-satuan', [App\Http\Controllers\Datatable\Akseslh\SatuanController::class, 'getAll'])->name('data-satuan');
                Route::get('/data-jenis-komponen-rab', [App\Http\Controllers\Datatable\Akseslh\JenisKomponenRabController::class, 'getAll'])->name('data-jenis-komponen-rab');
                Route::get('/data-master-komponen-rab', [App\Http\Controllers\Datatable\Akseslh\MasterKomponenRabController::class, 'getAll'])->name('data-master-komponen-rab');
        });
});
