<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_kegiatans', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary();
            $table->foreignUuid('paket_kegitan_id');
            $table->foreignUuid('user_eksternal_id');
            $table->string('judul_pengajuan_kegiatan', 500);
            $table->integer('provinsi_kegiatan');
            $table->integer('kabupaten_kegiatan');
            $table->integer('kecamatan_kegiatan');
            $table->integer('kelurahan_kegiatan');
            $table->text('alamat_kegiatan')->nullable();
            $table->date('tanggal_kegiatan');
            $table->string('username', 100)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuan_kegiatans');
    }
}
