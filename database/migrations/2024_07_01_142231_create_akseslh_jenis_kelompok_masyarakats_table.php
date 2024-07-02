<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAkseslhJenisKelompokMasyarakatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akseslh_jenis_kelompok_masyarakats', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary();
            $table->string('jenis_kelompok_masyarakat', 150);
            $table->tinyInteger('short_id');
            $table->tinyInteger('flag');
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
        Schema::dropIfExists('akseslh_jenis_kelompok_masyarakats');
    }
}
