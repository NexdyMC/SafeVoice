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
        Schema::create('tb_laporan_status_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('tb_laporan')->onDelete('cascade');
            $table->string('status_lama', 20);
            $table->string('status_baru', 20);
            $table->text('umpan_balik')->nullable();
            $table->foreignId('diubah_oleh')->constrained('tb_users')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_laporan_status_log');
    }
};
