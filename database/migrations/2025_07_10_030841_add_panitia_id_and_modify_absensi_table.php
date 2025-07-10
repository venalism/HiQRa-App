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
        Schema::table('absensi', function (Blueprint $table) {
            // STEP 1: Hapus foreign key constraint pada `peserta_id`. Ini "membuka gembok".
            $table->dropForeign(['peserta_id']);

            // STEP 2: Sekarang kita bisa hapus unique index-nya. Ini "mengambil kunci".
            $table->dropUnique(['peserta_id', 'kegiatan_id']);

            // STEP 3: Lakukan perubahan yang diinginkan
            $table->foreignId('peserta_id')->nullable()->change();
            $table->foreignId('panitia_id')
                ->nullable()
                ->after('peserta_id')
                ->constrained('panitia')
                ->onDelete('cascade');

            // STEP 4: Buat kembali foreign key untuk `peserta_id`
            $table->foreign('peserta_id')
                ->references('id')
                ->on('peserta')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            // Lakukan proses kebalikan dari method up()

            $table->dropForeign(['panitia_id']);
            $table->dropForeign(['peserta_id']);

            $table->dropColumn('panitia_id');

            $table->foreignId('peserta_id')->nullable(false)->change();

            $table->unique(['peserta_id', 'kegiatan_id']);
            $table->foreign('peserta_id')
                ->references('id')
                ->on('peserta')
                ->onDelete('cascade');
        });
    }
};