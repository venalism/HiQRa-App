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
        Schema::create('kegiatan_target_divisi', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kegiatan_id')->constrained('kegiatan')->onDelete('cascade');
        $table->foreignId('divisi_id')->constrained('divisis')->onDelete('cascade');
        $table->timestamps();
    });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan_target_divisi');
    }
};
