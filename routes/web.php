<?php

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

Route::view('/blank', 'pages.blank.index')->name('blank');

Route::view('/home', 'pages.home.index')->name('home');

Route::prefix('akseslh')->group(function () {
    Route::resource('jenis-kegiatan', App\Http\Controllers\Cms\Akseslh\JenisKegiatanController::class);

    // Datatable
    Route::get('/data-jenis-kegiatan', [App\Http\Controllers\Datatable\Akseslh\JenisKegiatanController::class, 'getAll']);
});
