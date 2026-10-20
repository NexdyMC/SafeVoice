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
        Schema::create('tb_konsultasi_wali_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('tb_laporan')->onDelete('cascade');
            $table->foreignId('guru_bk_id')->constrained('tb_guru_bk')->onDelete('cascade');
            $table->foreignId('wali_kelas_id')->constrained('tb_wali_kelas')->onDelete('cascade');
            $table->text('hasil_diskusi');
            $table->enum('kesimpulan', [
                'tindak_lanjut_bk',
                'panggilan_orang_tua',
                'mediasi',
                'selesai',
            ]);
            $table->dateTime('tanggal_diskusi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_konsultasi_wali_kelas');
    }
};
