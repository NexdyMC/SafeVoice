<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLaporanRequest;
use App\Models\BuktiLaporan;
use App\Models\GuruBk;
use App\Models\Kelas;
use App\Models\Laporan;
use App\Models\LaporanStatusLog;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Store a newly created report in storage following the flowchart.
     */
    public function store(StoreLaporanRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Cari atau pastikan user pelapor dan siswa valid ada di database
        ['user' => $user, 'siswa' => $siswa] = $this->resolvePelapor(auth()->user());

        $pelaporId = $siswa->id;
        $korbanId = ! empty($validated['korban_id']) && Siswa::where('id', $validated['korban_id'])->exists()
            ? (int) $validated['korban_id']
            : $pelaporId;

        // 1. Generate kode_laporan unik
        $kodeLaporan = 'LP-'.date('Ymd').'-'.strtoupper(Str::random(4));

        // Transaksi penyimpanan data
        $laporan = DB::transaction(function () use ($validated, $kodeLaporan, $pelaporId, $korbanId, $request, $user) {
            // 2. Simpan Laporan ke tb_laporan
            $laporan = Laporan::create([
                'kode_laporan' => $kodeLaporan,
                'pelapor_id' => $pelaporId,
                'korban_id' => $korbanId,
                'terlapor' => $validated['terlapor'] ?? 'Tidak disebutkan',
                'jenis_bullying' => $validated['jenis_bullying'],
                'deskripsi' => $validated['deskripsi'],
                'tanggal_kejadian' => $validated['tanggal_kejadian'],
                'lokasi_kejadian' => $validated['lokasi_kejadian'],
                'is_anonim' => $request->boolean('is_anonim'),
                'status' => 'pending',
                'konfirmasi_siswa' => 'menunggu',
            ]);

            // 3. Simpan berkas bukti ke tb_bukti_laporan jika ada
            if ($request->hasFile('bukti')) {
                $file = $request->file('bukti');
                $path = $file->store('bukti_laporan', 'public');

                BuktiLaporan::create([
                    'laporan_id' => $laporan->id,
                    'path_file' => $path,
                    'tipe_file' => $file->getClientMimeType() ?: 'application/octet-stream',
                    'ukuran_kb' => (int) ceil($file->getSize() / 1024),
                ]);
            }

            // 4. Catat status awal ke tb_laporan_status_log
            LaporanStatusLog::create([
                'laporan_id' => $laporan->id,
                'status_lama' => '-',
                'status_baru' => 'pending',
                'umpan_balik' => 'Laporan baru berhasil dikirim dan menunggu verifikasi Guru BK.',
                'diubah_oleh' => $user->id,
                'created_at' => now(),
            ]);

            return $laporan;
        });

        // 5. Isolasi 1 HP / 1 Akun: Simpan ID laporan ke sesi & cookie perangkat ini
        $deviceReportIds = $this->getDeviceReportIds($request);
        if (! in_array($laporan->id, $deviceReportIds, true)) {
            $deviceReportIds[] = $laporan->id;
        }
        $request->session()->put('user_laporan_ids', $deviceReportIds);
        $cookie = cookie('sv_device_reports', json_encode($deviceReportIds), 60 * 24 * 365); // 1 tahun

        return redirect()->route('user.riwayat')->with([
            'success' => 'Laporan baru berhasil dikirim secara aman ke Guru BK.',
            'kode_laporan' => $laporan->kode_laporan,
        ])->withCookie($cookie);
    }

    /**
     * Tampilkan Beranda Siswa dengan laporan teraktual dan statistik pribadi.
     */
    public function beranda(Request $request): View
    {
        $authUser = auth()->user();
        $deviceReportIds = $this->getDeviceReportIds($request);

        $authorizedReportIds = [];
        if ($authUser && $authUser->isSiswa() && $authUser->siswa) {
            $studentReportIds = Laporan::where('pelapor_id', $authUser->siswa->id)->pluck('id')->all();
            $authorizedReportIds = array_merge($authorizedReportIds, $studentReportIds);
        }
        if (! empty($deviceReportIds)) {
            $authorizedReportIds = array_merge($authorizedReportIds, $deviceReportIds);
        }
        $authorizedReportIds = array_values(array_unique(array_filter($authorizedReportIds)));

        $latestLaporan = ! empty($authorizedReportIds)
            ? Laporan::with(['statusLogs.modifier', 'guruBk.user', 'bukti'])->whereIn('id', $authorizedReportIds)->latest()->first()
            : null;

        $totalLaporan = ! empty($authorizedReportIds) ? Laporan::whereIn('id', $authorizedReportIds)->count() : 0;
        $diprosesLaporan = ! empty($authorizedReportIds) ? Laporan::whereIn('id', $authorizedReportIds)->where('status', 'diproses')->count() : 0;
        $selesaiLaporan = ! empty($authorizedReportIds) ? Laporan::whereIn('id', $authorizedReportIds)->where('status', 'selesai')->count() : 0;

        $guruBkContact = GuruBk::with('user')->first();

        return view('user.beranda.beranda', compact(
            'latestLaporan',
            'totalLaporan',
            'diprosesLaporan',
            'selesaiLaporan',
            'guruBkContact'
        ));
    }

    /**
     * Tampilkan Profil Siswa dan Pusat Dukungan dengan data riil.
     */
    public function profile(Request $request): View
    {
        $authUser = auth()->user();
        $deviceReportIds = $this->getDeviceReportIds($request);

        $authorizedReportIds = [];
        if ($authUser && $authUser->isSiswa() && $authUser->siswa) {
            $studentReportIds = Laporan::where('pelapor_id', $authUser->siswa->id)->pluck('id')->all();
            $authorizedReportIds = array_merge($authorizedReportIds, $studentReportIds);
        }
        if (! empty($deviceReportIds)) {
            $authorizedReportIds = array_merge($authorizedReportIds, $deviceReportIds);
        }
        $authorizedReportIds = array_values(array_unique(array_filter($authorizedReportIds)));

        $siswa = $authUser?->siswa ?? Siswa::with(['user', 'kelas'])->first();

        $totalLaporan = ! empty($authorizedReportIds) ? Laporan::whereIn('id', $authorizedReportIds)->count() : 0;
        $diprosesLaporan = ! empty($authorizedReportIds) ? Laporan::whereIn('id', $authorizedReportIds)->where('status', 'diproses')->count() : 0;
        $selesaiLaporan = ! empty($authorizedReportIds) ? Laporan::whereIn('id', $authorizedReportIds)->where('status', 'selesai')->count() : 0;

        $guruBkContact = GuruBk::with('user')->first();

        return view('user.beranda.profile', compact(
            'siswa',
            'guruBkContact',
            'totalLaporan',
            'diprosesLaporan',
            'selesaiLaporan'
        ));
    }

    /**
     * Tampilkan riwayat aktivitas laporan (Privat: 1 Akun / 1 HP = 1 Riwayat).
     */
    public function riwayat(Request $request): View
    {
        $authUser = auth()->user();
        $deviceReportIds = $this->getDeviceReportIds($request);

        // Ambil ID laporan yang menjadi hak milik akun atau perangkat HP ini
        $authorizedReportIds = [];

        if ($authUser && $authUser->isSiswa() && $authUser->siswa) {
            $studentReportIds = Laporan::where('pelapor_id', $authUser->siswa->id)->pluck('id')->all();
            $authorizedReportIds = array_merge($authorizedReportIds, $studentReportIds);
        }

        if (! empty($deviceReportIds)) {
            $authorizedReportIds = array_merge($authorizedReportIds, $deviceReportIds);
        }

        $authorizedReportIds = array_values(array_unique(array_filter($authorizedReportIds)));

        // Ambil riwayat log status hanya dari laporan yang berhak diakses perangkat ini
        $selectedFilter = $request->query('status', 'semua');

        $logQuery = LaporanStatusLog::with(['laporan.pelapor.user', 'laporan.guruBk.user', 'modifier'])
            ->whereIn('laporan_id', $authorizedReportIds);

        if ($selectedFilter !== 'semua' && in_array($selectedFilter, ['pending', 'diproses', 'selesai', 'ditolak'], true)) {
            $logQuery->where('status_baru', $selectedFilter);
        }

        $logs = $logQuery->orderBy('created_at', 'desc')->get();

        // Kelompokkan log berdasarkan tanggal kronologis (Hari Ini, Kemarin, Tanggal Spesifik)
        $groupedLogs = $logs->groupBy(function ($log) {
            $createdAt = Carbon::parse($log->created_at)->startOfDay();
            $today = Carbon::today();
            $yesterday = Carbon::yesterday();

            if ($createdAt->equalTo($today)) {
                return 'Hari Ini|'.Carbon::parse($log->created_at)->translatedFormat('d F Y');
            }

            if ($createdAt->equalTo($yesterday)) {
                return 'Kemarin|'.Carbon::parse($log->created_at)->translatedFormat('d F Y');
            }

            return Carbon::parse($log->created_at)->translatedFormat('d F').'|'.Carbon::parse($log->created_at)->translatedFormat('d M Y');
        });

        return view('user.beranda.riwayat', compact('groupedLogs', 'selectedFilter', 'authorizedReportIds'));
    }

    /**
     * Konfirmasi kondisi siswa setelah kasus ditangani (Sudah aman / Masih terjadi).
     */
    public function konfirmasi(Request $request, Laporan $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'konfirmasi' => 'required|in:setuju,tidak_sesuai',
        ]);

        // Verifikasi kepemilikan laporan
        $deviceReportIds = $this->getDeviceReportIds($request);
        $isOwner = (auth()->check() && auth()->user()->siswa?->id === $laporan->pelapor_id)
            || in_array($laporan->id, $deviceReportIds, true);

        if (! $isOwner) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang mengonfirmasi laporan ini.');
        }

        $laporan->update([
            'konfirmasi_siswa' => $validated['konfirmasi'],
            'konfirmasi_at' => now(),
        ]);

        $pesanKonfirmasi = $validated['konfirmasi'] === 'setuju'
            ? 'Siswa mengonfirmasi bahwa kondisi saat ini sudah aman.'
            : 'Siswa mengonfirmasi bahwa tindakan perundungan masih terjadi dan memerlukan perhatian lanjutan.';

        LaporanStatusLog::create([
            'laporan_id' => $laporan->id,
            'status_lama' => $laporan->status,
            'status_baru' => $laporan->status,
            'umpan_balik' => $pesanKonfirmasi,
            'diubah_oleh' => auth()->id() ?? $laporan->pelapor->user_id,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Konfirmasi kondisimu berhasil dicatat.');
    }

    /**
     * Ambil daftar ID laporan yang tersimpan pada sesi atau cookie perangkat HP ini.
     *
     * @return array<int>
     */
    private function getDeviceReportIds(Request $request): array
    {
        $sessionIds = $request->session()->get('user_laporan_ids', []);
        $rawCookie = $request->cookie('sv_device_reports');
        $cookieIds = $rawCookie ? (json_decode($rawCookie, true) ?: []) : [];

        $combined = array_merge((array) $sessionIds, (array) $cookieIds);

        return array_map('intval', array_values(array_unique(array_filter($combined))));
    }

    /**
     * Pastikan akun user pelapor dan profil siswa valid dan ada di database.
     *
     * @return array{user: User, siswa: Siswa}
     */
    private function resolvePelapor(?User $authUser): array
    {
        $user = $authUser ?? User::where('role', 'siswa')->first();

        if (! $user) {
            $user = User::create([
                'nama_lengkap' => 'Siswa Pelapor',
                'email' => 'siswa.default@safevoice.id',
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'is_active' => true,
            ]);
        }

        $siswa = $user->siswa ?? Siswa::first();

        if (! $siswa) {
            $kelas = Kelas::firstOrCreate(
                ['nama_kelas' => 'X-MIPA-1'],
                ['tahun_ajaran' => '2026/2027']
            );

            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nis' => 'NIS-'.rand(100000, 999999),
                'kelas_id' => $kelas->id,
                'jenis_kelamin' => 'L',
            ]);
        }

        return ['user' => $user, 'siswa' => $siswa];
    }
}
