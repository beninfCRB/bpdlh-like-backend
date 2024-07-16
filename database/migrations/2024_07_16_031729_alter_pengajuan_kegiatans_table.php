<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPengajuanKegiatansTable extends Migration
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
            $table->dropColumn('tanggal_kegiatan');
            $table->date('tanggal_mulai_kegiatan')->after('alamat_kegiatan');
            $table->date('tanggal_akhir_kegiatan')->after('tanggal_mulai_kegiatan');
            $table->time('time_mulai_kegiatan')->after('tanggal_akhir_kegiatan');
            $table->time('time_akhir_kegiatan')->after('time_mulai_kegiatan');
            $table->string('proposal_kegiatan', 255)->after('time_akhir_kegiatan');
            $table->string('tujuan_kegiatan', 255)->after('proposal_kegiatan');
            $table->string('ruang_lingkup_kegiatan', 255)->after('tujuan_kegiatan');
            $table->tinyInteger('flag')->after('ruang_lingkup_kegiatan');
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
