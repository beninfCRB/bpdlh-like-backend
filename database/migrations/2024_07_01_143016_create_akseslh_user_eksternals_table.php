<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAkseslhUserEksternalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akseslh_user_eksternals', function (Blueprint $table) {
            // $table->id();
            $table->uuid('id')->primary();
            $table->foreignUuid('akseslh_kelompok_masyarakat_id');
            $table->string('email_user_eksternal', 100)->unique();
            $table->string('password_user_eksternal', 100);
            $table->string('nama_user_eksternal', 255);
            $table->enum('jenis_identitas_user_eksternal', ['KTP', 'SIM', 'Kartu Mahasiswa'])->default('KTP');
            $table->string('nomor_identitas_user_eksternal', 20);
            $table->string('nomor_hp_user_eksternal', 20);
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
        Schema::dropIfExists('akseslh_user_eksternals');
    }
}
