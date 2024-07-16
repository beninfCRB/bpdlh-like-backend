<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataPicKelompokMasyarakatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_pic_kelompok_masyarakats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_pic', 255);
            $table->enum('jenis_identitas_pic', ['KTP', 'SIM', 'KARTU MAHASISWA']);
            $table->string('nomor_identitas_pic', 50);
            $table->string('email_pic', 50);
            $table->string('nohp_pic', 50);
            $table->string('alamat_pic', 50);
            $table->string('kelurahan_pic', 50);
            $table->string('kecamatan_pic', 50);
            $table->string('provinsi_pic', 50);
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
        Schema::dropIfExists('data_pic_kelompok_masyarakats');
    }
}
