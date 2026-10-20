<?php

namespace Tests\Feature;

use App\Models\BuktiLaporan;
use App\Models\GuruBk;
use App\Models\Kelas;
use App\Models\KonsultasiWaliKelas;
use App\Models\Laporan;
use App\Models\LaporanStatusLog;
use App\Models\Siswa;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseSchemaAndModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that all 9 tables from ERD SafeVoice exist.
     */
    public function test_all_erd_tables_exist(): void
    {
        $tables = [
            'tb_users',
            'tb_wali_kelas',
            'tb_guru_bk',
            'tb_kelas',
            'tb_siswa',
            'tb_laporan',
            'tb_laporan_status_log',
            'tb_bukti_laporan',
            'tb_konsultasi_wali_kelas',
        ];

        foreach ($tables as $table) {
            $this->assertTrue(Schema::hasTable($table), "Table {$table} does not exist in the database.");
        }
    }

    /**
     * Test User profile 1:1 relations and role check methods.
     */
    public function test_user_and_profiles_relationships(): void
    {
        $userWali = User::create([
            'nama_lengkap' => 'Budi Santoso, S.Pd',
            'email' => 'budi@sekolah.sch.id',
            'password' => 'secret123',
            'role' => 'wali_kelas',
            'is_active' => true,
        ]);

        $waliKelas = WaliKelas::create([
            'user_id' => $userWali->id,
            'nip' => '198501012010011001',
            'no_hp' => '081234567890',
        ]);

        $this->assertTrue($userWali->isWaliKelas());
        $this->assertEquals($waliKelas->id, $userWali->waliKelas->id);
        $this->assertEquals($userWali->id, $waliKelas->user->id);

        $userBk = User::create([
            'nama_lengkap' => 'Siti Aminah, M.Psi',
            'email' => 'siti@sekolah.sch.id',
            'password' => 'secret123',
            'role' => 'guru_bk',
            'is_active' => true,
        ]);

        $guruBk = GuruBk::create([
            'user_id' => $userBk->id,
            'nip' => '198802022012012002',
            'no_hp' => '081298765432',
        ]);

        $this->assertTrue($userBk->isGuruBk());
        $this->assertEquals($guruBk->id, $userBk->guruBk->id);
    }

    /**
     * Test Kelas and Siswa relations, along with Laporan creation.
     */
    public function test_laporan_dual_role_and_child_relations(): void
    {
        // 1. Wali Kelas & Kelas
        $userWali = User::create([
            'nama_lengkap' => 'Wali Kelas 1',
            'email' => 'wali1@sekolah.sch.id',
            'password' => 'secret123',
            'role' => 'wali_kelas',
        ]);
        $wali = WaliKelas::create([
            'user_id' => $userWali->id,
            'nip' => '198001012000011001',
            'no_hp' => '0811111111',
        ]);
        $kelas = Kelas::create([
            'nama_kelas' => 'X-IPA-1',
            'tahun_ajaran' => '2026/2027',
            'wali_kelas_id' => $wali->id,
        ]);

        // 2. Siswa Pelapor & Siswa Korban
        $userPelapor = User::create([
            'nama_lengkap' => 'Ahmad Pelapor',
            'email' => 'ahmad@sekolah.sch.id',
            'password' => 'secret123',
            'role' => 'siswa',
        ]);
        $pelapor = Siswa::create([
            'user_id' => $userPelapor->id,
            'nis' => '10001',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'L',
        ]);

        $userKorban = User::create([
            'nama_lengkap' => 'Rina Korban',
            'email' => 'rina@sekolah.sch.id',
            'password' => 'secret123',
            'role' => 'siswa',
        ]);
        $korban = Siswa::create([
            'user_id' => $userKorban->id,
            'nis' => '10002',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'P',
        ]);

        // 3. Guru BK
        $userBk = User::create([
            'nama_lengkap' => 'Dewi BK',
            'email' => 'dewi@sekolah.sch.id',
            'password' => 'secret123',
            'role' => 'guru_bk',
        ]);
        $guruBk = GuruBk::create([
            'user_id' => $userBk->id,
            'nip' => '198701012011012001',
            'no_hp' => '0822222222',
        ]);

        // 4. Laporan
        $laporan = Laporan::create([
            'kode_laporan' => 'LP-2026-0001',
            'pelapor_id' => $pelapor->id,
            'korban_id' => $korban->id,
            'terlapor' => 'Oknum Terlapor',
            'jenis_bullying' => 'verbal',
            'deskripsi' => 'Terjadi ejekan berulang saat istirahat siang.',
            'tanggal_kejadian' => '2026-10-18',
            'lokasi_kejadian' => 'Kantin Belakang',
            'is_anonim' => false,
            'status' => 'diproses',
            'guru_bk_id' => $guruBk->id,
            'konfirmasi_siswa' => 'menunggu',
        ]);

        // Assert Laporan Relations
        $this->assertEquals($pelapor->id, $laporan->pelapor->id);
        $this->assertEquals($korban->id, $laporan->korban->id);
        $this->assertEquals($guruBk->id, $laporan->guruBk->id);

        // 5. Bukti Laporan
        $bukti = BuktiLaporan::create([
            'laporan_id' => $laporan->id,
            'path_file' => 'evidence/chat_screenshot.png',
            'tipe_file' => 'image/png',
            'ukuran_kb' => 256,
        ]);
        $this->assertCount(1, $laporan->bukti);
        $this->assertEquals('evidence/chat_screenshot.png', $laporan->bukti->first()->path_file);

        // 6. Status Log
        $log = LaporanStatusLog::create([
            'laporan_id' => $laporan->id,
            'status_lama' => 'pending',
            'status_baru' => 'diproses',
            'umpan_balik' => 'Laporan sedang ditelaah oleh Guru BK.',
            'diubah_oleh' => $userBk->id,
        ]);
        $this->assertCount(1, $laporan->statusLogs);
        $this->assertEquals('diproses', $laporan->statusLogs->first()->status_baru);
        $this->assertEquals($userBk->id, $log->modifier->id);

        // 7. Konsultasi Wali Kelas
        $konsultasi = KonsultasiWaliKelas::create([
            'laporan_id' => $laporan->id,
            'guru_bk_id' => $guruBk->id,
            'wali_kelas_id' => $wali->id,
            'hasil_diskusi' => 'Disepakati memanggil korban terlebih dahulu untuk pendampingan.',
            'kesimpulan' => 'tindak_lanjut_bk',
            'tanggal_diskusi' => now(),
        ]);
        $this->assertCount(1, $laporan->konsultasi);
        $this->assertEquals('tindak_lanjut_bk', $laporan->konsultasi->first()->kesimpulan);

        // 8. Soft Deletes check
        $laporan->delete();
        $this->assertSoftDeleted('tb_laporan', ['id' => $laporan->id]);
    }
}
