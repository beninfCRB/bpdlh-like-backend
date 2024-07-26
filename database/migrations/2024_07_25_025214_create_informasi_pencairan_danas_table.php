<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformasiPencairanDanasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informasi_pencairan_danas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('master_data_bank_id');
            $table->foreignUuid('log_tahapan_pengajuan_kegiatan_id');
            $table->string('nama_cabang', 255);
            $table->enum('jenis_rekening', ['Perorangan', 'Institusi/Perusahaan'])->default('Perorangan');
            $table->string('nama_pemilik_rekening', 255);
            $table->string('nomor_rekening', 255);
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
        Schema::dropIfExists('informasi_pencairan_danas');
    }
}
