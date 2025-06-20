<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterIndikatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_indikators', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary();
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
        Schema::dropIfExists('master_indikators');
    }
}
