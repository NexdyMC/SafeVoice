@extends('layouts.user')

@section('title', 'Log Aktivitas Laporan - Ruang Aman')

@section('content')
<div>

    <!-- Konten Utama: Log Aktivitas -->
    <main class="flex-1 p-4 space-y-6">

        @if(session('success'))
            <div class="p-3.5 rounded-2xl bg-teal-50 border border-teal-200 text-tealDark text-xs font-semibold flex items-center gap-2.5 shadow-sm">
                <i class="fa-solid fa-circle-check text-tealMain text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Header Halaman & Filter -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
            <div>
                <h2 class="text-lg font-bold text-textMain flex items-center gap-2">
                    <i class="fa-solid fa-timeline text-tealMain"></i> Log Aktivitas Laporan
                </h2>
                <p class="text-xs text-textSec mt-0.5">Riwayat kronologis dan perkembangan penanganan laporanmu.</p>
            </div>
            
            <!-- Filter Status -->
            <div class="relative">
                <select onchange="window.location.href = '?status=' + this.value" aria-label="Filter laporan" class="w-full sm:w-auto px-3.5 py-2 rounded-xl border border-gray-200 bg-gray-50 text-xs font-semibold text-textMain focus:outline-none focus:border-tealMain cursor-pointer">
                    <option value="semua" {{ ($selectedFilter ?? 'semua') === 'semua' ? 'selected' : '' }}>Semua Laporan ▾</option>
                    <option value="pending" {{ ($selectedFilter ?? '') === 'pending' ? 'selected' : '' }}>Dikirim (Pending)</option>
                    <option value="diproses" {{ ($selectedFilter ?? '') === 'diproses' ? 'selected' : '' }}>Dalam Proses</option>
                    <option value="selesai" {{ ($selectedFilter ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ ($selectedFilter ?? '') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
        </div>

        <!-- Timeline Kronologis -->
        <ol class="space-y-6 relative before:absolute before:inset-0 before:left-3.5 before:w-0.5 before:bg-tealMain/30 pl-2">

            @forelse($groupedLogs as $groupHeader => $logs)
                @php
                    $parts = explode('|', $groupHeader);
                    $badgeLabel = $parts[0] ?? 'Laporan';
                    $dateLabel = $parts[1] ?? '';
                    $isToday = $badgeLabel === 'Hari Ini';
                @endphp

                <li class="space-y-4">
                    <div class="flex items-center gap-2 relative z-10 pt-1">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $isToday ? 'bg-teal-gradient text-white shadow-sm' : 'bg-gray-200 text-textMain' }}">
                            {{ $badgeLabel }}
                        </span>
                        @if($dateLabel)
                            <span class="text-[11px] text-textSec font-medium">{{ $dateLabel }}</span>
                        @endif
                    </div>

                    @foreach($logs as $log)
                        @php
                            $status = $log->status_baru;
                            $dotBg = match($status) {
                                'diproses' => 'bg-amber-600',
                                'selesai' => 'bg-emerald-600',
                                'ditolak' => 'bg-rose-600',
                                default => 'bg-gray-500'
                            };
                            $statusBadge = match($status) {
                                'diproses' => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200', 'label' => 'Dalam Proses', 'title' => 'Status berubah ke Dalam Proses'],
                                'selesai' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'label' => 'Selesai', 'title' => 'Kasus telah diselesaikan'],
                                'ditolak' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'label' => 'Ditolak', 'title' => 'Laporan tidak dapat ditindaklanjuti'],
                                default => ['bg' => 'bg-gray-100 text-gray-700 border-gray-200', 'label' => 'Dikirim', 'title' => 'Laporan baru berhasil dikirim'],
                            };
                            $pengubah = ($log->modifier?->isGuruBk() || $log->modifier?->isAdmin())
                                ? 'Guru BK'
                                : ($log->laporan->is_anonim ? 'Rahasia' : ($log->modifier?->nama_lengkap ?? 'Pelapor'));
                        @endphp

                        <div class="relative pl-8 group">
                            <div class="absolute left-1.5 top-1.5 w-4 h-4 rounded-full {{ $dotBg }} border-2 border-white shadow-sm flex items-center justify-center"></div>

                            <div class="p-4 rounded-2xl bg-white border border-gray-100 card-hover shadow-sm space-y-2.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-textMain">{{ $log->laporan->kode_laporan }} &bull; {{ $pengubah }}</span>
                                    <span class="text-[11px] text-textSec">{{ \Carbon\Carbon::parse($log->created_at)->format('H.i') }} WIB</span>
                                </div>

                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $statusBadge['bg'] }}">{{ $statusBadge['label'] }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-tealDark border border-teal-200 capitalize">
                                        {{ str_replace('_', ' ', $log->laporan->jenis_bullying) }}
                                    </span>
                                    @if($log->laporan->is_anonim)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">Anonim</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">Terbuka</span>
                                    @endif
                                </div>

                                <p class="text-xs font-semibold text-textMain">{{ $statusBadge['title'] }}</p>

                                @if($log->umpan_balik)
                                    <p class="text-xs text-textSec italic bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                        "{{ $log->umpan_balik }}"
                                    </p>
                                @elseif($status === 'pending')
                                    <p class="text-xs text-textSec bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                                        "{{ $log->laporan->deskripsi }}"
                                    </p>
                                @endif

                                {{-- Interaksi Konfirmasi Kondisi Siswa jika Selesai --}}
                                @if($status === 'selesai')
                                    @if($log->laporan->konfirmasi_siswa === 'menunggu')
                                        <div class="p-3 rounded-xl bg-teal-50 border border-teal-100 space-y-2">
                                            <p class="text-[11px] font-bold text-tealDark">Konfirmasi kondisimu saat ini:</p>
                                            <form method="POST" action="{{ route('user.laporan.konfirmasi', $log->laporan->id) }}" class="flex items-center gap-2">
                                                @csrf
                                                <button type="submit" name="konfirmasi" value="setuju" class="flex-1 py-2 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] shadow-sm transition-colors flex items-center justify-center gap-1">
                                                    <i class="fa-solid fa-face-smile"></i> Sudah aman
                                                </button>
                                                <button type="submit" name="konfirmasi" value="tidak_sesuai" class="flex-1 py-2 px-3 rounded-lg bg-white hover:bg-amber-50 text-amber-800 border border-amber-300 font-bold text-[11px] shadow-sm transition-colors flex items-center justify-center gap-1">
                                                    <i class="fa-solid fa-triangle-exclamation"></i> Masih terjadi
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($log->laporan->konfirmasi_siswa === 'setuju')
                                        <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-medium text-center">
                                            <i class="fa-solid fa-circle-check text-emerald-600 mr-1"></i> Kamu telah mengonfirmasi bahwa kondisi sudah aman. Terima kasih!
                                        </div>
                                    @elseif($log->laporan->konfirmasi_siswa === 'tidak_sesuai')
                                        <div class="p-2.5 rounded-xl bg-amber-50 text-amber-800 border border-amber-200 text-[11px] font-medium text-center">
                                            <i class="fa-solid fa-triangle-exclamation text-amber-600 mr-1"></i> Kamu mengonfirmasi perundungan masih terjadi. Tim Guru BK akan meninjau ulang segera.
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </li>
            @empty
                <!-- Kondisi Belum Ada Laporan di Perangkat Ini (Empty State) -->
                <li class="p-8 bg-white rounded-2xl border border-gray-100 text-center space-y-4 shadow-sm">
                    <div class="w-16 h-16 mx-auto rounded-full bg-teal-50 text-tealMain flex items-center justify-center text-2xl">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-sm font-bold text-textMain">Riwayat Laporan Khusus Perangkat Ini</h3>
                        <p class="text-xs text-textSec max-w-sm mx-auto leading-relaxed">
                            SafeVoice menjamin kerahasiaan: riwayat laporan ini hanya dapat dipantau dari HP atau akunmu saja. Belum ada riwayat aktivitas laporan dari perangkat ini.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('user.beranda') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-gradient text-white text-xs font-bold shadow-sm hover:opacity-95 transition-opacity">
                            <i class="fa-solid fa-pen-to-square"></i> Buat Laporan Sekarang
                        </a>
                    </div>
                </li>
            @endforelse

        </ol>

    </main>

</div>
@endsection
