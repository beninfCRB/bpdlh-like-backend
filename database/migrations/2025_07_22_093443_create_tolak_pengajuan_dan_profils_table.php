<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTolakPengajuanDanProfilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tolak_pengajuan_dan_profils', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nomor_pengajuan')->nullable();
            $table->string('email_pic');
            $table->enum('status_penolakan', ['pengajuan', 'profil'])->default('pengajuan');
            $table->text('catatan_penlokan')->nullable();
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
        Schema::dropIfExists('tolak_pengajuan_dan_profils');
    }
}
