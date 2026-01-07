<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAlamatKontakDaruratToProfilePicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_pics', function (Blueprint $table) {
            $table->text('alamat_kontak_darurat')->nullable()->after('nomor_kontak_darurat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_pics', function (Blueprint $table) {
            //
        });
    }
}
