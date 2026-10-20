<?php

namespace App\Http\Controllers;

use App\Models\GuruBk;
use App\Models\Kelas;
use App\Models\Laporan;
use App\Models\LaporanStatusLog;
use App\Models\Siswa;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard dan manajemen data admin.
     */
    public function index(Request $request): View
    {
        // 1. KPI dan Statistik Utama
        $totalLaporan = Laporan::count();
        $pendingCount = Laporan::where('status', 'pending')->count();
        $diprosesCount = Laporan::where('status', 'diproses')->count();
        $selesaiCount = Laporan::where('status', 'selesai')->count();
        $ditolakCount = Laporan::where('status', 'ditolak')->count();

        // 2. Statistik Jenis Perundungan
        $jenisCounts = [
            'verbal' => Laporan::where('jenis_bullying', 'verbal')->count(),
            'fisik' => Laporan::where('jenis_bullying', 'fisik')->count(),
            'sosial' => Laporan::where('jenis_bullying', 'sosial')->count(),
            'cyber_bullying' => Laporan::where('jenis_bullying', 'cyber_bullying')->count(),
        ];

        // 3. Data Laporan Lengkap
        $laporans = Laporan::with(['pelapor.user', 'pelapor.kelas', 'korban.user', 'guruBk.user', 'bukti'])
            ->latest()
            ->get();

        // 4. Data Master Pengguna
        $users = User::with(['siswa.kelas', 'guruBk', 'waliKelas'])
            ->latest()
            ->get();

        // 5. Data Master Kelas
        $kelasList = Kelas::with(['waliKelas.user', 'siswa'])
            ->withCount('siswa')
            ->get();

        // 6. Data Guru BK & Wali Kelas untuk opsi penugasan
        $guruBkList = GuruBk::with('user')->get();
        $waliKelasList = WaliKelas::with('user')->get();

        // 7. Log Aktivitas Terbaru
        $statusLogs = LaporanStatusLog::with(['laporan', 'modifier'])
            ->latest('created_at')
            ->take(15)
            ->get();

        // 8. Koleksi Khusus 3 Alur Menu Guru BK (Berdasarkan Flowchart)
        $laporanPending = Laporan::with(['pelapor.user', 'pelapor.kelas.waliKelas.user', 'korban.user', 'bukti'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $laporanKonsultasi = Laporan::with(['pelapor.user', 'pelapor.kelas.waliKelas.user', 'konsultasi.guruBk.user', 'konsultasi.waliKelas.user', 'bukti'])
            ->where('status', 'diproses')
            ->latest()
            ->get();

        $laporanTindakLanjut = Laporan::with(['pelapor.user', 'pelapor.kelas', 'guruBk.user', 'konsultasi'])
            ->whereIn('status', ['diproses', 'selesai'])
            ->latest()
            ->get();

        return view('admin.dashboard.home', compact(
            'totalLaporan',
            'pendingCount',
            'diprosesCount',
            'selesaiCount',
            'ditolakCount',
            'jenisCounts',
            'laporans',
            'users',
            'kelasList',
            'guruBkList',
            'waliKelasList',
            'statusLogs',
            'laporanPending',
            'laporanKonsultasi',
            'laporanTindakLanjut'
        ));
    }

    /**
     * Tambah data pengguna baru (Admin, Guru BK, Wali Kelas, atau Siswa).
     */
    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|unique:tb_users,email|max:150',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,guru_bk,wali_kelas,siswa',
            'nis' => 'nullable|string|max:20|unique:tb_siswa,nis',
            'nip' => 'nullable|string|max:25',
            'kelas_id' => 'nullable|exists:tb_kelas,id',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        if ($validated['role'] === 'siswa') {
            Siswa::create([
                'user_id' => $user->id,
                'nis' => $validated['nis'] ?: 'NIS-'.rand(100000, 999999),
                'kelas_id' => $validated['kelas_id'] ?: (Kelas::first()?->id ?? 1),
                'jenis_kelamin' => $validated['jenis_kelamin'] ?? 'L',
            ]);
        } elseif ($validated['role'] === 'guru_bk') {
            GuruBk::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'] ?: 'NIP-'.rand(10000000, 99999999),
                'no_hp' => $validated['no_hp'] ?: '081200000000',
            ]);
        } elseif ($validated['role'] === 'wali_kelas') {
            WaliKelas::create([
                'user_id' => $user->id,
                'nip' => $validated['nip'] ?: 'NIP-'.rand(10000000, 99999999),
                'no_hp' => $validated['no_hp'] ?: '081200000000',
            ]);
        }

        return back()->with('success', 'Pengguna baru berhasil ditambahkan ke database.');
    }

    /**
     * Tambah data kelas baru.
     */
    public function storeKelas(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:20',
            'tahun_ajaran' => 'required|string|max:9',
            'wali_kelas_id' => 'nullable|exists:tb_wali_kelas,id',
        ]);

        Kelas::create($validated);

        return back()->with('success', 'Kelas baru berhasil dibuat.');
    }

    /**
     * Update status laporan atau penugasan Guru BK dari panel admin.
     */
    public function updateLaporan(Request $request, Laporan $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,diproses,selesai,ditolak',
            'guru_bk_id' => 'nullable|exists:tb_guru_bk,id',
            'catatan' => 'nullable|string|max:500',
        ]);

        $statusLama = $laporan->status;
        $statusBaru = $validated['status'];

        $laporan->update([
            'status' => $statusBaru,
            'guru_bk_id' => $validated['guru_bk_id'] ?? $laporan->guru_bk_id,
        ]);

        if ($statusLama !== $statusBaru || ! empty($validated['catatan'])) {
            LaporanStatusLog::create([
                'laporan_id' => $laporan->id,
                'status_lama' => $statusLama,
                'status_baru' => $statusBaru,
                'umpan_balik' => $validated['catatan'] ?: "Status diperbarui menjadi {$statusBaru} oleh Administrator.",
                'diubah_oleh' => auth()->id() ?? User::where('role', 'admin')->first()?->id ?? 1,
                'created_at' => now(),
            ]);
        }

        return back()->with('success', "Status laporan {$laporan->kode_laporan} berhasil diperbarui.");
    }
}
