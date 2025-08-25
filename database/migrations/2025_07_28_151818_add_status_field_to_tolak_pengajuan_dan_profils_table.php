<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusFieldToTolakPengajuanDanProfilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tolak_pengajuan_dan_profils', function (Blueprint $table) {
            //
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('catatan_penlokan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tolak_pengajuan_dan_profils', function (Blueprint $table) {
            //
        });
    }
}
