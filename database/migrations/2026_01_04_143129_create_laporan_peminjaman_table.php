<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('laporan_peminjaman', function (Blueprint $table) {
        $table->id();

        $table->foreignId('mahasiswa_id')->constrained()->cascadeOnDelete();
        $table->foreignId('barang_id')->constrained()->cascadeOnDelete();

        $table->string('lab'); // cloud / jaringan / iot
        $table->integer('jumlah');

        $table->date('tanggal_pinjam');
        $table->date('tanggal_kembali')->nullable();

        $table->enum('status', ['dipinjam','dikembalikan'])->default('dipinjam');

        $table->timestamps();
    });
}

};
