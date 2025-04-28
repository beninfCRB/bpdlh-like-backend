<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePivotEmailBlastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('pivot_email_blasts', function (Blueprint $table) {
            // Ubah kolom catatan_log jadi text
            $table->text('catatan_log')->nullable()->change();

            // Ubah enum status, ini perlu di-drop dulu baru dibuat lagi
            $table->dropColumn('status');
        });

        Schema::table('pivot_email_blasts', function (Blueprint $table) {
            $table->enum('status', ['diterima', 'ditolak', 'tolak_profil'])->after('nomor_pengajuan');
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
        Schema::table('pivot_email_blasts', function (Blueprint $table) {
            // Balikin ke kondisi semula (optional aja)
            $table->dropColumn('status');
            $table->enum('status', ['diterima', 'ditolak'])->after('nomor_pengajuan');

            $table->string('catatan_log')->nullable()->change();
        });
    }
}
