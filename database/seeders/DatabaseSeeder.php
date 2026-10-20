<?php

namespace Database\Seeders;

use App\Models\GuruBk;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin
        User::firstOrCreate(
            ['email' => 'admin@safevoice.id'],
            [
                'nama_lengkap' => 'Administrator SafeVoice',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // 2. Guru BK
        $userBk = User::firstOrCreate(
            ['email' => 'gurubk@safevoice.id'],
            [
                'nama_lengkap' => 'Dra. Siti Rahmawati, M.Pd (Guru BK)',
                'password' => Hash::make('password123'),
                'role' => 'guru_bk',
                'is_active' => true,
            ]
        );
        GuruBk::firstOrCreate(
            ['user_id' => $userBk->id],
            [
                'nip' => '198501012010012001',
                'no_hp' => '081234567890',
            ]
        );

        // 3. Wali Kelas
        $userWali = User::firstOrCreate(
            ['email' => 'walikelas@safevoice.id'],
            [
                'nama_lengkap' => 'Budi Santoso, S.Pd (Wali Kelas)',
                'password' => Hash::make('password123'),
                'role' => 'wali_kelas',
                'is_active' => true,
            ]
        );
        $wali = WaliKelas::firstOrCreate(
            ['user_id' => $userWali->id],
            [
                'nip' => '198702022011021002',
                'no_hp' => '081234567891',
            ]
        );

        // 4. Kelas
        $kelas = Kelas::firstOrCreate(
            ['nama_kelas' => 'X-MIPA-1'],
            [
                'tahun_ajaran' => '2026/2027',
                'wali_kelas_id' => $wali->id,
            ]
        );

        // 5. Siswa
        $userSiswa = User::firstOrCreate(
            ['email' => 'siswa@safevoice.id'],
            [
                'nama_lengkap' => 'Ahmad Faiz (Siswa)',
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'is_active' => true,
            ]
        );
        Siswa::firstOrCreate(
            ['user_id' => $userSiswa->id],
            [
                'nis' => '10293847',
                'kelas_id' => $kelas->id,
                'jenis_kelamin' => 'L',
            ]
        );
    }
}
