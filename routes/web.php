<?php

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Exports\PivotEmailBlastTemplateExport;
use App\Exports\TolakPengajuanDanProfilTemplate;

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

Route::get('/', function () {
    return redirect()->route('home');
});

Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [App\Http\Controllers\Auth\AuthController::class, 'authenticate'])->name('login.auth')->middleware('recaptcha');
Route::post('/logout', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::view('/blank', 'pages.blank.index')->name('blank');

Route::get('provinsi/{id}', [App\Http\Controllers\Api\Akseslh\ProvinsiController::class, 'show']);
Route::get('kota', [App\Http\Controllers\Api\Akseslh\KotaController::class, 'index']);
Route::get('kota/{id}', [App\Http\Controllers\Api\Akseslh\KotaController::class, 'show']);
Route::get('kecamatan', [App\Http\Controllers\Api\Akseslh\KecamatanController::class, 'index']);
Route::get('kecamatan/{id}', [App\Http\Controllers\Api\Akseslh\KecamatanController::class, 'show']);
Route::get('kelurahan', [App\Http\Controllers\Api\Akseslh\KelurahanController::class, 'index']);
Route::get('kelurahan/{id}', [App\Http\Controllers\Api\Akseslh\KelurahanController::class, 'show']);

Route::delete('/dokumen-delete/{id}', [App\Http\Controllers\Cms\Akseslh\JenisDokumenController::class, 'delete_dokumen']);
// Route::get('/export', [App\Http\Controllers\Cms\Akseslh\PengajuanKegiatanController::class, 'export']);
Route::post('download-zip', [App\Http\Controllers\Cms\DashboardController::class, 'download_zip'])->name('download-zip');

Route::middleware(['auth'])->group(function () {

    Route::view('/home', 'pages.home.index')->name('home');

    Route::get('dashboard', [App\Http\Controllers\Cms\DashboardController::class, 'index']);

    Route::prefix('akseslh')->group(function () {

        Route::middleware(['ensureroleweb:approver,administrator'])->group(function () {
            Route::resource('jenis-kelompok-masyarakat', App\Http\Controllers\Cms\Akseslh\JenisKelompokMasyarakat::class);
            Route::resource('kelompok-masyarakat', App\Http\Controllers\Cms\Akseslh\KelompokMasyarakatController::class);
            Route::resource('jenis-kegiatan', App\Http\Controllers\Cms\Akseslh\JenisKegiatanController::class);
            Route::resource('tematik-kegiatan', App\Http\Controllers\Cms\Akseslh\TematikKegiatanController::class);
            Route::resource('sub-tematik-kegiatan', App\Http\Controllers\Cms\Akseslh\SubTematikKegiatanController::class);
            Route::resource('master-sub-tematik-kegiatan', App\Http\Controllers\Cms\Akseslh\MasterSubTematikKegiatanController::class);
            Route::resource('paket-kegiatan', App\Http\Controllers\Cms\Akseslh\PaketKegiatanController::class);
            Route::resource('jenis-pekerjaan', App\Http\Controllers\Cms\Akseslh\JenisPekerjaanController::class);
            Route::resource('pendidikan', App\Http\Controllers\Cms\Akseslh\PendidikanController::class);
            Route::resource('status-pernikahan', App\Http\Controllers\Cms\Akseslh\StatusPernikahanController::class);
            Route::resource('agama', App\Http\Controllers\Cms\Akseslh\AgamaController::class);
            Route::resource('tahapan-pengajuan-kegiatan', App\Http\Controllers\Cms\Akseslh\TahapanPengajuanKegiatanController::class);
            Route::resource('satuan', App\Http\Controllers\Cms\Akseslh\SatuanController::class);
            Route::resource('jenis-komponen-rab', App\Http\Controllers\Cms\Akseslh\JenisKomponenRabController::class);
            Route::resource('master-komponen-rab', App\Http\Controllers\Cms\Akseslh\MasterKomponenRabController::class);
            Route::resource('master-data-bank', App\Http\Controllers\Cms\Akseslh\MasterDataBankController::class);
            Route::resource('log-jadwal-pembukaan', App\Http\Controllers\Cms\Akseslh\LogJadwalPembukaanController::class);
            Route::resource('log-masa-sanggah', App\Http\Controllers\Cms\Akseslh\LogMasaSanggahController::class);
            Route::resource('jenis-dokumen', App\Http\Controllers\Cms\Akseslh\JenisDokumenController::class);
            Route::resource('master-data-indikator-laporan', App\Http\Controllers\Cms\Akseslh\MasterDataIndikatorLaporanController::class);
            Route::resource('master-indikator', App\Http\Controllers\Cms\Akseslh\MasterIndikatorController::class);

            // Restore
            Route::post('/tematik-kegiatan/{id}/restore', [App\Http\Controllers\Cms\Akseslh\TematikKegiatanController::class, 'restore']);
            Route::post('/sub-tematik-kegiatan/{id}/restore', [App\Http\Controllers\Cms\Akseslh\SubTematikKegiatanController::class, 'restore']);
            Route::post('/jenis-kelompok-masyarakat/{id}/restore', [App\Http\Controllers\Cms\Akseslh\JenisKelompokMasyarakat::class, 'restore']);
            Route::post('/jenis-dokumen/{id}/restore', [App\Http\Controllers\Cms\Akseslh\JenisDokumenController::class, 'restore']);
            Route::post('/jenis-kegiatan/{id}/restore', [App\Http\Controllers\Cms\Akseslh\JenisKegiatanController::class, 'restore']);
        });

        Route::middleware(['ensureroleweb:administrator'])->group(function () {
            Route::resource('banner-informasi', App\Http\Controllers\Cms\Akseslh\BannerInformasiController::class);
            Route::resource('email-blast', App\Http\Controllers\Cms\Akseslh\EmailBlastController::class);
        });

        Route::middleware(['ensureroleweb:administrator,pmu-bpdlh'])->group(function () {
            Route::resource('transaksi-penyaluran', App\Http\Controllers\Cms\Akseslh\TransaksiPenyaluranController::class);
            Route::get('/transaksi-penyaluran-export-template', [App\Http\Controllers\Cms\Akseslh\TransaksiPenyaluranController::class, 'template'])->name('transaksi-penyaluran.export-template');
            Route::post('/transaksi-penyaluran-import', [App\Http\Controllers\Cms\Akseslh\TransaksiPenyaluranController::class, 'import'])->name('transaksi-penyaluran.import');

            Route::get('/transaksi-penyaluran-import', [App\Http\Controllers\Cms\Akseslh\TransaksiPenyaluranController::class, 'import_view'])->name('transaksi-penyaluran.import-view');
            Route::get('/transaksi-penyaluran-import/{id}', [App\Http\Controllers\Cms\Akseslh\TransaksiPenyaluranController::class, 'import_edit'])->name('transaksi-penyaluran.import-edit');
            Route::put('/transaksi-penyaluran-import/{id}', [App\Http\Controllers\Cms\Akseslh\TransaksiPenyaluranController::class, 'import_update'])->name('transaksi-penyaluran.import-update');
        });

        Route::middleware(['ensureroleweb:administrator,verifikator'])->group(function () {
            Route::resource('verifikasi-pengajuan-kegiatan', App\Http\Controllers\Cms\Akseslh\VerifikasiPengajuanKegiatanController::class);
            Route::put('verifikasi-pengajuan-kegiatan/verifikasi-pengajuan/{id}', [App\Http\Controllers\Cms\Akseslh\VerifikasiPengajuanKegiatanController::class, 'verifikasiPengajuan']);
        });

        // Import & Download
        Route::post('pic-kelompok-masyarakat/import', [App\Http\Controllers\Cms\Akseslh\DataPicKelompokMasyarakatController::class, 'import'])
            ->name('pic-kelompok-masyarakat.import');

        Route::post('/pengajuan-kegiatan/export-excel-pengajuan', [App\Http\Controllers\Cms\Akseslh\PengajuanKegiatanController::class, 'export'])->name('export-excel-pengajuan');
        Route::get('/export-proposal/{id}', [App\Http\Controllers\Cms\Akseslh\PengajuanKegiatanController::class, 'export_proposal'])->name('export-proposal');
        Route::get('/export-rab/{id}', [App\Http\Controllers\Cms\Akseslh\PengajuanKegiatanController::class, 'export_rab'])->name('export-rab');
        Route::post('export-excel-transaksi-penyaluran', [App\Http\Controllers\Cms\Akseslh\TransaksiPenyaluranController::class, 'export'])->name('export-excel-transaksi-penyaluran');
        Route::get('/export-pic', [App\Http\Controllers\Cms\Akseslh\DataPicKelompokMasyarakatController::class, 'export'])->name('pic-kelompok-masyarakat.export');
        Route::post('/import-pivot-email-blast', [App\Http\Controllers\Cms\Akseslh\EmailBlastController::class, 'importPivotData'])->name('pivot.import.upload');
        Route::get('/template-pivot-email-blast', function () {
            return Excel::download(new PivotEmailBlastTemplateExport, 'template_pivot_email_blast.xlsx');
        })->name('pivot.template.download');

        Route::get('pengajuan-kegiatan/{id}/dokumen', [App\Http\Controllers\Cms\Akseslh\PengajuanKegiatanController::class, 'dokumen'])->name('pengajuan-kegiatan.document');
        Route::put('pengajuan-kegiatan/{id}/dokumen', [App\Http\Controllers\Cms\Akseslh\PengajuanKegiatanController::class, 'update_dokumen'])->name('pengajuan-kegiatan.document.update');
        Route::post('pengajuan-kegiatan/{id}/update-sptjm', [App\Http\Controllers\Cms\Akseslh\PengajuanKegiatanController::class, 'update_sptjm'])->name('pengajuan-kegiatan.update-sptjm');

        Route::resource('pic-kelompok-masyarakat', App\Http\Controllers\Cms\Akseslh\DataPicKelompokMasyarakatController::class);
        Route::resource('user-akseslh', App\Http\Controllers\Cms\Akseslh\UserAkseslhController::class);
        Route::resource('pengajuan-kegiatan', App\Http\Controllers\Cms\Akseslh\PengajuanKegiatanController::class);

        Route::resource('profile-pic', App\Http\Controllers\Cms\Akseslh\ProfilePicController::class);
        Route::put('profile-pic/tolak-profil/{id}', [App\Http\Controllers\Cms\Akseslh\ProfilePicController::class, 'tolak_profil']);
        Route::put('profile-pic/pengajuan-perubahan-profil/{id}', [App\Http\Controllers\Cms\Akseslh\ProfilePicController::class, 'pengajuan_perubahan_profil']);

        // User Jenis Kelompok
        Route::resource('master-user-jenis-kelompok', App\Http\Controllers\Cms\Akseslh\MasterUserJenisKelompokController::class);

        Route::resource('testimonial', App\Http\Controllers\Cms\Akseslh\TestimonialController::class);

        Route::get('tolak-pengajuan-dan-profil', [App\Http\Controllers\Cms\Akseslh\TolakPengajuanDanProfilController::class, 'index'])->name('tolak-pengajuan-dan-profil.index');
        Route::post('tolak-pengajuan-dan-profil', [App\Http\Controllers\Cms\Akseslh\TolakPengajuanDanProfilController::class, 'store'])->name('tolak-pengajuan-dan-profil.store');
        Route::post('tolak-pengajuan-dan-profil/proses', [App\Http\Controllers\Cms\Akseslh\TolakPengajuanDanProfilController::class, 'proses'])->name('tolak-pengajuan-dan-profil.proses');
        Route::get('tolak-pengajuan-dan-profil/template', function () {
            return Excel::download(new TolakPengajuanDanProfilTemplate, 'tolak_pengajuan_dan_profil_template.xlsx');
        })->name('tolak-pengajuan-dan-profil.template');

        Route::get('standar-rab-paket-kegiatan/{id}', [App\Http\Controllers\Cms\Akseslh\StandarRabPaketKegiatanController::class, 'edit']);
        Route::post('standar-rab-paket-kegiatan', [App\Http\Controllers\Cms\Akseslh\StandarRabPaketKegiatanController::class, 'store'])->name('standar-rab-paket-kegiatan.store');

        Route::get('laporan-akhir-kegiatan', [App\Http\Controllers\Cms\Akseslh\LaporanAkhirKegiatanController::class, 'index'])->name('laporan-akhir-kegiatan.index');
        Route::post('laporan-akhir-kegiatan', [App\Http\Controllers\Cms\Akseslh\LaporanAkhirKegiatanController::class, 'store'])->name('laporan-akhir-kegiatan.store');
        Route::get('laporan-akhir-kegiatan/edit', [App\Http\Controllers\Cms\Akseslh\LaporanAkhirKegiatanController::class, 'edit'])->name('laporan-akhir-kegiatan.edit');

        // Datatable
        Route::get('/data-profile-pic', [App\Http\Controllers\Datatable\Akseslh\ProfilePicController::class, 'getAll'])->name('data-profile-pic');
        Route::get('/data-jenis-kegiatan', [App\Http\Controllers\Datatable\Akseslh\JenisKegiatanController::class, 'getAll'])->name('data-jenis-kegiatan');
        Route::get('/data-jenis-dokumen', [App\Http\Controllers\Datatable\Akseslh\JenisDokumenController::class, 'getAll'])->name('data-jenis-dokumen');
        Route::get('/data-tahapan-pengajuan-kegiatan', [App\Http\Controllers\Datatable\Akseslh\TahapanPengajuanKegiatanController::class, 'getAll'])->name('data-tahapan-pengajuan-kegiatan');
        Route::get('/data-jenis-kelompok-masyarakat', [App\Http\Controllers\Datatable\Akseslh\JenisKelompokMasyarakatController::class, 'getAll'])->name('data-jenis-kelompok-masyarakat');
        Route::get('/data-kelompok-masyarakat', [App\Http\Controllers\Datatable\Akseslh\KelompokMasyarakatController::class, 'getAll'])->name('data-kelompok-masyarakat');
        Route::get('/data-email-blast', [App\Http\Controllers\Datatable\Akseslh\EmailBlastController::class, 'getAll'])->name('data-email-blast');
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
        Route::get('/data-master-user-jenis-kelompok', [App\Http\Controllers\Datatable\Akseslh\MasterUserJenisKelompokController::class, 'getAll'])->name('data-master-user-jenis-kelompok');
        Route::get('/data-master-user-jenis-kelompok/{id}', [App\Http\Controllers\Datatable\Akseslh\MasterUserJenisKelompokController::class, 'getAllUser'])->name('data-master-user-jenis-kelompok.show');
        Route::get('/data-jenis-pekerjaan', [App\Http\Controllers\Datatable\Akseslh\JenisPekerjaanController::class, 'getAll'])->name('data-jenis-pekerjaan');
        Route::get('/data-pendidikan', [App\Http\Controllers\Datatable\Akseslh\PendidikanController::class, 'getAll'])->name('data-pendidikan');
        Route::get('/data-banner-informasi', [App\Http\Controllers\Datatable\Akseslh\BannerInformasiController::class, 'getAll'])->name('data-banner-informasi');
        Route::get('/data-status-pernikahan', [App\Http\Controllers\Datatable\Akseslh\StatusPernikahanController::class, 'getAll'])->name('data-status-pernikahan');
        Route::get('/data-agama', [App\Http\Controllers\Datatable\Akseslh\AgamaController::class, 'getAll'])->name('data-agama');
        Route::get('/data-log-masa-sanggah', [App\Http\Controllers\Datatable\Akseslh\LogMasaSanggahController::class, 'getAll'])->name('data-log-masa-sanggah');
        Route::get('/data-master-indikator', [App\Http\Controllers\Datatable\Akseslh\MasterIndikatorController::class, 'getAll'])->name('data-master-indikator');
        Route::get('/data-laporan-akhir-kegiatan', [App\Http\Controllers\Datatable\Akseslh\LaporanAkhirKegiatanController::class, 'getAll'])->name('data-laporan-akhir-kegiatan');
        Route::get('/data-tolak-pengajuan-dan-profil', [App\Http\Controllers\Datatable\Akseslh\TolakPengajuanDanProfilController::class, 'getAll'])->name('data-tolak-pengajuan-dan-profil');
    });
});
