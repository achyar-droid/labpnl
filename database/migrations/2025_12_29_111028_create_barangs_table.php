<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->enum('kategori', [
                'Perangkat Utama',
                'Perangkat Jaringan',
                'Perangkat IoT & Embedded',
                'Perangkat Elektronik',
                'Media & Penyimpanan',
                'Kabel & Konektor',
                'Aksesoris & Peripheral',
                'Peralatan Pendukung',
                'Lisensi & Software',
                'Lain-lain'
            ]);
            $table->integer('jumlah');
            $table->enum('lab', ['iot','jaringan','cloud']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('barangs');
    }
};
