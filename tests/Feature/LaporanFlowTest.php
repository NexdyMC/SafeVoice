<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Laporan;
use App\Models\LaporanStatusLog;
use App\Models\Siswa;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaporanFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $studentUser;

    private Siswa $siswa;

    protected function setUp(): void
    {
        parent::setUp();

        // Siapkan akun dan profil siswa
        $waliUser = User::create([
            'nama_lengkap' => 'Wali Kelas 1',
            'email' => 'wali@sekolah.sch.id',
            'password' => 'secret123',
            'role' => 'wali_kelas',
        ]);

        $wali = WaliKelas::create([
            'user_id' => $waliUser->id,
            'nip' => '198001012010011001',
            'no_hp' => '08123456789',
        ]);

        $kelas = Kelas::create([
            'nama_kelas' => 'X-RPL-1',
            'tahun_ajaran' => '2026/2027',
            'wali_kelas_id' => $wali->id,
        ]);

        $this->studentUser = User::create([
            'nama_lengkap' => 'Budi Siswa',
            'email' => 'budi@sekolah.sch.id',
            'password' => 'secret123',
            'role' => 'siswa',
        ]);

        $this->siswa = Siswa::create([
            'user_id' => $this->studentUser->id,
            'nis' => 'NIS-2026-001',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'L',
        ]);
    }

    /**
     * Test flowchart condition: Data tidak lengkap -> Pesan error ditampilkan.
     */
    public function test_flowchart_validation_error_when_incomplete_data(): void
    {
        $response = $this->actingAs($this->studentUser)->post(route('user.laporan.store'), [
            'jenis_bullying' => '', // kosong
            'lokasi_kejadian' => '', // kosong
            'deskripsi' => '', // kosong
        ]);

        $response->assertSessionHasErrors(['jenis_bullying', 'lokasi_kejadian', 'deskripsi', 'tanggal_kejadian']);
        $this->assertDatabaseCount('tb_laporan', 0);
    }

    /**
     * Test flowchart condition: Data lengkap -> Simpan laporan, bukti, catat log status awal.
     */
    public function test_flowchart_success_pipeline_saves_laporan_bukti_and_status_log(): void
    {
        Storage::fake('public');

        $fileBukti = UploadedFile::fake()->image('bukti_chat.png', 300, 300);

        $response = $this->actingAs($this->studentUser)->post(route('user.laporan.store'), [
            'jenis_bullying' => 'verbal',
            'terlapor' => 'Siswa X',
            'tanggal_kejadian' => date('Y-m-d'),
            'lokasi_kejadian' => 'Kantin Belakang Sekolah',
            'deskripsi' => 'Terjadi ejekan berulang saat istirahat siang.',
            'is_anonim' => '1',
            'bukti' => $fileBukti,
        ]);

        $response->assertRedirect(route('user.riwayat'));
        $response->assertSessionHas('success');
        $response->assertSessionHas('kode_laporan');

        // 1. Verifikasi data di tb_laporan
        $this->assertDatabaseHas('tb_laporan', [
            'pelapor_id' => $this->siswa->id,
            'jenis_bullying' => 'verbal',
            'terlapor' => 'Siswa X',
            'lokasi_kejadian' => 'Kantin Belakang Sekolah',
            'is_anonim' => true,
            'status' => 'pending',
            'konfirmasi_siswa' => 'menunggu',
        ]);

        // 2. Verifikasi berkas di tb_bukti_laporan dan storage fisik
        $this->assertDatabaseHas('tb_bukti_laporan', [
            'tipe_file' => 'image/png',
        ]);

        // 3. Verifikasi log status awal di tb_laporan_status_log
        $this->assertDatabaseHas('tb_laporan_status_log', [
            'status_lama' => '-',
            'status_baru' => 'pending',
            'diubah_oleh' => $this->studentUser->id,
        ]);
    }

    /**
     * Test laporan submission when user is unauthenticated (guest) does not trigger foreign key violation.
     */
    public function test_guest_submission_resolves_or_creates_valid_student_without_foreign_key_violation(): void
    {
        $response = $this->post(route('user.laporan.store'), [
            'jenis_bullying' => 'verbal',
            'terlapor' => 'Cahya',
            'tanggal_kejadian' => '2026-10-20',
            'lokasi_kejadian' => 'Kantin',
            'deskripsi' => 'Cahya minta uang 100',
            'is_anonim' => '1',
        ]);

        $response->assertRedirect(route('user.riwayat'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tb_laporan', [
            'terlapor' => 'Cahya',
            'deskripsi' => 'Cahya minta uang 100',
            'status' => 'pending',
        ]);
    }

    /**
     * Test riwayat isolation: only reports from the student or device are visible (private like google email).
     */
    public function test_riwayat_is_private_to_owner_and_not_public(): void
    {
        // 1. Buat laporan atas nama Student A
        $laporanA = Laporan::create([
            'kode_laporan' => 'LP-TEST-AAA',
            'pelapor_id' => $this->siswa->id,
            'korban_id' => $this->siswa->id,
            'terlapor' => 'Terlapor A',
            'jenis_bullying' => 'verbal',
            'deskripsi' => 'Deskripsi rahasia Student A',
            'tanggal_kejadian' => '2026-10-20',
            'lokasi_kejadian' => 'Kelas A',
            'is_anonim' => true,
            'status' => 'pending',
            'konfirmasi_siswa' => 'menunggu',
        ]);

        LaporanStatusLog::create([
            'laporan_id' => $laporanA->id,
            'status_lama' => '-',
            'status_baru' => 'pending',
            'umpan_balik' => 'Laporan A berhasil dikirim.',
            'diubah_oleh' => $this->studentUser->id,
            'created_at' => now(),
        ]);

        // Student A dapat melihat laporannya sendiri
        $responseStudentA = $this->actingAs($this->studentUser)->get(route('user.riwayat'));
        $responseStudentA->assertStatus(200);
        $responseStudentA->assertSee('LP-TEST-AAA');
        $responseStudentA->assertSee('Laporan A berhasil dikirim.');

        // Pengunjung lain dari perangkat lain (tanpa sesi / akun pelapor) TIDAK BISA melihat laporan Student A
        $this->flushSession();
        auth()->logout();
        $responseStranger = $this->get(route('user.riwayat'));
        $responseStranger->assertStatus(200);
        $responseStranger->assertDontSee('LP-TEST-AAA');
        $responseStranger->assertDontSee('Laporan A berhasil dikirim.');
        $responseStranger->assertSee('Belum ada riwayat aktivitas laporan dari perangkat ini');
    }

    /**
     * Test konfirmasi kondisi siswa saat kasus selesai.
     */
    public function test_student_can_confirm_condition_when_report_is_resolved(): void
    {
        $laporan = Laporan::create([
            'kode_laporan' => 'LP-TEST-SELESAI',
            'pelapor_id' => $this->siswa->id,
            'korban_id' => $this->siswa->id,
            'terlapor' => 'Terlapor B',
            'jenis_bullying' => 'fisik',
            'deskripsi' => 'Kasus fisik selesai dimediasi.',
            'tanggal_kejadian' => '2026-10-20',
            'lokasi_kejadian' => 'Lapangan',
            'is_anonim' => false,
            'status' => 'selesai',
            'konfirmasi_siswa' => 'menunggu',
        ]);

        $response = $this->actingAs($this->studentUser)->post(route('user.laporan.konfirmasi', $laporan->id), [
            'konfirmasi' => 'setuju',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tb_laporan', [
            'id' => $laporan->id,
            'konfirmasi_siswa' => 'setuju',
        ]);

        $this->assertDatabaseHas('tb_laporan_status_log', [
            'laporan_id' => $laporan->id,
            'status_baru' => 'selesai',
            'umpan_balik' => 'Siswa mengonfirmasi bahwa kondisi saat ini sudah aman.',
        ]);
    }
}
