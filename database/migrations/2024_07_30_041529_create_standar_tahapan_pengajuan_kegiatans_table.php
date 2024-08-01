<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandarTahapanPengajuanKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standar_tahapan_pengajuan_kegiatans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('paket_kegiatan_id');
            $table->foreignUuid('tahapan_pengajuan_kegiatan_id');
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->tinyInteger('flag')->default(1);
            $table->string('username', 100)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('lokasi_bidang_folus', function (Blueprint $table) {
            //
            $table->uuid('id')->primary()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('standar_tahapan_pengajuan_kegiatans');
    }
}
