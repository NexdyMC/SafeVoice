@extends('layouts.user')

@section('title', 'Pusat Bantuan & Profil Siswa - Ruang Aman')

@section('content')
<!-- Konten Utama: Profil & Pusat Bantuan Siswa -->
<main class="flex-1 p-5 sm:p-6 space-y-6">

    <!-- Kartu Identitas Siswa Modern -->
    <div class="p-6 rounded-[28px] bg-white border border-slate-200/80 shadow-soft-float flex flex-col sm:flex-row items-center sm:items-start gap-4">
        <div class="w-16 h-16 rounded-2xl gradient-aurora text-white flex items-center justify-center font-extrabold text-xl shadow-glow-blue shrink-0">
            {{ strtoupper(substr($siswa->user?->nama_lengkap ?? 'Siswa', 0, 2)) }}
        </div>
        <div class="flex-1 text-center sm:text-left space-y-1">
            <h2 class="text-base sm:text-lg font-extrabold text-slate-900">{{ $siswa->user?->nama_lengkap ?? 'Siswa Ruang Aman' }}</h2>
            <p class="text-xs text-slate-500 font-semibold">
                {{ $siswa->kelas?->nama_kelas ?? 'Umum' }} &bull; NIS {{ $siswa->nis ?? '-' }}
            </p>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold border border-emerald-200 mt-2">
                <i class="fa-solid fa-circle-check text-[10px]"></i> Akun Terverifikasi
            </div>
        </div>
    </div>

    <!-- Kartu Pusat Konseling Langsung Guru BK -->
    <section class="p-6 rounded-[28px] bg-gradient-to-br from-blue-50/80 via-white to-cyan-50/40 border border-blue-200/70 shadow-soft-float space-y-4 relative overflow-hidden">
        <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-brandBlue/10 rounded-full blur-xl pointer-events-none"></div>
        
        <div class="flex items-start justify-between">
            <div>
                <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-brandBlue text-white uppercase tracking-wider">Pusat Konseling Sekolah</span>
                <h3 class="font-extrabold text-base text-slate-900 mt-2">Butuh Teman Cerita Langsung?</h3>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-white text-brandBlue flex items-center justify-center shadow-sm border border-blue-100 text-lg">
                <i class="fa-solid fa-comments"></i>
            </div>
        </div>

        <div class="space-y-2 text-xs text-slate-600">
            <p class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-user-tie text-brandBlue"></i>
                <span>{{ $guruBkContact?->user?->nama_lengkap ?? 'Dra. Siti Rahmawati, M.Pd (Guru BK)' }}</span>
            </p>
            <p class="flex items-center gap-2"><i class="fa-solid fa-location-dot text-brandBlue w-4 text-center"></i> Ruang Konseling BK &bull; Gedung Utama Lantai 1</p>
            <p class="flex items-center gap-2"><i class="fa-regular fa-clock text-brandBlue w-4 text-center"></i> Jam Kerja: Senin - Jumat, 07.30 - 15.00 WIB</p>
        </div>

        <div class="pt-2">
            <a href="https://wa.me/6281234567890?text=Halo%20Ibu%20BK,%20saya%20ingin%20berkonsultasi." target="_blank" class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-brandBlue hover:bg-brandDark text-white font-extrabold text-xs shadow-glow-blue transition-all">
                <i class="fa-brands fa-whatsapp text-base"></i>
                <span>Hubungi Guru BK via WhatsApp</span>
            </a>
        </div>
    </section>

    <!-- Kartu Statistik Aktivitas Pribadi HP Ini -->
    <div class="p-6 rounded-[28px] bg-white border border-slate-200/80 shadow-soft-float space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-chart-simple text-brandBlue"></i> Aktivitas di Perangkat Ini
            </h3>
            <a href="{{ route('user.riwayat') }}" class="text-xs text-brandBlue font-bold hover:underline">Buka Timeline &rarr;</a>
        </div>
        <div class="grid grid-cols-3 gap-3 text-center">
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                <p class="text-xl font-extrabold text-slate-900">{{ $totalLaporan }}</p>
                <p class="text-[10px] text-slate-500 font-bold uppercase mt-0.5">Total</p>
            </div>
            <div class="p-3.5 rounded-2xl bg-amber-50 border border-amber-100">
                <p class="text-xl font-extrabold text-amber-700">{{ $diprosesLaporan }}</p>
                <p class="text-[10px] text-amber-800 font-bold uppercase mt-0.5">Diproses</p>
            </div>
            <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-100">
                <p class="text-xl font-extrabold text-emerald-700">{{ $selesaiLaporan }}</p>
                <p class="text-[10px] text-emerald-800 font-bold uppercase mt-0.5">Selesai</p>
            </div>
        </div>
    </div>

    <!-- Privasi & Bantuan Informasi -->
    <div class="p-5 rounded-[28px] bg-white border border-slate-200/80 shadow-soft-float space-y-3">
        <h3 class="font-extrabold text-xs text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-lock text-brandBlue"></i> Perlindungan Kerahasiaan
        </h3>
        <p class="text-xs text-slate-500 leading-relaxed">
            SafeVoice dirancang dengan standar enkripsi perlindungan anak. Tidak ada data laporan yang dibagikan secara publik. Akses riwayat sepenuhnya terkunci pada perangkat HP atau akun pelapor.
        </p>
    </div>

    <!-- Tombol Keluar Sesi -->
    <div class="pt-1">
        <a href="{{ route('user.login') }}" class="w-full py-3.5 rounded-2xl bg-white hover:bg-rose-50 text-slate-600 hover:text-rose-700 font-bold text-xs border border-slate-200 transition-all flex items-center justify-center gap-2 shadow-sm">
            <i class="fa-solid fa-power-off text-rose-600"></i>
            <span>Keluar dari Akun Siswa</span>
        </a>
    </div>

</main>
@endsection
