<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterDataIndikatorLaporansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_data_indikator_laporans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('jenis_kegiatan_id');
            $table->foreignUuid('sub_tematik_kegiatan_id');
            $table->string('nama_indikator', 100);
            $table->string('satuan', 10);
            $table->string('tipe_data', 10);
            $table->tinyInteger('flag')->default(1);
            $table->string('username', 100)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('master_data_indikator_laporans');
    }
}
