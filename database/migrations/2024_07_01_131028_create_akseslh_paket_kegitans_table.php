<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAkseslhPaketKegitansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akseslh_paket_kegitans', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary();
            $table->foreignUuid('akseslh_jenis_kegiatan_id');
            $table->string('nama_paket_kegiatan', 150);
            $table->string('deskripsi_paket_kegiatan', 500);
            $table->integer('quota_paket_kegiatan');
            $table->double('pagu_paket_kegiatan', 20);
            $table->boolean('tahap_pencairan_paket_kegiatan')->default(1);
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
        Schema::dropIfExists('akseslh_paket_kegitans');
    }
}
