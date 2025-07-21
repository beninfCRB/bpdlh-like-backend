<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTypeInPengajuanKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengajuan_kegiatans', function (Blueprint $table) {
            //
            $table->text('capaian_output')->nullable()->change();
            $table->text('capaian_outcome')->nullable()->change();
            $table->text('kendala_kegiatan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengajuan_kegiatans', function (Blueprint $table) {
            //
        });
    }
}
