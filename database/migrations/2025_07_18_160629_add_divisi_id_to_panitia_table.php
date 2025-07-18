<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDivisiIdToPanitiaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('panitia', function (Blueprint $table) {
            // Tambahkan kolom divisi_id
            $table->unsignedBigInteger('divisi_id')->nullable()->after('id');

            // Jadikan foreign key ke tabel divisi
            $table->foreign('divisi_id')->references('id')->on('divisis')->onDelete('set null');

            // Tambahkan kolom prodi jika dibutuhkan
            // $table->string('prodi')->nullable()->after('divisi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panitia', function (Blueprint $table) {
            // Hapus foreign key dulu, baru kolomnya
            $table->dropForeign(['divisi_id']);
            $table->dropColumn('divisi_id');

            // Hapus kolom prodi jika ditambahkan
            // $table->dropColumn('prodi');
        });
    }
}
