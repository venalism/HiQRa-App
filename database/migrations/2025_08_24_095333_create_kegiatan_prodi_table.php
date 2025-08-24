<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kegiatan_prodi', function (Blueprint $table) {
            $table->id();
            // Kolom ini akan menyimpan ID dari tabel 'kegiatan'
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->onDelete('cascade');
            // Kolom ini akan menyimpan ID dari tabel 'prodis'
            $table->foreignId('prodi_id')->constrained('prodis')->onDelete('cascade');
            // Tambahkan constraint unik untuk mencegah duplikasi
            $table->unique(['kegiatan_id', 'prodi_id']);
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kegiatan_prodi');
    }
};