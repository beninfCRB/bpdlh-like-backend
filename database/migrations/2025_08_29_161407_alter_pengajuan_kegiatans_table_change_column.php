<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPengajuanKegiatansTableChangeColumn extends Migration
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
            $table->text('proposal_kegiatan')->change();
            $table->text('tujuan_kegiatan')->change();
            $table->text('ruang_lingkup_kegiatan')->change();
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
