<?php

namespace Tests\Feature;

use App\Models\GuruBk;
use App\Models\Kelas;
use App\Models\Laporan;
use App\Models\Siswa;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruBkFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $userBk;

    private GuruBk $guruBk;

    private User $userWali;

    private WaliKelas $waliKelas;

    private Kelas $kelas;

    private Siswa $siswa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userBk = User::create([
            'nama_lengkap' => 'Guru BK Ratna',
            'email' => 'ratna.bk@sekolah.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'guru_bk',
        ]);
        $this->guruBk = GuruBk::create([
            'user_id' => $this->userBk->id,
            'nip' => '198201012005012001',
            'no_hp' => '081234567890',
        ]);

        $this->userWali = User::create([
            'nama_lengkap' => 'Wali Kelas Dedi',
            'email' => 'dedi.wali@sekolah.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'wali_kelas',
        ]);
        $this->waliKelas = WaliKelas::create([
            'user_id' => $this->userWali->id,
            'nip' => '198502022008011002',
            'no_hp' => '081234567891',
        ]);

        $this->kelas = Kelas::create([
            'nama_kelas' => 'XI-RPL-1',
            'tahun_ajaran' => '2026/2027',
            'wali_kelas_id' => $this->waliKelas->id,
        ]);

        $userSiswa = User::create([
            'nama_lengkap' => 'Siswa Pelapor',
            'email' => 'siswa@sekolah.sch.id',
            'password' => bcrypt('password123'),
            'role' => 'siswa',
        ]);
        $this->siswa = Siswa::create([
            'user_id' => $userSiswa->id,
            'nis' => '10293847',
            'kelas_id' => $this->kelas->id,
            'jenis_kelamin' => 'L',
        ]);
    }

    /**
     * Flowchart 1: Guru BK menolak laporan langsung dengan alasan wajib.
     */
    public function test_menu_1_guru_bk_can_reject_report_with_required_feedback(): void
    {
        $laporan = Laporan::create([
            'kode_laporan' => 'LP-FLOW-001',
            'pelapor_id' => $this->siswa->id,
            'korban_id' => $this->siswa->id,
            'terlapor' => 'Siswa Z',
            'jenis_bullying' => 'verbal',
            'deskripsi' => 'Laporan uji coba penolakan.',
            'tanggal_kejadian' => '2026-10-20',
            'lokasi_kejadian' => 'Kantin',
            'status' => 'pending',
            'konfirmasi_siswa' => 'menunggu',
        ]);

        $response = $this->actingAs($this->userBk)->post(route('admin.bk.verifikasi', $laporan->id), [
            'keputusan' => 'tolak',
            'alasan_tolak' => 'Bukti kurang memadai dan tidak ditemukan indikasi pelanggaran.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tb_laporan', [
            'id' => $laporan->id,
            'status' => 'ditolak',
        ]);

        $this->assertDatabaseHas('tb_laporan_status_log', [
            'laporan_id' => $laporan->id,
            'status_baru' => 'ditolak',
            'umpan_balik' => 'Bukti kurang memadai dan tidak ditemukan indikasi pelanggaran.',
        ]);
    }

    /**
     * Flowchart 1 & 4: Guru BK meneruskan laporan layak ke Wali Kelas (status: diproses).
     */
    public function test_menu_1_guru_bk_can_verify_and_forward_report_to_wali_kelas(): void
    {
        $laporan = Laporan::create([
            'kode_laporan' => 'LP-FLOW-002',
            'pelapor_id' => $this->siswa->id,
            'korban_id' => $this->siswa->id,
            'terlapor' => 'Siswa B',
            'jenis_bullying' => 'fisik',
            'deskripsi' => 'Dorongan di tangga.',
            'tanggal_kejadian' => '2026-10-20',
            'lokasi_kejadian' => 'Tangga Gedung A',
            'status' => 'pending',
            'konfirmasi_siswa' => 'menunggu',
        ]);

        $response = $this->actingAs($this->userBk)->post(route('admin.bk.verifikasi', $laporan->id), [
            'keputusan' => 'teruskan',
            'guru_bk_id' => $this->guruBk->id,
            'wali_kelas_id' => $this->waliKelas->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tb_laporan', [
            'id' => $laporan->id,
            'status' => 'diproses',
            'guru_bk_id' => $this->guruBk->id,
        ]);

        $this->assertDatabaseHas('tb_laporan_status_log', [
            'laporan_id' => $laporan->id,
            'status_lama' => 'pending',
            'status_baru' => 'diproses',
        ]);
    }

    /**
     * Flowchart 2: Diskusi BK & Wali Kelas mencatat notulensi dan memutuskan Fakta vs Hoaks.
     */
    public function test_menu_2_guru_bk_can_record_consultation_notes_and_decide_fact_or_hoax(): void
    {
        $laporan = Laporan::create([
            'kode_laporan' => 'LP-FLOW-003',
            'pelapor_id' => $this->siswa->id,
            'korban_id' => $this->siswa->id,
            'terlapor' => 'Siswa C',
            'jenis_bullying' => 'cyber_bullying',
            'deskripsi' => 'Grup chat penghinaan.',
            'tanggal_kejadian' => '2026-10-20',
            'lokasi_kejadian' => 'Online',
            'status' => 'diproses',
            'konfirmasi_siswa' => 'menunggu',
        ]);

        $response = $this->actingAs($this->userBk)->post(route('admin.bk.konsultasi', $laporan->id), [
            'hasil_diskusi' => 'Telah dilakukan pengecekan tangkapan layar bersama wali kelas.',
            'informasi_cukup' => 'ya',
            'status_fakta' => 'fakta',
            'kesimpulan' => 'mediasi',
            'wali_kelas_id' => $this->waliKelas->id,
            'guru_bk_id' => $this->guruBk->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tb_konsultasi_wali_kelas', [
            'laporan_id' => $laporan->id,
            'kesimpulan' => 'mediasi',
        ]);

        $this->assertDatabaseHas('tb_laporan_status_log', [
            'laporan_id' => $laporan->id,
            'status_baru' => 'diproses',
        ]);
    }

    /**
     * Flowchart 3: Penanganan tuntas dan status diubah ke selesai (menunggu konfirmasi siswa).
     */
    public function test_menu_3_guru_bk_can_resolve_case(): void
    {
        $laporan = Laporan::create([
            'kode_laporan' => 'LP-FLOW-004',
            'pelapor_id' => $this->siswa->id,
            'korban_id' => $this->siswa->id,
            'terlapor' => 'Siswa D',
            'jenis_bullying' => 'verbal',
            'deskripsi' => 'Ejekan berulang.',
            'tanggal_kejadian' => '2026-10-20',
            'lokasi_kejadian' => 'Kelas',
            'status' => 'diproses',
            'konfirmasi_siswa' => 'menunggu',
        ]);

        $response = $this->actingAs($this->userBk)->post(route('admin.bk.selesai', $laporan->id), [
            'tindakan' => 'Mediasi Damai dan Pembinaan',
            'catatan' => 'Kedua pihak telah saling memaafkan dan menandatangani surat kesepakatan damai.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tb_laporan', [
            'id' => $laporan->id,
            'status' => 'selesai',
            'konfirmasi_siswa' => 'menunggu',
        ]);
    }

    /**
     * Flowchart 3: Kembalikan status ke 'diproses' jika siswa mengabarkan 'Masih terjadi'.
     */
    public function test_menu_3_guru_bk_can_reopen_case_if_bullying_persists(): void
    {
        $laporan = Laporan::create([
            'kode_laporan' => 'LP-FLOW-005',
            'pelapor_id' => $this->siswa->id,
            'korban_id' => $this->siswa->id,
            'terlapor' => 'Siswa E',
            'jenis_bullying' => 'verbal',
            'deskripsi' => 'Ejekan belum berhenti.',
            'tanggal_kejadian' => '2026-10-20',
            'lokasi_kejadian' => 'Kelas',
            'status' => 'selesai',
            'konfirmasi_siswa' => 'tidak_sesuai', // Siswa menyatakan masih terjadi!
        ]);

        $response = $this->actingAs($this->userBk)->post(route('admin.bk.kembalikan', $laporan->id), [
            'catatan' => 'Siswa melapor masih terjadi perundungan, tim BK memanggil ulang pihak terkait.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tb_laporan', [
            'id' => $laporan->id,
            'status' => 'diproses',
        ]);

        $this->assertDatabaseHas('tb_laporan_status_log', [
            'laporan_id' => $laporan->id,
            'status_lama' => 'selesai',
            'status_baru' => 'diproses',
        ]);
    }
}
