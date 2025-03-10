<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeIdColumnToTahapanPengajuanKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tahapan_pengajuan_kegiatans', function (Blueprint $table) {
            //
            $table->tinyInteger('code_id')->after('sort')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tahapan_pengajuan_kegiatans', function (Blueprint $table) {
            //
        });
    }
}
