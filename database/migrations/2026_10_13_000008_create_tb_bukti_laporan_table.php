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
        Schema::create('tb_bukti_laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('tb_laporan')->onDelete('cascade');
            $table->string('path_file', 255);
            $table->string('tipe_file', 50);
            $table->unsignedInteger('ukuran_kb');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_bukti_laporan');
    }
};
