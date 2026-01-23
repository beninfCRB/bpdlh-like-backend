<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJenisKelompokColumnToProfilePicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_pics', function (Blueprint $table) {
            //
            $table->string('jenis_kelompok_masyarakat')->nullable()->after('data_pic_kelompok_masyarakat_id');
            $table->foreignUuid('jenis_kelompok_masyarakat_id')->nullable()->after('jenis_kelompok_masyarakat');
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
            $table->dropColumn('jenis_kelompok_masyarakat');
            $table->dropForeign(['jenis_kelompok_masyarakat_id']);
            $table->dropColumn('jenis_kelompok_masyarakat_id');
        });
    }
}
