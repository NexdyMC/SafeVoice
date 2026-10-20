@extends('layouts.user')

@section('title', 'Beranda Siswa - Ruang Aman SafeVoice')

@section('content')
<div x-data="{ 
    openReportModal: {{ $errors->any() ? 'true' : 'false' }}, 
    isAnonymous: {{ old('is_anonim') ? 'true' : 'true' }}, 
    reportType: '{{ old('jenis_bullying', 'verbal') }}',
    openSosModal: false,
    descLength: 0
}">

    <!-- Konten Utama: Beranda Siswa -->
    <main class="flex-1 p-5 sm:p-6 space-y-7">

        {{-- Alert Notifikasi Berhasil --}}
        @if(session('success'))
            <div class="p-4 rounded-3xl bg-emerald-50 border border-emerald-200/80 text-emerald-900 text-xs shadow-sm flex items-center justify-between gap-3 animate-fade-in">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm">{{ session('success') }}</p>
                        @if(session('kode_laporan'))
                            <p class="text-[11px] text-emerald-700 font-mono mt-0.5">Kode Laporan: <span class="font-extrabold">{{ session('kode_laporan') }}</span></p>
                        @endif
                    </div>
                </div>
                <a href="{{ route('user.riwayat') }}" class="px-3 py-1.5 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition-colors shrink-0">
                    Buka Riwayat
                </a>
            </div>
        @endif

        {{-- Alert Validasi Error --}}
        @if($errors->any())
            <div class="p-4 rounded-3xl bg-rose-50 border border-rose-200 text-rose-900 text-xs shadow-sm space-y-1.5">
                <div class="flex items-center gap-2 font-bold text-sm text-rose-800">
                    <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                    <span>Isian laporan belum lengkap:</span>
                </div>
                <ul class="list-disc list-inside text-rose-700 pl-2 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- HERO SECTION: Empathetic Safe Space Banner --}}
        <section class="p-7 sm:p-8 rounded-[32px] gradient-aurora text-white shadow-xl shadow-brandBlue/20 relative overflow-hidden">
            <!-- Background Ornaments -->
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-cyan-400/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -left-16 w-56 h-56 bg-brandCoral/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-3">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md text-[11px] font-bold text-cyan-200 border border-white/20">
                    <i class="fa-solid fa-shield-heart text-cyan-300"></i>
                    <span>100% Rahasia & Dilindungi</span>
                </div>

                <h2 class="text-2xl sm:text-3xl font-extrabold leading-snug tracking-tight text-white drop-shadow-sm">
                    Kamu berhak merasa aman di sekolah.
                </h2>

                <p class="text-xs sm:text-sm text-slate-200 font-medium leading-relaxed max-w-lg">
                    Apapun yang terjadi—ejekan, pengucilan, atau kekerasan—ceritakan tanpa rasa takut. Suaramu akan didengar dan ditindaklanjuti dengan empati.
                </p>
            </div>

            <div class="mt-7 flex flex-wrap items-center gap-3 relative z-10">
                <button @click="openReportModal = true" class="px-6 py-3.5 rounded-2xl bg-white hover:bg-slate-50 text-brandDark font-extrabold text-xs sm:text-sm shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 flex items-center gap-2.5 cursor-pointer">
                    <i class="fa-solid fa-pen-nib text-brandBlue text-base"></i>
                    <span>Ceritakan Sekarang</span>
                </button>

                <button @click="openSosModal = true" class="px-4 py-3.5 rounded-2xl bg-rose-500/20 hover:bg-rose-500/30 text-rose-200 hover:text-white font-bold text-xs border border-rose-300/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-400"></i>
                    <span>Tombol Darurat (SOS)</span>
                </button>
            </div>
        </section>

        {{-- STATUS LAPORAN TERAKHIR (DATA NYATA PERANGKAT INI) --}}
        <section class="space-y-3.5">
            <div class="flex items-center justify-between px-1">
                <h3 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-heart-pulse text-brandBlue"></i> Laporan Aktif Perangkat Ini
                </h3>
                @if($latestLaporan)
                    <a href="{{ route('user.riwayat') }}" class="text-xs text-brandBlue font-bold hover:underline flex items-center gap-1">
                        <span>Semua Riwayat</span> <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                @endif
            </div>

            @if($latestLaporan)
                <div class="p-6 rounded-[28px] bg-white border border-slate-200/80 shadow-soft-float space-y-4 transition-all">
                    <div class="flex items-start justify-between gap-3">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-mono font-bold text-xs text-brandBlue">{{ $latestLaporan->kode_laporan }}</span>
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 capitalize border border-slate-200">
                                    {{ str_replace('_', ' ', $latestLaporan->jenis_bullying) }}
                                </span>
                                @if($latestLaporan->is_anonim)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">Anonim</span>
                                @endif
                            </div>
                            <h4 class="font-bold text-sm sm:text-base text-slate-800 line-clamp-1">
                                Terlapor: {{ $latestLaporan->terlapor }}
                            </h4>
                        </div>

                        {{-- Status Pill --}}
                        @php
                            $badgeConf = match($latestLaporan->status) {
                                'diproses' => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200', 'dot' => 'bg-amber-500', 'label' => 'Dalam Proses'],
                                'selesai' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'Selesai'],
                                'ditolak' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'dot' => 'bg-rose-500', 'label' => 'Ditolak'],
                                default => ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'dot' => 'bg-slate-500', 'label' => 'Pending']
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $badgeConf['bg'] }} flex items-center gap-1.5 shrink-0">
                            <span class="w-2 h-2 rounded-full {{ $badgeConf['dot'] }} animate-pulse"></span>
                            {{ $badgeConf['label'] }}
                        </span>
                    </div>

                    {{-- Stepper Progress Dinamis Berdasarkan Flowchart --}}
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <div class="grid grid-cols-4 gap-1 text-center text-[10px] font-bold text-slate-400">
                            @php
                                $step = match($latestLaporan->status) {
                                    'ditolak' => 2,
                                    'diproses' => 3,
                                    'selesai' => 4,
                                    default => 1,
                                };
                            @endphp
                            <div class="{{ $step >= 1 ? 'text-brandBlue' : '' }}">
                                <div class="w-6 h-6 mx-auto rounded-full {{ $step >= 1 ? 'bg-brandBlue text-white' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-[10px] mb-1">1</div>
                                <span>Kirim</span>
                            </div>
                            <div class="{{ $step >= 2 ? 'text-brandBlue' : '' }}">
                                <div class="w-6 h-6 mx-auto rounded-full {{ $step >= 2 ? 'bg-brandBlue text-white' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-[10px] mb-1">2</div>
                                <span>Verifikasi BK</span>
                            </div>
                            <div class="{{ $step >= 3 ? 'text-brandBlue' : '' }}">
                                <div class="w-6 h-6 mx-auto rounded-full {{ $step >= 3 ? 'bg-brandBlue text-white' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-[10px] mb-1">3</div>
                                <span>Konsultasi</span>
                            </div>
                            <div class="{{ $step >= 4 ? 'text-emerald-600' : '' }}">
                                <div class="w-6 h-6 mx-auto rounded-full {{ $step >= 4 ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-400' }} flex items-center justify-center text-[10px] mb-1">4</div>
                                <span>Tuntas</span>
                            </div>
                        </div>
                    </div>

                    @php
                        $lastLog = $latestLaporan->statusLogs->last();
                    @endphp
                    @if($lastLog && $lastLog->umpan_balik)
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200/70 text-xs text-slate-600 italic">
                            "{{ $lastLog->umpan_balik }}"
                        </div>
                    @endif
                </div>
            @else
                <div class="p-6 rounded-[28px] bg-white border border-slate-200/80 shadow-soft-float text-center space-y-3">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl shadow-sm">
                        <i class="fa-solid fa-feather"></i>
                    </div>
                    <div class="space-y-1">
                        <h4 class="font-bold text-sm text-slate-900">Belum Ada Laporan Aktif</h4>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto leading-relaxed">
                            Kondisimu di sekolah terjaga. Jika suatu hari kamu mengalami atau melihat perundungan, klik tombol di atas untuk melapor secara rahasia.
                        </p>
                    </div>
                </div>
            @endif
        </section>

        {{-- KATEGORI PERUNDUNGAN (Edukasi Visual Siswa) --}}
        <section class="space-y-3.5">
            <h3 class="font-extrabold text-sm text-slate-900 flex items-center gap-2 px-1">
                <i class="fa-solid fa-shapes text-brandBlue"></i> Kenali Jenis Perundungan
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
                <!-- Verbal -->
                <div @click="reportType = 'verbal'; openReportModal = true" class="p-4 rounded-3xl bg-white border border-slate-200/80 hover:border-blue-300 hover:shadow-soft-float transition-all cursor-pointer group space-y-2">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-comment-slash"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-slate-900">Verbal</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 leading-tight">Ejekan, julukan buruk, hinaan fisik</p>
                    </div>
                </div>

                <!-- Fisik -->
                <div @click="reportType = 'fisik'; openReportModal = true" class="p-4 rounded-3xl bg-white border border-slate-200/80 hover:border-rose-300 hover:shadow-soft-float transition-all cursor-pointer group space-y-2">
                    <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-hand-fist"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-slate-900">Fisik</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 leading-tight">Dorongan, pukulan, pemalakan</p>
                    </div>
                </div>

                <!-- Sosial -->
                <div @click="reportType = 'sosial'; openReportModal = true" class="p-4 rounded-3xl bg-white border border-slate-200/80 hover:border-purple-300 hover:shadow-soft-float transition-all cursor-pointer group space-y-2">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-user-xmark"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-slate-900">Sosial</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 leading-tight">Pengucilan, fitnah, penyebaran rumor</p>
                    </div>
                </div>

                <!-- Siber -->
                <div @click="reportType = 'cyber_bullying'; openReportModal = true" class="p-4 rounded-3xl bg-white border border-slate-200/80 hover:border-amber-300 hover:shadow-soft-float transition-all cursor-pointer group space-y-2">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-slate-900">Siber</h4>
                        <p class="text-[10px] text-slate-500 mt-0.5 leading-tight">Hinaan medsos, teror pesan digital</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- BANTUAN KONSELING LANGSUNG DENGAN GURU BK --}}
        <section class="p-5 sm:p-6 rounded-[28px] bg-gradient-to-br from-blue-50/80 via-white to-cyan-50/50 border border-blue-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-brandBlue text-white flex items-center justify-center text-xl shrink-0 shadow-glow-blue">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 text-brandBlue uppercase">Layanan BK Sekolah</span>
                    <h4 class="font-extrabold text-sm text-slate-900 mt-1">Konseling Tatap Muka & Daring</h4>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $guruBkContact?->user?->nama_lengkap ?? 'Dra. Siti Rahmawati, M.Pd (Guru BK)' }}</p>
                </div>
            </div>

            <a href="https://wa.me/6281234567890?text=Halo%20Ibu%20BK,%20saya%20ingin%20berkonsultasi." target="_blank" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-brandBlue hover:bg-brandDark text-white font-bold text-xs shadow-md transition-all flex items-center justify-center gap-2">
                <i class="fa-brands fa-whatsapp text-sm"></i>
                <span>Hubungi Guru BK</span>
            </a>
        </section>

    </main>

    {{-- ================= MODAL LAPORAN PERUNDUNGAN ================= --}}
    <div x-show="openReportModal" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-950/60 backdrop-blur-sm transition-opacity">
        <div @click.away="openReportModal = false" class="w-full max-w-lg bg-white rounded-t-[32px] sm:rounded-[32px] p-6 sm:p-8 shadow-2xl max-h-[90vh] overflow-y-auto space-y-5">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-brandBlue text-white flex items-center justify-center text-sm shadow-sm">
                        <i class="fa-solid fa-shield"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base text-slate-900">Formulir Ruang Aman</h3>
                        <p class="text-[11px] text-slate-500 font-medium">Laporanmu hanya akan dibaca oleh Guru BK.</p>
                    </div>
                </div>
                <button @click="openReportModal = false" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-700 flex items-center justify-center text-lg">&times;</button>
            </div>

            <form action="{{ route('user.laporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf

                {{-- Toggle Anonim Modern --}}
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-slate-800 block text-xs">Kirim Secara Anonim</span>
                        <span class="text-[10px] text-slate-500">Nama dan kelasmu tidak akan dicantumkan di laporan.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_anonim" value="1" x-model="isAnonymous" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brandBlue"></div>
                    </label>
                </div>

                {{-- Pilihan Kategori Bullying --}}
                <div class="space-y-1.5">
                    <label class="font-bold text-slate-800">Jenis Perundungan yang Terjadi: <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        <label :class="reportType === 'verbal' ? 'bg-blue-50 border-brandBlue text-brandBlue font-extrabold' : 'bg-white border-slate-200 text-slate-700'" class="p-2.5 rounded-xl border flex items-center gap-2 cursor-pointer transition-all">
                            <input type="radio" name="jenis_bullying" value="verbal" x-model="reportType" class="sr-only">
                            <i class="fa-solid fa-comment-slash"></i>
                            <span>Verbal</span>
                        </label>
                        <label :class="reportType === 'fisik' ? 'bg-rose-50 border-rose-500 text-rose-600 font-extrabold' : 'bg-white border-slate-200 text-slate-700'" class="p-2.5 rounded-xl border flex items-center gap-2 cursor-pointer transition-all">
                            <input type="radio" name="jenis_bullying" value="fisik" x-model="reportType" class="sr-only">
                            <i class="fa-solid fa-hand-fist"></i>
                            <span>Fisik</span>
                        </label>
                        <label :class="reportType === 'sosial' ? 'bg-purple-50 border-purple-500 text-purple-600 font-extrabold' : 'bg-white border-slate-200 text-slate-700'" class="p-2.5 rounded-xl border flex items-center gap-2 cursor-pointer transition-all">
                            <input type="radio" name="jenis_bullying" value="sosial" x-model="reportType" class="sr-only">
                            <i class="fa-solid fa-user-xmark"></i>
                            <span>Sosial</span>
                        </label>
                        <label :class="reportType === 'cyber_bullying' ? 'bg-amber-50 border-amber-500 text-amber-700 font-extrabold' : 'bg-white border-slate-200 text-slate-700'" class="p-2.5 rounded-xl border flex items-center gap-2 cursor-pointer transition-all">
                            <input type="radio" name="jenis_bullying" value="cyber_bullying" x-model="reportType" class="sr-only">
                            <i class="fa-solid fa-mobile-screen"></i>
                            <span>Siber</span>
                        </label>
                    </div>
                </div>

                {{-- Terlapor & Lokasi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="font-bold text-slate-800">Nama Terlapor (Pelaku):</label>
                        <input type="text" name="terlapor" value="{{ old('terlapor') }}" placeholder="Boleh nama/inisial/tidak dikenal" class="w-full p-2.5 rounded-xl border border-slate-200 focus:border-brandBlue focus:outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="font-bold text-slate-800">Lokasi Kejadian: <span class="text-rose-500">*</span></label>
                        <input type="text" name="lokasi_kejadian" required value="{{ old('lokasi_kejadian') }}" placeholder="Contoh: Kantin, Kelas XI RPL" class="w-full p-2.5 rounded-xl border border-slate-200 focus:border-brandBlue focus:outline-none">
                    </div>
                </div>

                {{-- Tanggal Kejadian --}}
                <div class="space-y-1">
                    <label class="font-bold text-slate-800">Tanggal Kejadian: <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_kejadian" required value="{{ old('tanggal_kejadian', date('Y-m-d')) }}" class="w-full p-2.5 rounded-xl border border-slate-200 focus:border-brandBlue focus:outline-none font-medium">
                </div>

                {{-- Deskripsi Kronologi --}}
                <div class="space-y-1">
                    <label class="font-bold text-slate-800">Ceritakan Apa yang Terjadi: <span class="text-rose-500">*</span></label>
                    <textarea name="deskripsi" required rows="3" placeholder="Tuliskan kejadian yang kamu alami atau lihat dengan sejujurnya..." class="w-full p-3 rounded-xl border border-slate-200 focus:border-brandBlue focus:outline-none leading-relaxed text-xs">{{ old('deskripsi') }}</textarea>
                </div>

                {{-- Upload Bukti Opsional --}}
                <div class="space-y-1">
                    <label class="font-bold text-slate-800">Lampirkan Bukti Foto/Tangkapan Layar (Opsional):</label>
                    <input type="file" name="bukti" accept="image/*,.pdf" class="w-full p-2 rounded-xl border border-slate-200 text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-brandBlue hover:file:bg-blue-100">
                </div>

                <div class="pt-2 flex items-center justify-end gap-3">
                    <button type="button" @click="openReportModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-brandBlue hover:bg-brandDark text-white font-extrabold shadow-md transition-all flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= MODAL TOMBOL DARURAT SOS ================= --}}
    <div x-show="openSosModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm">
        <div @click.away="openSosModal = false" class="w-full max-w-sm bg-white rounded-3xl p-6 shadow-2xl text-center space-y-4">
            <div class="w-16 h-16 mx-auto rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-3xl animate-bounce">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="space-y-1">
                <h3 class="font-extrabold text-lg text-slate-900">Butuh Bantuan Mendesak?</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Jika kamu sedang dalam bahaya fisik langsung di sekolah, segera temui guru terdekat atau hubungi hotline BK di bawah ini.
                </p>
            </div>
            <div class="space-y-2 pt-2">
                <a href="https://wa.me/6281234567890?text=DARURAT%20-%20Saya%20membutuhkan%20perlindungan%20segera%20di%20sekolah." target="_blank" class="w-full py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-lg shadow-rose-600/30 flex items-center justify-center gap-2 transition-all">
                    <i class="fa-brands fa-whatsapp text-base"></i> Hubungi Hotline Darurat Sekarang
                </a>
                <button type="button" @click="openSosModal = false" class="w-full py-2.5 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs hover:bg-slate-200 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
