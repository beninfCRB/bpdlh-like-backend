<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTahapSalurPaketKegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('tahap_salur_paket_kegiatans', function (Blueprint $table) {
            //
            $table->integer('porsi_pencairan')->after('tahap_salur');
            $table->tinyInteger('flag')->after('porsi_pencairan');
            $table->string('username', 100)->nullable()->after('flag');
            $table->softDeletes();
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
