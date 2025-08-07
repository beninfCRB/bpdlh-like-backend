<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilePicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_pics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('data_pic_kelompok_masyarakat_id');
            $table->foreignUuid('kelompok_masyarakat_id');
            $table->string('nama_pic', 255);
            $table->enum('jenis_identitas_pic', ['KTP', 'SIM', 'KARTU MAHASISWA']);
            $table->string('nomor_identitas_pic', 50);
            $table->string('nomor_npwp_pic')->nullable();
            $table->string('email_pic', 50)->nullable();
            $table->string('nohp_pic', 50);
            $table->string('alamat_pic', 255);
            $table->string('kelurahan_pic', 50);
            $table->string('kecamatan_pic', 50);
            $table->string('kabupaten_pic', 50);
            $table->string('provinsi_pic', 50);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->foreignUuid('agama_id')->nullable();
            $table->foreignUuid('status_perkawinan_id')->nullable();
            $table->string('nama_gadis_ibu_kandung')->nullable();
            $table->foreignUuid('jenis_pekerjaan_id')->nullable();
            $table->foreignUuid('pendidikan_id')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable();
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
        Schema::dropIfExists('profile_pics');
    }
}
