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
        Schema::create('tb_users', function (Blueprint $table) {
            $table->id("id");
            $table->string('nama_lengkap', 200);
            $table->string('email', 100);
            $table->string('password', 100);
            $table->enum('role', ['siswa', 'guru_bk', 'admin', 'wali_kelas'])->default('siswa');
            $table->boolean('is_active')->true();
            $table->timestamps('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
