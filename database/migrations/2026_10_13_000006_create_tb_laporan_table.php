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
        Schema::create('tb_laporan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_laporan', 20)->unique();
            $table->foreignId('pelapor_id')->constrained('tb_siswa')->onDelete('cascade');
            $table->foreignId('korban_id')->constrained('tb_siswa')->onDelete('cascade');
            $table->string('terlapor', 150);
            $table->enum('jenis_bullying', ['verbal', 'fisik', 'sosial', 'cyber_bullying']);
            $table->text('deskripsi');
            $table->date('tanggal_kejadian');
            $table->string('lokasi_kejadian', 150);
            $table->boolean('is_anonim')->default(false);
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->foreignId('guru_bk_id')->nullable()->constrained('tb_guru_bk')->nullOnDelete();
            $table->enum('konfirmasi_siswa', ['menunggu', 'setuju', 'tidak_sesuai'])->default('menunggu');
            $table->timestamp('konfirmasi_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_laporan');
    }
};
