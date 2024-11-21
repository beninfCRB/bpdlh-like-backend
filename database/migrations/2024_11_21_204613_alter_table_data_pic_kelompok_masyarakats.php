<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDataPicKelompokMasyarakats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('data_pic_kelompok_masyarakats', function (Blueprint $table) {
            //
            $table->string('tempat_lahir')->nullable()->after('provinsi_pic');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->foreignUuid('agama_id')->nullable()->after('tanggal_lahir');
            $table->foreignUuid('status_perkawinan_id')->nullable()->after('agama_id');
            $table->string('nama_gadis_ibu_kandung')->nullable()->after('status_perkawinan_id');
            $table->foreignUuid('jenis_pekerjaan_id')->nullable()->after('nama_gadis_ibu_kandung');
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
