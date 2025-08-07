<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProfilePicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('profile_pics', function (Blueprint $table) {
            //
            $table->text('catatan')->nullable()->after('jenis_kelamin');
            $table->enum('status_verifikasi', ['belum_verifikasi', 'verifikasi', 'tolak'])->default('belum_verifikasi')->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
