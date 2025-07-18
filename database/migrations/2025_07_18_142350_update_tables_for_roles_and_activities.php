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
            $table->string('target_prodi')->nullable()->after('lokasi');
            $table->string('target_kelas')->nullable()->after('target_prodi');
            $table->string('target_tingkat')->nullable()->after('target_kelas');
            $table->string('target_jabatan')->nullable()->after('target_tingkat');
        });

        Schema::table('peserta', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('prodi')->nullable()->after('jabatan');
            $table->string('kelas')->nullable()->after('prodi');
            $table->string('tingkat')->nullable()->after('kelas');
        });

        Schema::table('panitia', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('prodi')->nullable()->after('jabatan');
            $table->string('kelas')->nullable()->after('prodi');
            $table->string('tingkat')->nullable()->after('kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropColumn(['target_prodi', 'target_kelas', 'target_tingkat', 'target_jabatan']);
        });

        Schema::table('peserta', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'prodi', 'kelas', 'tingkat']);
        });

        Schema::table('panitia', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'prodi', 'kelas', 'tingkat']);
        });
    }
};
