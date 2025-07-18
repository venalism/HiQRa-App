<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('peserta', function (Blueprint $table) {
            $table->unsignedBigInteger('kelas_id')->nullable()->after('id');

            // Foreign key constraint
            $table->foreign('kelas_id')
                  ->references('id')
                  ->on('kelas')
                  ->onDelete('set null'); // atau cascade jika ingin menghapus peserta saat kelas dihapus
        });
    }

    public function down(): void {
        Schema::table('peserta', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });
    }
};
