<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->foreignId('target_divisi_id')->nullable()->constrained('divisis')->onDelete('set null');
            $table->foreignId('target_jabatan_id')->nullable()->constrained('jabatans')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('kegiatan', function (Blueprint $table) {
            $table->dropForeign(['target_divisi_id']);
            $table->dropColumn('target_divisi_id');
            $table->dropForeign(['target_jabatan_id']);
            $table->dropColumn('target_jabatan_id');
        });
    }
};