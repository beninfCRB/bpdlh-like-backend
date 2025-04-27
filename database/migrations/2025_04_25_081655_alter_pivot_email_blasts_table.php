<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPivotEmailBlastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pivot_email_blasts', function (Blueprint $table) {
            // Hapus kolom pengajuan_kegiatan_id
            $table->dropColumn('pengajuan_kegiatan_id');

            // Tambahkan kolom nomor_pengajuan
            $table->string('nomor_pengajuan')->after('email')->index();
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
