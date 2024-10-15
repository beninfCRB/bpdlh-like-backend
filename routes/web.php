<?php

use App\Exports\DataPicKelompokMasyarakatExport;
use App\Models\UserAkseslh;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
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
Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'authenticate'])->name('login.auth')->middleware('recaptcha');

Route::get('/', function () {
    return redirect()->route('home');
});

Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::view('/blank', 'pages.blank.index')->name('blank');

Route::get('provinsi/{id}', [App\Http\Controllers\Api\Akseslh\ProvinsiController::class, 'show']);
Route::get('kota', [App\Http\Controllers\Api\Akseslh\KotaController::class, 'index']);
Route::get('kota/{id}', [App\Http\Controllers\Api\Akseslh\KotaController::class, 'show']);
Route::get('kecamatan', [App\Http\Controllers\Api\Akseslh\KecamatanController::class, 'index']);
Route::get('kecamatan/{id}', [App\Http\Controllers\Api\Akseslh\KecamatanController::class, 'show']);
Route::get('kelurahan', [App\Http\Controllers\Api\Akseslh\KelurahanController::class, 'index']);
Route::get('kelurahan/{id}', [App\Http\Controllers\Api\Akseslh\KelurahanController::class, 'show']);

Route::view('/pdf', 'pdf.template-small-grant');

Route::delete('/dokumen-delete/{id}', [App\Http\Controllers\Cms\Akseslh\JenisDokumenController::class, 'delete_dokumen']);

// Route::get('/export', [App\Http\Controllers\Cms\Akseslh\PengajuanKegiatanController::class, 'export']);

Route::middleware(['auth'])->group(function () {

    Route::view('/home', 'pages.home.index')->name('home');

    Route::prefix('akseslh')->group(function () {

        Route::post('pic-kelompok-masyarakat/import', [App\Http\Controllers\Cms\Akseslh\DataPicKelompokMasyarakatController::class, 'import'])
            ->name('pic-kelompok-masyarakat.import');

        Route::post('export-excel-pengajuan', [App\Http\Controllers\Cms\Akseslh\PengajuanKegiatanController::class, 'export'])->name('export-excel-pengajuan');
        Route::post('export-excel-transaksi-penyaluran', [App\Http\Controllers\Cms\Akseslh\TransaksiPenyaluranController::class, 'export'])->name('export-excel-transaksi-penyaluran');

        Route::get('/export-pic', [App\Http\Controllers\Cms\Akseslh\DataPicKelompokMasyarakatController::class, 'export'])->name('pic-kelompok-masyarakat.export');

        Route::resource('jenis-kegiatan', App\Http\Controllers\Cms\Akseslh\JenisKegiatanController::class);
        Route::resource('jenis-dokumen', App\Http\Controllers\Cms\Akseslh\JenisDokumenController::class);
        Route::resource('jenis-kelompok-masyarakat', App\Http\Controllers\Cms\Akseslh\JenisKelompokMasyarakat::class);
        Route::resource('kelompok-masyarakat', App\Http\Controllers\Cms\Akseslh\KelompokMasyarakatController::class);
        Route::resource('paket-kegiatan', App\Http\Controllers\Cms\Akseslh\PaketKegiatanController::class);
        Route::resource('pic-kelompok-masyarakat', App\Http\Controllers\Cms\Akseslh\DataPicKelompokMasyarakatController::class);
        Route::resource('user-akseslh', App\Http\Controllers\Cms\Akseslh\UserAkseslhController::class);
        Route::resource('tahapan-pengajuan-kegiatan', App\Http\Controllers\Cms\Akseslh\TahapanPengajuanKegiatanController::class);
        Route::resource('tematik-kegiatan', App\Http\Controllers\Cms\Akseslh\TematikKegiatanController::class);
        Route::resource('sub-tematik-kegiatan', App\Http\Controllers\Cms\Akseslh\SubTematikKegiatanController::class);
        Route::resource('master-sub-tematik-kegiatan', App\Http\Controllers\Cms\Akseslh\MasterSubTematikKegiatanController::class);
        Route::resource('satuan', App\Http\Controllers\Cms\Akseslh\SatuanController::class);
        Route::resource('jenis-komponen-rab', App\Http\Controllers\Cms\Akseslh\JenisKomponenRabController::class);
        Route::resource('master-komponen-rab', App\Http\Controllers\Cms\Akseslh\MasterKomponenRabController::class);
        Route::resource('pengajuan-kegiatan', App\Http\Controllers\Cms\Akseslh\PengajuanKegiatanController::class);
        Route::resource('master-data-bank', App\Http\Controllers\Cms\Akseslh\MasterDataBankController::class);
        Route::resource('log-jadwal-pembukaan', App\Http\Controllers\Cms\Akseslh\LogJadwalPembukaanController::class);
        Route::resource('transaksi-penyaluran', App\Http\Controllers\Cms\Akseslh\TransaksiPenyaluranController::class);
        Route::resource('master-data-indikator-laporan', App\Http\Controllers\Cms\Akseslh\MasterDataIndikatorLaporanController::class);

        Route::get('standar-rab-paket-kegiatan/{id}', [App\Http\Controllers\Cms\Akseslh\StandarRabPaketKegiatanController::class, 'edit']);
        Route::post('standar-rab-paket-kegiatan', [App\Http\Controllers\Cms\Akseslh\StandarRabPaketKegiatanController::class, 'store'])->name('standar-rab-paket-kegiatan.store');

        // Datatable
        Route::get('/data-jenis-kegiatan', [App\Http\Controllers\Datatable\Akseslh\JenisKegiatanController::class, 'getAll'])->name('data-jenis-kegiatan');
        Route::get('/data-jenis-dokumen', [App\Http\Controllers\Datatable\Akseslh\JenisDokumenController::class, 'getAll'])->name('data-jenis-dokumen');
        Route::get('/data-tahapan-pengajuan-kegiatan', [App\Http\Controllers\Datatable\Akseslh\TahapanPengajuanKegiatanController::class, 'getAll'])->name('data-tahapan-pengajuan-kegiatan');
        Route::get('/data-jenis-kelompok-masyarakat', [App\Http\Controllers\Datatable\Akseslh\JenisKelompokMasyarakatController::class, 'getAll'])->name('data-jenis-kelompok-masyarakat');
        Route::get('/data-kelompok-masyarakat', [App\Http\Controllers\Datatable\Akseslh\KelompokMasyarakatController::class, 'getAll'])->name('data-kelompok-masyarakat');
        Route::get('/data-paket-kegiatan', [App\Http\Controllers\Datatable\Akseslh\PaketKegiatanController::class, 'getAll'])->name('data-paket-kegiatan');
        Route::get('/data-pic-kelompok-masyarakat', [App\Http\Controllers\Datatable\Akseslh\DataPicKelompokMasyarakatController::class, 'getAll'])->name('data-pic-kelompok-masyarakat');
        Route::get('/data-user-akseslh', [App\Http\Controllers\Datatable\Akseslh\UserAkseslhController::class, 'getAll'])->name('data-user-akseslh');
        Route::get('/data-tematik-kegiatan', [App\Http\Controllers\Datatable\Akseslh\TematikKegiatanController::class, 'getAll'])->name('data-tematik-kegiatan');
        Route::get('/data-sub-tematik-kegiatan', [App\Http\Controllers\Datatable\Akseslh\SubTematikKegiatanController::class, 'getAll'])->name('data-sub-tematik-kegiatan');
        Route::get('/data-master-sub-tematik-kegiatan', [App\Http\Controllers\Datatable\Akseslh\MasterSubTematikKegiatanController::class, 'getAll'])->name('data-master-sub-tematik-kegiatan');
        Route::get('/data-satuan', [App\Http\Controllers\Datatable\Akseslh\SatuanController::class, 'getAll'])->name('data-satuan');
        Route::get('/data-jenis-komponen-rab', [App\Http\Controllers\Datatable\Akseslh\JenisKomponenRabController::class, 'getAll'])->name('data-jenis-komponen-rab');
        Route::get('/data-master-komponen-rab', [App\Http\Controllers\Datatable\Akseslh\MasterKomponenRabController::class, 'getAll'])->name('data-master-komponen-rab');
        Route::get('/data-pengajuan-kegiatan', [App\Http\Controllers\Datatable\Akseslh\PengajuanKegiatanController::class, 'getAll'])->name('data-pengajuan-kegiatan');
        Route::get('/data-master-data-bank', [App\Http\Controllers\Datatable\Akseslh\MasterDataBankController::class, 'getAll'])->name('data-master-data-bank');
        Route::get('/data-log-jadwal-pembukaan', [App\Http\Controllers\Datatable\Akseslh\LogJadwalPembukaanController::class, 'getAll'])->name('data-log-jadwal-pembukaan');
        Route::get('/data-transaksi-penyaluran', [App\Http\Controllers\Datatable\Akseslh\TransaksiPenyaluranController::class, 'getAll'])->name('data-transaksi-penyaluran');
        Route::get('/data-master-data-indikator-laporan', [App\Http\Controllers\Datatable\Akseslh\MasterDataIndikatorLaporanController::class, 'getAll'])->name('data-master-data-indikator-laporan');
    });
});
