@extends('layouts.admin')

@section('title', 'Dashboard Admin & Guru BK - SafeVoice')

@section('content')
<main class="flex-1 p-6 space-y-6 overflow-y-auto" x-data="{ 
    selectedLaporan: null, 
    showModalLaporan: false,
    showModalUser: false,
    showModalKelas: false,
    filterStatusLaporan: 'semua'
}">

    {{-- Pesan Flash Notifikasi --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-sm">&times;</button>
        </div>
    @endif

    {{-- ================= TAB 1: RINGKASAN ================= --}}
    <div x-show="currentMenu === 'ringkasan'" class="space-y-6">

        <!-- Alert Peringatan Keterlambatan -->
        @if($pendingCount > 0)
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-amber-900">{{ $pendingCount }} laporan baru belum ditangani</h3>
                        <p class="text-xs text-amber-700 mt-0.5">Guru BK perlu menindaklanjuti dan memverifikasi laporan agar siswa segera mendapatkan rasa aman.</p>
                    </div>
                </div>
                <button @click="currentMenu = 'laporan'" class="px-4 py-2 rounded-xl bg-oceanMain hover:bg-oceanDark text-white font-bold text-xs shadow-sm transition-colors shrink-0 text-center">
                    Tinjau laporan sekarang
                </button>
            </div>
        @endif

        <!-- Kartu Statistik Utama (4 Kartu) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Laporan -->
            <div class="p-5 rounded-2xl bg-white border border-gray-100 card-hover shadow-sm space-y-1">
                <span class="text-xs font-semibold text-textSec">Total laporan</span>
                <div class="flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-oceanMain">{{ $totalLaporan }}</span>
                    <span class="text-[11px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">Database</span>
                </div>
                <p class="text-[11px] text-textSec pt-1 border-t border-gray-50">Tercatat di sistem SafeVoice</p>
            </div>

            <!-- Perlu Tindakan -->
            <div class="p-5 rounded-2xl bg-white border border-gray-100 card-hover shadow-sm space-y-1">
                <span class="text-xs font-semibold text-textSec">Perlu tindakan</span>
                <div class="flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-amber-600">{{ $pendingCount }}</span>
                    <span class="text-[11px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">Pending</span>
                </div>
                <p class="text-[11px] text-textSec pt-1 border-t border-gray-50">Menunggu verifikasi awal</p>
            </div>

            <!-- Sedang Diproses -->
            <div class="p-5 rounded-2xl bg-white border border-gray-100 card-hover shadow-sm space-y-1">
                <span class="text-xs font-semibold text-textSec">Sedang diproses</span>
                <div class="flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-blue-600">{{ $diprosesCount }}</span>
                    <span class="text-[11px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">Konsultasi BK</span>
                </div>
                <p class="text-[11px] text-textSec pt-1 border-t border-gray-50">Sedang dibahas bersama Wali Kelas</p>
            </div>

            <!-- Selesai -->
            <div class="p-5 rounded-2xl bg-white border border-gray-100 card-hover shadow-sm space-y-1">
                <span class="text-xs font-semibold text-textSec">Selesai ditangani</span>
                <div class="flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold text-emerald-600">{{ $selesaiCount }}</span>
                    <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">Tuntas</span>
                </div>
                <p class="text-[11px] text-textSec pt-1 border-t border-gray-50">Kasus terselesaikan & dikonfirmasi</p>
            </div>
        </div>

        <!-- Bagian Grafik & Analisis -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Statistik Status Donut Ringkasan -->
            <div class="p-5 rounded-2xl bg-white border border-gray-100 card-hover shadow-sm space-y-4 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-sm text-textMain">Status Penanganan Laporan</h3>
                    <span class="text-[11px] text-textSec">Real-time</span>
                </div>
                
                <div class="space-y-3 pt-2">
                    <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-gray-50 border border-gray-100">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-gray-500"></span> Pending / Dikirim</span>
                        <span class="font-extrabold text-gray-700">{{ $pendingCount }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-amber-50/60 border border-amber-100">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span> Dalam Proses</span>
                        <span class="font-extrabold text-amber-700">{{ $diprosesCount }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-emerald-50/60 border border-emerald-100">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span> Selesai</span>
                        <span class="font-extrabold text-emerald-700">{{ $selesaiCount }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs p-2.5 rounded-xl bg-rose-50/60 border border-rose-100">
                        <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span> Ditolak (Hoaks/Tidak Layak)</span>
                        <span class="font-extrabold text-rose-700">{{ $ditolakCount }}</span>
                    </div>
                </div>
            </div>

            <!-- Distribusi Jenis Perundungan -->
            <div class="lg:col-span-2 p-5 rounded-2xl bg-white border border-gray-100 card-hover shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-sm text-textMain">Distribusi Jenis Perundungan</h3>
                    <span class="text-[11px] text-textSec">Data kumulatif</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    @php
                        $maxJenis = max(array_values($jenisCounts)) ?: 1;
                    @endphp
                    @foreach($jenisCounts as $jenis => $count)
                        @php
                            $persen = round(($count / $maxJenis) * 100);
                            $labels = [
                                'verbal' => ['nama' => 'Verbal (Ejekan/Hinaan)', 'color' => 'bg-blue-600'],
                                'fisik' => ['nama' => 'Fisik (Kekerasan)', 'color' => 'bg-red-500'],
                                'sosial' => ['nama' => 'Sosial (Pengucilan)', 'color' => 'bg-purple-600'],
                                'cyber_bullying' => ['nama' => 'Siber (Cyberbullying)', 'color' => 'bg-amber-500'],
                            ];
                            $info = $labels[$jenis] ?? ['nama' => ucfirst($jenis), 'color' => 'bg-oceanMain'];
                        @endphp
                        <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-100 space-y-2">
                            <div class="flex justify-between items-center text-xs font-semibold">
                                <span>{{ $info['nama'] }}</span>
                                <span class="font-bold text-oceanMain">{{ $count }} laporan</span>
                            </div>
                            <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                                <div class="{{ $info['color'] }} h-full rounded-full transition-all duration-500" style="width: {{ $persen }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Bagian Laporan Terbaru & Aktivitas -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Tabel Laporan Terbaru -->
            <div class="lg:col-span-2 p-5 rounded-2xl bg-white border border-gray-100 card-hover shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-sm text-textMain">Laporan Masuk Terbaru</h3>
                        <p class="text-[11px] text-textSec">Daftar laporan teraktual yang masuk ke SafeVoice.</p>
                    </div>
                    <button @click="currentMenu = 'laporan'" class="text-xs text-oceanBlue font-bold hover:underline">Kelola semua laporan &rarr;</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-gray-100 text-textSec font-semibold">
                                <th class="py-2.5 px-3">KODE</th>
                                <th class="py-2.5 px-3">JENIS</th>
                                <th class="py-2.5 px-3">KELAS</th>
                                <th class="py-2.5 px-3">PELAPOR</th>
                                <th class="py-2.5 px-3">STATUS</th>
                                <th class="py-2.5 px-3 text-right">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 font-medium">
                            @forelse($laporans->take(6) as $laporan)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="py-3 px-3 font-bold text-oceanMain">{{ $laporan->kode_laporan }}</td>
                                    <td class="py-3 px-3 capitalize">{{ str_replace('_', ' ', $laporan->jenis_bullying) }}</td>
                                    <td class="py-3 px-3">{{ $laporan->pelapor?->kelas?->nama_kelas ?? 'Umum' }}</td>
                                    <td class="py-3 px-3">
                                        @if($laporan->is_anonim)
                                            <span class="px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 font-bold text-[10px]">Anonim</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold text-[10px]">{{ $laporan->pelapor?->user?->nama_lengkap ?? 'Siswa' }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3">
                                        @php
                                            $stBadge = match($laporan->status) {
                                                'diproses' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'ditolak' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                default => 'bg-gray-100 text-gray-700 border-gray-200'
                                            };
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-full font-bold text-[10px] border {{ $stBadge }} capitalize">
                                            {{ $laporan->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-right">
                                        <button @click="selectedLaporan = {{ json_encode($laporan) }}; showModalLaporan = true;" class="px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-oceanMain hover:text-white text-textMain text-[11px] font-bold transition-colors">
                                            Periksa
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-6 text-textSec">Belum ada laporan yang tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Aktivitas Log Terbaru -->
            <div class="p-5 rounded-2xl bg-white border border-gray-100 card-hover shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-sm text-textMain">Log Aktivitas Terakhir</h3>
                    <button @click="currentMenu = 'log'" class="text-xs text-oceanBlue font-bold hover:underline">Semua Log</button>
                </div>
                <div class="space-y-3 text-xs">
                    @forelse($statusLogs->take(5) as $log)
                        <div class="flex items-start gap-2.5 pb-3 border-b border-gray-50 last:border-b-0 last:pb-0">
                            <span class="w-2 h-2 rounded-full bg-oceanBlue mt-1.5 shrink-0"></span>
                            <div>
                                <p class="font-medium text-textMain">
                                    <span class="font-bold">{{ $log->laporan?->kode_laporan }}</span> &bull; 
                                    <span class="capitalize">{{ $log->status_baru }}</span>
                                </p>
                                <p class="text-[11px] text-textSec mt-0.5 italic">
                                    "{{ Str::limit($log->umpan_balik, 50) }}"
                                </p>
                                <p class="text-[10px] text-gray-400 mt-1">
                                    {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }} &bull; Oleh {{ $log->modifier?->nama_lengkap ?? 'Sistem' }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-textSec text-center py-4">Belum ada log aktivitas.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    {{-- ================= TAB 2: KELOLA SEMUA LAPORAN ================= --}}
    <div x-show="currentMenu === 'laporan'" class="space-y-6" x-cloak>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div>
                <h2 class="text-lg font-bold text-textMain flex items-center gap-2">
                    <i class="fa-solid fa-folder-open text-oceanBlue"></i> Manajemen Seluruh Laporan
                </h2>
                <p class="text-xs text-textSec mt-0.5">Kelola verifikasi, ubah status, dan tugaskan Guru BK untuk setiap laporan perundungan.</p>
            </div>
            
            <div class="flex items-center gap-2">
                <select x-model="filterStatusLaporan" class="px-3.5 py-2 rounded-xl border border-gray-200 bg-gray-50 text-xs font-semibold text-textMain focus:outline-none focus:border-oceanBlue">
                    <option value="semua">Semua Status ({{ $totalLaporan }})</option>
                    <option value="pending">Pending ({{ $pendingCount }})</option>
                    <option value="diproses">Dalam Proses ({{ $diprosesCount }})</option>
                    <option value="selesai">Selesai ({{ $selesaiCount }})</option>
                    <option value="ditolak">Ditolak ({{ $ditolakCount }})</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/70 border-b border-gray-100 text-textSec font-semibold">
                        <tr>
                            <th class="py-3 px-4">KODE</th>
                            <th class="py-3 px-4">PELAPOR</th>
                            <th class="py-3 px-4">TERLAPOR</th>
                            <th class="py-3 px-4">JENIS</th>
                            <th class="py-3 px-4">LOKASI & TANGGAL</th>
                            <th class="py-3 px-4">GURU BK PENANGGUNG JAWAB</th>
                            <th class="py-3 px-4">STATUS</th>
                            <th class="py-3 px-4 text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($laporans as $laporan)
                            <tr x-show="filterStatusLaporan === 'semua' || filterStatusLaporan === '{{ $laporan->status }}'" class="hover:bg-gray-50/60 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-oceanMain">{{ $laporan->kode_laporan }}</td>
                                <td class="py-3.5 px-4">
                                    @if($laporan->is_anonim)
                                        <span class="px-2 py-0.5 rounded-full bg-purple-50 text-purple-700 font-bold text-[10px]">Anonim</span>
                                    @else
                                        <span class="font-bold text-textMain">{{ $laporan->pelapor?->user?->nama_lengkap ?? 'Siswa' }}</span>
                                        <span class="text-[10px] text-textSec block">{{ $laporan->pelapor?->kelas?->nama_kelas ?? 'Kelas -' }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-rose-700 font-bold">{{ $laporan->terlapor }}</td>
                                <td class="py-3.5 px-4 capitalize">{{ str_replace('_', ' ', $laporan->jenis_bullying) }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="text-textMain">{{ $laporan->lokasi_kejadian }}</span>
                                    <span class="text-[10px] text-textSec block">{{ \Carbon\Carbon::parse($laporan->tanggal_kejadian)->format('d M Y') }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    {{ $laporan->guruBk?->user?->nama_lengkap ?? 'Belum Ditugaskan' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @php
                                        $stClass = match($laporan->status) {
                                            'diproses' => 'bg-amber-50 text-amber-800 border-amber-200',
                                            'selesai' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'ditolak' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            default => 'bg-gray-100 text-gray-700 border-gray-200'
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] border {{ $stClass }} capitalize">
                                        {{ $laporan->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <button @click="selectedLaporan = {{ json_encode($laporan) }}; showModalLaporan = true;" class="px-3 py-1.5 rounded-xl bg-oceanBlue hover:bg-oceanMain text-white font-bold text-xs shadow-sm transition-colors">
                                        Kelola & Status
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8 text-textSec">Belum ada laporan di database.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ================= TAB 3: KELOLA MASTER PENGGUNA ================= --}}
    <div x-show="currentMenu === 'pengguna'" class="space-y-6" x-cloak>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div>
                <h2 class="text-lg font-bold text-textMain flex items-center gap-2">
                    <i class="fa-solid fa-users-gear text-oceanBlue"></i> Manajemen Pengguna & Hak Akses
                </h2>
                <p class="text-xs text-textSec mt-0.5">Kelola akun Siswa, Guru BK, Wali Kelas, dan Administrator SafeVoice.</p>
            </div>
            
            <button @click="showModalUser = true" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-user-plus"></i> Tambah Pengguna Baru
            </button>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/70 border-b border-gray-100 text-textSec font-semibold">
                        <tr>
                            <th class="py-3 px-4">NAMA LENGKAP</th>
                            <th class="py-3 px-4">EMAIL</th>
                            <th class="py-3 px-4">PERAN (ROLE)</th>
                            <th class="py-3 px-4">IDENTITAS (NIS/NIP)</th>
                            <th class="py-3 px-4">KELAS / PENUGASAN</th>
                            <th class="py-3 px-4">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @foreach($users as $u)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-textMain">{{ $u->nama_lengkap }}</td>
                                <td class="py-3.5 px-4 text-textSec">{{ $u->email }}</td>
                                <td class="py-3.5 px-4">
                                    @php
                                        $roleBadge = match($u->role) {
                                            'admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                            'guru_bk' => 'bg-blue-50 text-blue-700 border-blue-200',
                                            'wali_kelas' => 'bg-amber-50 text-amber-800 border-amber-200',
                                            default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] border {{ $roleBadge }} uppercase">
                                        {{ str_replace('_', ' ', $u->role) }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-textSec font-mono">
                                    {{ $u->siswa?->nis ?? $u->guruBk?->nip ?? $u->waliKelas?->nip ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-textSec">
                                    {{ $u->siswa?->kelas?->nama_kelas ?? ($u->waliKelas ? 'Wali Kelas' : '-') }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $u->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $u->is_active ? 'Aktif' : 'Non-aktif' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ================= TAB 4: KELOLA KELAS ================= --}}
    <div x-show="currentMenu === 'kelas'" class="space-y-6" x-cloak>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div>
                <h2 class="text-lg font-bold text-textMain flex items-center gap-2">
                    <i class="fa-solid fa-school text-oceanBlue"></i> Manajemen Kelas & Wali Kelas
                </h2>
                <p class="text-xs text-textSec mt-0.5">Kelola data kelas dan penugasan Wali Kelas terkait alur konsultasi.</p>
            </div>
            
            <button @click="showModalKelas = true" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Tambah Kelas Baru
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @forelse($kelasList as $kelas)
                <div class="p-5 rounded-2xl bg-white border border-gray-100 shadow-sm space-y-3 card-hover">
                    <div class="flex items-center justify-between">
                        <span class="text-base font-extrabold text-oceanMain">{{ $kelas->nama_kelas }}</span>
                        <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[10px] font-bold">{{ $kelas->tahun_ajaran }}</span>
                    </div>
                    <div class="text-xs space-y-1 text-textSec pt-2 border-t border-gray-100">
                        <p><span class="font-semibold text-textMain">Wali Kelas:</span> {{ $kelas->waliKelas?->user?->nama_lengkap ?? 'Belum Ditugaskan' }}</p>
                        <p><span class="font-semibold text-textMain">Jumlah Siswa:</span> {{ $kelas->siswa_count }} Siswa</p>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8 text-textSec bg-white rounded-2xl border border-gray-100">
                    Belum ada kelas terdaftar.
                </div>
            @endforelse
        </div>
    </div>

    {{-- ================= TAB 5: LOG AUDIT ================= --}}
    <div x-show="currentMenu === 'log'" class="space-y-6" x-cloak>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <h2 class="text-lg font-bold text-textMain flex items-center gap-2">
                <i class="fa-solid fa-timeline text-oceanBlue"></i> Jejak Audit Status Laporan
            </h2>
            <p class="text-xs text-textSec mt-0.5">Seluruh riwayat perubahan status laporan dan catatan tindak lanjut tercatat di sini.</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <ol class="space-y-4 relative before:absolute before:inset-0 before:left-3 before:w-0.5 before:bg-gray-200 pl-2">
                @foreach($statusLogs as $log)
                    <li class="relative pl-6">
                        <div class="absolute left-1.5 top-1.5 w-3 h-3 rounded-full bg-oceanBlue border-2 border-white shadow-sm"></div>
                        <div class="p-3.5 rounded-xl bg-gray-50 border border-gray-100 space-y-1 text-xs">
                            <div class="flex items-center justify-between font-bold text-textMain">
                                <span>{{ $log->laporan?->kode_laporan }} &bull; Dari '{{ $log->status_lama }}' ke '{{ $log->status_baru }}'</span>
                                <span class="text-[11px] text-textSec font-normal">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, H:i') }} WIB</span>
                            </div>
                            <p class="text-textSec italic">"{{ $log->umpan_balik }}"</p>
                            <p class="text-[10px] text-textSec font-semibold">Diubah oleh: {{ $log->modifier?->nama_lengkap ?? 'Sistem' }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>

    {{-- ================= TAB GURU BK 1: VERIFIKASI & DISPOSISI LAPORAN (FLOWCHART 1 & 4) ================= --}}
    <div x-show="currentMenu === 'bk_verifikasi'" class="space-y-6" x-cloak>
        <div class="bg-white p-5 rounded-2xl border border-amber-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 text-xs font-bold mb-2">
                    <i class="fa-solid fa-code-branch text-amber-600"></i> Flowchart Tahap 1: Skrining Guru BK
                </div>
                <h2 class="text-lg font-bold text-textMain flex items-center gap-2">
                    <i class="fa-solid fa-inbox text-amber-500"></i> Verifikasi & Disposisi Laporan Masuk
                </h2>
                <p class="text-xs text-textSec mt-0.5">Guru BK meninjau bukti kronologi: putuskan apakah layak diproses dan diteruskan ke Wali Kelas, atau tolak langsung.</p>
            </div>
            <span class="px-4 py-2 rounded-xl bg-amber-50 text-amber-900 border border-amber-200 font-extrabold text-xs shrink-0">
                {{ $laporanPending->count() }} Laporan Menunggu Verifikasi
            </span>
        </div>

        <div class="space-y-5">
            @forelse($laporanPending as $lp)
                <div class="p-6 rounded-3xl bg-white border border-gray-100 shadow-sm space-y-4 card-hover">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-xl bg-oceanMain text-white font-extrabold text-xs tracking-wider">
                                {{ $lp->kode_laporan }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                Pending Verifikasi
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-tealDark border border-teal-200 capitalize">
                                {{ str_replace('_', ' ', $lp->jenis_bullying) }}
                            </span>
                            @if($lp->is_anonim)
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">Anonim</span>
                            @endif
                        </div>
                        <span class="text-xs text-textSec font-medium">
                            <i class="fa-regular fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($lp->created_at)->format('d M Y, H:i') }} WIB
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs bg-gray-50/70 p-4 rounded-2xl border border-gray-100">
                        <div>
                            <span class="text-textSec font-medium">Pelapor / Korban:</span>
                            <p class="font-bold text-textMain mt-0.5">
                                {{ $lp->is_anonim ? 'Rahasia (Anonim)' : ($lp->pelapor?->user?->nama_lengkap ?? 'Siswa') }}
                            </p>
                            <p class="text-[11px] text-textSec">Kelas: {{ $lp->pelapor?->kelas?->nama_kelas ?? 'Umum' }}</p>
                        </div>
                        <div>
                            <span class="text-textSec font-medium">Terlapor (Diduga Pelaku):</span>
                            <p class="font-bold text-rose-700 mt-0.5 text-sm">{{ $lp->terlapor }}</p>
                        </div>
                        <div>
                            <span class="text-textSec font-medium">Lokasi & Waktu Kejadian:</span>
                            <p class="font-bold text-textMain mt-0.5">{{ $lp->lokasi_kejadian }}</p>
                            <p class="text-[11px] text-textSec">{{ \Carbon\Carbon::parse($lp->tanggal_kejadian)->format('d F Y') }}</p>
                        </div>
                    </div>

                    <div class="space-y-1 text-xs">
                        <span class="font-bold text-textMain">Kronologi Kejadian:</span>
                        <div class="p-3.5 rounded-2xl bg-white border border-gray-200/80 text-textSec leading-relaxed">
                            "{{ $lp->deskripsi }}"
                        </div>
                    </div>

                    @if($lp->bukti->count() > 0)
                        <div class="space-y-2 text-xs">
                            <span class="font-bold text-textMain flex items-center gap-1.5">
                                <i class="fa-solid fa-paperclip text-oceanBlue"></i> Berkas Bukti Terlampir ({{ $lp->bukti->count() }}):
                            </span>
                            <div class="flex flex-wrap gap-2">
                                @foreach($lp->bukti as $b)
                                    <a href="{{ asset('storage/' . $b->path_file) }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-gray-100 hover:bg-oceanBlue hover:text-white transition-colors text-[11px] font-semibold flex items-center gap-2 border border-gray-200">
                                        <i class="fa-solid fa-file-image"></i> Buka Lampiran ({{ $b->ukuran_kb }} KB)
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Form Keputusan Skrining BK --}}
                    <div class="pt-3 border-t border-gray-100" x-data="{ openTolak: false }">
                        <form action="{{ route('admin.bk.verifikasi', $lp->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="wali_kelas_id" value="{{ $lp->pelapor?->kelas?->wali_kelas_id ?? '' }}">

                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <label class="text-xs font-bold text-textMain">Tugaskan Guru BK Penangan:</label>
                                    <select name="guru_bk_id" class="p-2 rounded-xl border border-gray-200 text-xs font-semibold bg-gray-50">
                                        @foreach($guruBkList as $bk)
                                            <option value="{{ $bk->id }}">{{ $bk->user?->nama_lengkap }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex items-center gap-2.5">
                                    <button type="button" @click="openTolak = !openTolak" class="px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold transition-colors flex items-center gap-1.5">
                                        <i class="fa-solid fa-ban"></i> Tolak Langsung
                                    </button>

                                    <button type="submit" name="keputusan" value="teruskan" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition-all flex items-center gap-2">
                                        <i class="fa-solid fa-share-from-square"></i> Layak & Teruskan ke Wali Kelas
                                    </button>
                                </div>
                            </div>

                            <div x-show="openTolak" x-cloak class="p-4 rounded-2xl bg-rose-50/70 border border-rose-200 space-y-2 text-xs">
                                <label class="font-bold text-rose-900 block">Alasan Penolakan (Wajib dikirim ke Siswa Pelapor):</label>
                                <textarea name="alasan_tolak" rows="2" placeholder="Jelaskan alasan mengapa laporan ini tidak dapat diproses lebih lanjut..." class="w-full p-2.5 rounded-xl border border-rose-300 text-xs bg-white"></textarea>
                                <div class="flex justify-end">
                                    <button type="submit" name="keputusan" value="tolak" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-sm">
                                        Konfirmasi Penolakan Laporan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-10 rounded-3xl bg-white border border-gray-100 text-center space-y-3 shadow-sm">
                    <div class="w-14 h-14 mx-auto rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <h3 class="text-sm font-bold text-textMain">Seluruh Laporan Masuk Telah Selesai Diverifikasi</h3>
                    <p class="text-xs text-textSec max-w-sm mx-auto">Tidak ada antrian laporan baru. Setiap laporan baru yang dikirimkan siswa akan langsung muncul di sini.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ================= TAB GURU BK 2: RUANG KONSULTASI BK & WALI KELAS (FLOWCHART 2) ================= --}}
    <div x-show="currentMenu === 'bk_konsultasi'" class="space-y-6" x-cloak>
        <div class="bg-white p-5 rounded-2xl border border-blue-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-800 border border-blue-200 text-xs font-bold mb-2">
                    <i class="fa-solid fa-code-branch text-blue-600"></i> Flowchart Tahap 2: Kolaborasi BK & Wali Kelas
                </div>
                <h2 class="text-lg font-bold text-textMain flex items-center gap-2">
                    <i class="fa-solid fa-comments text-blue-600"></i> Ruang Diskusi Investigasi Bersama Wali Kelas
                </h2>
                <p class="text-xs text-textSec mt-0.5">Bahas bukti dan kronologi secara mendalam, simpan notulensi resmi ke database, dan tentukan apakah kasus adalah Fakta atau Hoaks.</p>
            </div>
            <span class="px-4 py-2 rounded-xl bg-blue-50 text-blue-900 border border-blue-200 font-extrabold text-xs shrink-0">
                {{ $laporanKonsultasi->count() }} Kasus Sedang Dibahas
            </span>
        </div>

        <div class="space-y-6">
            @forelse($laporanKonsultasi as $lk)
                <div class="p-6 rounded-3xl bg-white border border-gray-100 shadow-sm space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
                        <div class="flex items-center gap-3">
                            <span class="font-extrabold text-sm text-oceanMain">{{ $lk->kode_laporan }}</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">Dalam Diskusi</span>
                            <span class="text-xs text-textSec font-semibold">Wali Kelas: {{ $lk->pelapor?->kelas?->waliKelas?->user?->nama_lengkap ?? 'Bapak/Ibu Wali' }}</span>
                        </div>
                        <span class="text-xs text-rose-700 font-bold">Terlapor: {{ $lk->terlapor }}</span>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100 text-xs text-textSec">
                        <span class="font-bold text-textMain block mb-1">Isi Laporan:</span>
                        "{{ $lk->deskripsi }}"
                    </div>

                    {{-- Riwayat Notulensi Konsultasi Sebelumnya --}}
                    @if($lk->konsultasi->count() > 0)
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-textMain flex items-center gap-2">
                                <i class="fa-solid fa-clock-rotate-left text-blue-600"></i> Riwayat Diskusi Sebelumnya ({{ $lk->konsultasi->count() }}):
                            </span>
                            <div class="space-y-2">
                                @foreach($lk->konsultasi as $kItem)
                                    <div class="p-3.5 rounded-xl bg-blue-50/50 border border-blue-100 text-xs space-y-1">
                                        <div class="flex justify-between font-bold text-blue-950">
                                            <span>Rekomendasi: {{ ucfirst(str_replace('_', ' ', $kItem->kesimpulan)) }}</span>
                                            <span class="font-normal text-textSec">{{ \Carbon\Carbon::parse($kItem->tanggal_diskusi)->format('d M Y, H:i') }} WIB</span>
                                        </div>
                                        <p class="text-textSec italic">"{{ $kItem->hasil_diskusi }}"</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Form Tambah Notulensi & Penetapan Fakta/Hoaks (Flowchart 2) --}}
                    <form action="{{ route('admin.bk.konsultasi', $lk->id) }}" method="POST" class="p-4 rounded-2xl bg-gray-50 border border-gray-200/80 space-y-3.5 text-xs">
                        @csrf
                        <span class="font-bold text-textMain text-sm block">Catat Hasil Pembahasan BK & Wali Kelas:</span>

                        <div class="space-y-1">
                            <label class="font-semibold text-textMain">Notulensi Diskusi Investigasi:</label>
                            <textarea name="hasil_diskusi" required rows="2" placeholder="Tuliskan temuan klarifikasi saksi, kronologi sebenarnya, atau hasil pemanggilan awal..." class="w-full p-2.5 rounded-xl border border-gray-200 bg-white"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="space-y-1">
                                <label class="font-semibold text-textMain">1. Apakah Informasi Cukup?</label>
                                <select name="informasi_cukup" required class="w-full p-2 rounded-xl border border-gray-200 bg-white font-bold">
                                    <option value="ya">Ya (Informasi Lengkap)</option>
                                    <option value="tidak">Tidak (Perlu Diskusi Lanjutan)</option>
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label class="font-semibold text-textMain">2. Validitas Kasus:</label>
                                <select name="status_fakta" class="w-full p-2 rounded-xl border border-gray-200 bg-white font-bold">
                                    <option value="fakta">Laporan FAKTA (Terbukti)</option>
                                    <option value="hoaks">Laporan HOAKS (Tidak Benar)</option>
                                </select>
                            </div>

                            <div class="space-y-1">
                                <label class="font-semibold text-textMain">3. Rekomendasi Tindakan:</label>
                                <select name="kesimpulan" required class="w-full p-2 rounded-xl border border-gray-200 bg-white font-semibold">
                                    <option value="tindak_lanjut_bk">Tindak Lanjut BK</option>
                                    <option value="mediasi">Mediasi Kedua Pihak</option>
                                    <option value="panggilan_orang_tua">Panggilan Orang Tua</option>
                                    <option value="selesai">Selesai Damai</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition-all flex items-center gap-2">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan Hasil Diskusi & Putuskan Alur
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="p-10 rounded-3xl bg-white border border-gray-100 text-center space-y-3 shadow-sm">
                    <div class="w-14 h-14 mx-auto rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <h3 class="text-sm font-bold text-textMain">Belum Ada Kasus Dalam Pembahasan Konsultasi</h3>
                    <p class="text-xs text-textSec max-w-sm mx-auto">Laporan yang telah diverifikasi layak di Menu 1 akan secara otomatis masuk ke ruang diskusi ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ================= TAB GURU BK 3: TINDAK LANJUT & MONITORING KONFIRMASI (FLOWCHART 3) ================= --}}
    <div x-show="currentMenu === 'bk_tindak_lanjut'" class="space-y-6" x-cloak>
        <div class="bg-white p-5 rounded-2xl border border-emerald-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold mb-2">
                    <i class="fa-solid fa-code-branch text-emerald-600"></i> Flowchart Tahap 3: Resolusi & Konfirmasi
                </div>
                <h2 class="text-lg font-bold text-textMain flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-check text-emerald-600"></i> Tindak Lanjut & Monitoring Respon Siswa
                </h2>
                <p class="text-xs text-textSec mt-0.5">Pantau konfirmasi rasa aman dari siswa secara real-time. Jika siswa menekan 'Masih terjadi', kembalikan ke proses investigasi lanjutan.</p>
            </div>
            <span class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-900 border border-emerald-200 font-extrabold text-xs shrink-0">
                {{ $laporanTindakLanjut->count() }} Kasus Terpantau
            </span>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/70 border-b border-gray-100 text-textSec font-semibold">
                        <tr>
                            <th class="py-3 px-4">KODE</th>
                            <th class="py-3 px-4">TERLAPOR</th>
                            <th class="py-3 px-4">STATUS KASUS</th>
                            <th class="py-3 px-4">RESPON KONFIRMASI SISWA (LIVE)</th>
                            <th class="py-3 px-4 text-right">AKSI TINDAK LANJUT</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($laporanTindakLanjut as $lt)
                            <tr class="hover:bg-gray-50/60 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-oceanMain">{{ $lt->kode_laporan }}</td>
                                <td class="py-3.5 px-4 text-rose-700 font-bold">{{ $lt->terlapor }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] capitalize border {{ $lt->status === 'selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-blue-50 text-blue-700 border-blue-200' }}">
                                        {{ $lt->status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($lt->konfirmasi_siswa === 'setuju')
                                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold text-xs inline-flex items-center gap-1.5 shadow-sm">
                                            <i class="fa-solid fa-circle-check text-emerald-600"></i> Sudah Aman (Kasus Tuntas)
                                        </span>
                                    @elseif($lt->konfirmasi_siswa === 'tidak_sesuai')
                                        <span class="px-3 py-1 rounded-full bg-rose-100 text-rose-800 font-bold text-xs inline-flex items-center gap-1.5 shadow-sm animate-pulse">
                                            <i class="fa-solid fa-triangle-exclamation text-rose-600"></i> Siswa: Masih Terjadi!
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200 font-bold text-[11px] inline-flex items-center gap-1.5">
                                            <i class="fa-regular fa-hourglass-half text-amber-600"></i> Menunggu Respon Siswa
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right space-x-1.5">
                                    {{-- Jika siswa menekan tidak_sesuai (Masih terjadi), ada tombol kembalikan ke proses sesuai Flowchart 3 --}}
                                    @if($lt->konfirmasi_siswa === 'tidak_sesuai')
                                        <form action="{{ route('admin.bk.kembalikan', $lt->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-sm transition-all inline-flex items-center gap-1.5">
                                                <i class="fa-solid fa-rotate-left"></i> Bahas Ulang Bersama Tim
                                            </button>
                                        </form>
                                    @elseif($lt->status !== 'selesai')
                                        <button @click="selectedLaporan = {{ json_encode($lt) }}; showModalLaporan = true;" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition-all inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-check-double"></i> Selesaikan Kasus
                                        </button>
                                    @else
                                        <span class="text-xs text-textSec font-semibold italic">Telah Diselesaikan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-textSec">Belum ada kasus pada tahap resolusi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ================= MODAL KELOLA STATUS LAPORAN ================= --}}
    <div x-show="showModalLaporan" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div @click.away="showModalLaporan = false" class="w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl space-y-5">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="font-bold text-base text-oceanMain flex items-center gap-2">
                    <i class="fa-solid fa-file-pen text-oceanBlue"></i> Kelola Status Laporan
                </h3>
                <button @click="showModalLaporan = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <template x-if="selectedLaporan">
                <form :action="'/admin/laporan/' + selectedLaporan.id" method="POST" class="space-y-4 text-xs">
                    @csrf
                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-100 space-y-1">
                        <p class="font-bold text-textMain text-sm" x-text="selectedLaporan.kode_laporan"></p>
                        <p class="text-textSec"><span class="font-semibold">Terlapor:</span> <span x-text="selectedLaporan.terlapor"></span></p>
                        <p class="text-textSec"><span class="font-semibold">Deskripsi:</span> <span x-text="selectedLaporan.deskripsi"></span></p>
                    </div>

                    <div class="space-y-1">
                        <label class="font-bold text-textMain">Perbarui Status Penanganan:</label>
                        <select name="status" x-model="selectedLaporan.status" class="w-full p-2.5 rounded-xl border border-gray-200 bg-white font-semibold">
                            <option value="pending">Pending (Menunggu Verifikasi)</option>
                            <option value="diproses">Dalam Proses (Diteruskan ke Wali Kelas)</option>
                            <option value="selesai">Selesai (Kasus Telah Tuntas)</option>
                            <option value="ditolak">Ditolak (Hoaks / Tidak Layak)</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="font-bold text-textMain">Tugaskan Guru BK:</label>
                        <select name="guru_bk_id" x-model="selectedLaporan.guru_bk_id" class="w-full p-2.5 rounded-xl border border-gray-200 bg-white font-medium">
                            <option value="">-- Pilih Guru BK --</option>
                            @foreach($guruBkList as $bk)
                                <option value="{{ $bk->id }}">{{ $bk->user?->nama_lengkap }} ({{ $bk->nip }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="font-bold text-textMain">Catatan / Umpan Balik Resmi:</label>
                        <textarea name="catatan" rows="3" placeholder="Tuliskan catatan disposisi atau alasan perubahan status..." class="w-full p-2.5 rounded-xl border border-gray-200 bg-white text-xs"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                        <button type="button" @click="showModalLaporan = false" class="px-4 py-2 rounded-xl bg-gray-100 text-textSec font-bold">Batal</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-oceanBlue hover:bg-oceanMain text-white font-bold shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </template>
        </div>
    </div>

    {{-- ================= MODAL TAMBAH USER ================= --}}
    <div x-show="showModalUser" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div @click.away="showModalUser = false" class="w-full max-w-lg bg-white rounded-3xl p-6 shadow-2xl space-y-5">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="font-bold text-base text-oceanMain flex items-center gap-2">
                    <i class="fa-solid fa-user-plus text-emerald-600"></i> Tambah Pengguna Baru
                </h3>
                <button @click="showModalUser = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-3.5 text-xs" x-data="{ role: 'siswa' }">
                @csrf
                <div class="space-y-1">
                    <label class="font-bold text-textMain">Nama Lengkap:</label>
                    <input type="text" name="nama_lengkap" required placeholder="Contoh: Muhammad Rizki" class="w-full p-2.5 rounded-xl border border-gray-200">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="font-bold text-textMain">Email:</label>
                        <input type="email" name="email" required placeholder="email@sekolah.id" class="w-full p-2.5 rounded-xl border border-gray-200">
                    </div>
                    <div class="space-y-1">
                        <label class="font-bold text-textMain">Password:</label>
                        <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full p-2.5 rounded-xl border border-gray-200">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="font-bold text-textMain">Peran (Role):</label>
                    <select name="role" x-model="role" required class="w-full p-2.5 rounded-xl border border-gray-200 font-semibold">
                        <option value="siswa">Siswa</option>
                        <option value="guru_bk">Guru BK</option>
                        <option value="wali_kelas">Wali Kelas</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                <template x-if="role === 'siswa'">
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <div class="space-y-1">
                            <label class="font-bold text-textMain">NIS:</label>
                            <input type="text" name="nis" placeholder="Nomor Induk Siswa" class="w-full p-2.5 rounded-xl border border-gray-200">
                        </div>
                        <div class="space-y-1">
                            <label class="font-bold text-textMain">Kelas:</label>
                            <select name="kelas_id" class="w-full p-2.5 rounded-xl border border-gray-200">
                                @foreach($kelasList as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </template>

                <template x-if="role === 'guru_bk' || role === 'wali_kelas'">
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <div class="space-y-1">
                            <label class="font-bold text-textMain">NIP:</label>
                            <input type="text" name="nip" placeholder="Nomor Induk Pegawai" class="w-full p-2.5 rounded-xl border border-gray-200">
                        </div>
                        <div class="space-y-1">
                            <label class="font-bold text-textMain">No. Handphone/WA:</label>
                            <input type="text" name="no_hp" placeholder="08xxxxxxxxxx" class="w-full p-2.5 rounded-xl border border-gray-200">
                        </div>
                    </div>
                </template>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" @click="showModalUser = false" class="px-4 py-2 rounded-xl bg-gray-100 text-textSec font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold shadow-sm">Daftarkan Pengguna</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= MODAL TAMBAH KELAS ================= --}}
    <div x-show="showModalKelas" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div @click.away="showModalKelas = false" class="w-full max-w-md bg-white rounded-3xl p-6 shadow-2xl space-y-5">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="font-bold text-base text-oceanMain flex items-center gap-2">
                    <i class="fa-solid fa-school text-blue-600"></i> Tambah Kelas Baru
                </h3>
                <button @click="showModalKelas = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
            </div>

            <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf
                <div class="space-y-1">
                    <label class="font-bold text-textMain">Nama Kelas:</label>
                    <input type="text" name="nama_kelas" required placeholder="Contoh: X-RPL-1, XI-TKJ-2" class="w-full p-2.5 rounded-xl border border-gray-200">
                </div>

                <div class="space-y-1">
                    <label class="font-bold text-textMain">Tahun Ajaran:</label>
                    <input type="text" name="tahun_ajaran" required value="2026/2027" placeholder="2026/2027" class="w-full p-2.5 rounded-xl border border-gray-200">
                </div>

                <div class="space-y-1">
                    <label class="font-bold text-textMain">Wali Kelas Pembina:</label>
                    <select name="wali_kelas_id" class="w-full p-2.5 rounded-xl border border-gray-200 font-medium">
                        <option value="">-- Pilih Wali Kelas (Opsional) --</option>
                        @foreach($waliKelasList as $wk)
                            <option value="{{ $wk->id }}">{{ $wk->user?->nama_lengkap }} ({{ $wk->nip }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" @click="showModalKelas = false" class="px-4 py-2 rounded-xl bg-gray-100 text-textSec font-bold">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-sm">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>

</main>
@endsection
