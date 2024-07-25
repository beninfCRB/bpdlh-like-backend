<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatatanLogTahapanPengajuanKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catatan_log_tahapan_pengajuan_kegiatans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('log_tahapan_pengajuan_kegiatan_id');
            $table->string('catatan_log');
            $table->tinyInteger('flag')->default(1);
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
        Schema::dropIfExists('catatan_log_tahapan_pengajuan_kegiatans');
    }
}
