<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNomorSptjmColumnToPengajuanKegiatansTable extends Migration
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
            $table->string('nomor_sptjm', 100)->after('caping_rab')->nullable();
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
