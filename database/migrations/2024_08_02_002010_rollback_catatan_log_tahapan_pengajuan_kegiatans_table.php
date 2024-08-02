<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RollbackCatatanLogTahapanPengajuanKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catatan_log_tahapan_pengajuan_kegiatans', function (Blueprint $table) {
            // Menghapus kolom pengajuan_kegiatan_id
            if (Schema::hasColumn('catatan_log_tahapan_pengajuan_kegiatans', 'pengajuan_kegiatan_id')) {
                $table->dropColumn('pengajuan_kegiatan_id');
            }

            // Menambahkan kolom log_tahapan_pengajuan_kegiatan_id
            $table->foreignUuid('log_tahapan_pengajuan_kegiatan_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catatan_log_tahapan_pengajuan_kegiatans', function (Blueprint $table) {
            // Menambahkan kembali kolom pengajuan_kegiatan_id jika perlu
            $table->foreignUuid('pengajuan_kegiatan_id')->after('id');

            // Menghapus kolom log_tahapan_pengajuan_kegiatan_id
            $table->dropColumn('log_tahapan_pengajuan_kegiatan_id');
        });
    }
}
