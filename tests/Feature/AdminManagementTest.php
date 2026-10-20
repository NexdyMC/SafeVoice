<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Laporan;
use App\Models\Siswa;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::create([
            'nama_lengkap' => 'Admin SafeVoice',
            'email' => 'admin@safevoice.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);
    }

    /**
     * Test admin dashboard page renders with real database stats.
     */
    public function test_admin_dashboard_renders_with_real_statistics(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('SafeVoice');
        $response->assertSee('Total laporan');
        $response->assertSee('Manajemen Seluruh Laporan');
        $response->assertSee('Manajemen Pengguna');
    }

    /**
     * Test admin can create a new student user with profile.
     */
    public function test_admin_can_create_new_student_user(): void
    {
        $waliUser = User::create([
            'nama_lengkap' => 'Wali Kelas 1',
            'email' => 'wali1@safevoice.id',
            'password' => bcrypt('password123'),
            'role' => 'wali_kelas',
        ]);

        $wali = WaliKelas::create([
            'user_id' => $waliUser->id,
            'nip' => '198701012011011001',
            'no_hp' => '081234567890',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'X-RPL-1',
            'tahun_ajaran' => '2026/2027',
            'wali_kelas_id' => $wali->id,
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.users.store'), [
            'nama_lengkap' => 'Siswa Baru',
            'email' => 'siswabaru@safevoice.id',
            'password' => 'secret123',
            'role' => 'siswa',
            'nis' => 'NIS-2026-999',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'L',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tb_users', [
            'email' => 'siswabaru@safevoice.id',
            'role' => 'siswa',
        ]);

        $this->assertDatabaseHas('tb_siswa', [
            'nis' => 'NIS-2026-999',
            'kelas_id' => $kelas->id,
        ]);
    }

    /**
     * Test admin can create a new class.
     */
    public function test_admin_can_create_new_kelas(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.kelas.store'), [
            'nama_kelas' => 'XI-TKJ-1',
            'tahun_ajaran' => '2026/2027',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tb_kelas', [
            'nama_kelas' => 'XI-TKJ-1',
            'tahun_ajaran' => '2026/2027',
        ]);
    }

    /**
     * Test admin can update report status and generate status log.
     */
    public function test_admin_can_update_laporan_status(): void
    {
        $waliUser = User::create([
            'nama_lengkap' => 'Wali Kelas',
            'email' => 'wali@safevoice.id',
            'password' => bcrypt('password123'),
            'role' => 'wali_kelas',
        ]);
        $wali = WaliKelas::create(['user_id' => $waliUser->id, 'nip' => '1987111', 'no_hp' => '08111']);
        $kelas = Kelas::create(['nama_kelas' => 'X-1', 'tahun_ajaran' => '2026/2027', 'wali_kelas_id' => $wali->id]);

        $siswaUser = User::create([
            'nama_lengkap' => 'Pelapor',
            'email' => 'pelapor@safevoice.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
        ]);
        $siswa = Siswa::create(['user_id' => $siswaUser->id, 'nis' => '112233', 'kelas_id' => $kelas->id, 'jenis_kelamin' => 'L']);

        $laporan = Laporan::create([
            'kode_laporan' => 'LP-UPDATE-001',
            'pelapor_id' => $siswa->id,
            'korban_id' => $siswa->id,
            'terlapor' => 'Siswa Z',
            'jenis_bullying' => 'verbal',
            'deskripsi' => 'Ejekan',
            'tanggal_kejadian' => '2026-10-20',
            'lokasi_kejadian' => 'Kantin',
            'status' => 'pending',
            'konfirmasi_siswa' => 'menunggu',
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.laporan.update', $laporan->id), [
            'status' => 'diproses',
            'catatan' => 'Laporan diteruskan ke Guru BK dan Wali Kelas untuk penanganan.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tb_laporan', [
            'id' => $laporan->id,
            'status' => 'diproses',
        ]);

        $this->assertDatabaseHas('tb_laporan_status_log', [
            'laporan_id' => $laporan->id,
            'status_lama' => 'pending',
            'status_baru' => 'diproses',
            'umpan_balik' => 'Laporan diteruskan ke Guru BK dan Wali Kelas untuk penanganan.',
        ]);
    }
}
