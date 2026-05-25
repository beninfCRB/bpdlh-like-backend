<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterJenisKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jenis_kegiatans', function (Blueprint $table) {
            $table->string('durasi_hari_kegiatan')->nullable()->after('jenis_kegiatan');
            $table->string('durasi_hari_sptjm')->nullable()->after('durasi_hari_kegiatan');
            $table->string('durasi_hari_laporan_kegiatan')->nullable()->after('durasi_hari_sptjm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
