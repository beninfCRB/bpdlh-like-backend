<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTahapSalurPaketKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tahap_salur_paket_kegiatans', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary();
            $table->foreignUuid('oaket_kegiatan_id');
            $table->integer('tahap_salur');
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
        Schema::dropIfExists('tahap_salur_paket_kegiatans');
    }
}
