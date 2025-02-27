<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRabPengajuanKegiatanssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('rab_pengajuan_paket_kegiatans', function (Blueprint $table) {
            //
            $table->integer('harga_unit_realisasi')->nullable()->after('qty');
            $table->integer('qty_realisasi')->nullable()->after('harga_unit_realisasi');
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
