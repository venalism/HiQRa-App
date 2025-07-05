<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta')->onDelete('cascade');
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Panitia yang scan
            $table->timestamp('waktu_hadir');
            $table->enum('metode', ['barcode', 'manual'])->default('barcode');
            $table->enum('status', ['hadir', 'tidak_hadir', 'izin'])->default('hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['peserta_id', 'kegiatan_id']); // Peserta hanya bisa absen sekali per kegiatan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
