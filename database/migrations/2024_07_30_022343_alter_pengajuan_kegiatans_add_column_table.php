<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPengajuanKegiatansAddColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('pengajuan_kegiatans', function (Blueprint $table) {
            //
            $table->foreignUuid('lokasi_bidang_folu_id')->after('paket_kegiatan_id');
            $table->enum('is_active', ['ACTIVE', 'INACTIVE'])->default('ACTIVE')->after('ruang_lingkup_kegiatan');
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
