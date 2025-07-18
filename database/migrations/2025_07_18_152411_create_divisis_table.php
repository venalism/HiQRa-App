<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('divisis', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->foreignId('jabatan_id')->constrained('jabatans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('divisis');
    }
};