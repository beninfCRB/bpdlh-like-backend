<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterUserJenisKelompoksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_user_jenis_kelompoks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_akseslh_id');
            $table->foreignUuid('jenis_kelompok_masyarakat_id');
            $table->tinyInteger('flag')->default(1);
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
        Schema::dropIfExists('master_user_jenis_kelompoks');
    }
}
