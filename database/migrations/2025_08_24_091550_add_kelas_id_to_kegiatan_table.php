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
        Schema::table('kegiatan', function (Blueprint $table) {
            // Tambahkan kolom 'kelas_id' yang bisa null
            $table->unsignedBigInteger('kelas_id')->nullable()->after('lokasi');

            // Tambahkan foreign key constraint
            // Ini menghubungkan kolom 'kelas_id' di tabel 'kegiatan'
            // dengan kolom 'id' di tabel 'kelas'
            $table->foreign('kelas_id')
                  ->references('id')
                  ->on('kelas')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            // Hapus foreign key terlebih dahulu
            $table->dropForeign(['kelas_id']);
            
            // Hapus kolom 'kelas_id'
            $table->dropColumn('kelas_id');
        });
    }
};
