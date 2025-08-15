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
        Schema::table('panitia', function (Blueprint $table) {
            // Menambahkan index unik ke kolom no_hp
            $table->unique('no_hp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('panitia', function (Blueprint $table) {
            // Menghapus index unik jika migrasi di-rollback
            $table->dropUnique(['no_hp']);
        });
    }
};