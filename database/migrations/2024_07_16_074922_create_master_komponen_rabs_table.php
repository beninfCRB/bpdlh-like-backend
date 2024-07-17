<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterKomponenRabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_komponen_rabs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('jenis_komponen_rab_id');
            $table->foreignUuid('satuan_id');
            $table->string('komponen_rab', 100);
            $table->integer('standar_harga_unit');
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
        Schema::dropIfExists('master_komponen_rabs');
    }
}
