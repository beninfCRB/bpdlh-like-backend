<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksiPenyaluransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_penyalurans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('master_data_bank_id')->constrained('master_data_banks');
            $table->foreignUuid('pengajuan_kegiatan_id')->constrained('pengajuan_kegiatans');
            $table->string('nomor_rekening');
            $table->string('nama_pemilik_rekening');
            $table->bigInteger('nilai_penyaluran');
            $table->date('tanggal_penyaluran');
            $table->tinyInteger('flag')->default(1);
            $table->string('username')->nullable();
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
        Schema::dropIfExists('transaksi_penyalurans');
    }
}
