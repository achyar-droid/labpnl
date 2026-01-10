<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();

            $table->string('nim')->unique();
            $table->string('nama');

            // dropdown pilihan
            $table->enum('prodi', ['TRKJ', 'TRM', 'TI']);
            $table->enum('kelas', ['A', 'B', 'C', 'D', 'E']);
            $table->year('angkatan');
            $table->enum('jenis_kelamin', ['L', 'P']);

            // lab pemisahan data
            $table->enum('lab', ['iot', 'jaringan', 'cloud']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
