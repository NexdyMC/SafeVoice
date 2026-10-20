<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin - SafeVoice')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Font Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        oceanDark: '#0E2443',
                        oceanMain: '#12315F',
                        oceanBlue: '#2563EB',
                        oceanLight: '#38BDF8',
                        accentStart: '#FF8A5B',
                        accentEnd: '#FFB36B',
                        dominantBg: '#F8FAFC',
                        textMain: '#1F2937',
                        textSec: '#5B6573',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-ocean-sidebar {
            background-color: #12315F;
        }
        .bg-dominant-gradient {
            background: linear-gradient(180deg, #F8FAFC 0%, #EDF4FC 100%);
        }
        .bg-accent-gradient {
            background: linear-gradient(135deg, #FF8A5B 0%, #FFB36B 100%);
            background-size: 100%;
            transition: background-size 0.3s ease, transform 0.2s ease;
        }
        .bg-accent-gradient:hover {
            background-size: 160%;
            transform: translateY(-1px);
        }
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(18, 49, 95, 0.08), 0 8px 10px -6px rgba(18, 49, 95, 0.08);
        }
        @media (prefers-reduced-motion: reduce) {
            *, ::before, ::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-dominant-gradient text-textMain min-h-screen flex antialiased" x-data="{ sidebarOpen: false, currentMenu: 'ringkasan' }">

    <!-- SIDEBAR (Desktop & Mobile Drawer) -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-ocean-sidebar text-white flex flex-col justify-between transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-auto shadow-xl">
        
        <!-- Logo & Brand -->
        <div class="p-6 space-y-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center text-white border border-white/20 shadow-inner">
                    <i class="fa-solid fa-shield-heart text-xl text-[#38BDF8]"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg text-white leading-tight">SafeVoice</h1>
                    <p class="text-[11px] text-white/60 font-medium">Pusat Keadilan</p>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="space-y-1.5 text-sm font-medium">
                <a href="#" @click="currentMenu = 'ringkasan'" :class="currentMenu === 'ringkasan' ? 'bg-white/15 text-white font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl transition-all">
                    <i class="fa-solid fa-house-chimney w-5 text-center"></i>
                    <span>Ringkasan</span>
                </a>

                <div class="pt-3 pb-1 px-4 text-[10px] font-bold tracking-wider uppercase text-white/40">
                    Alur Guru BK (Flowchart)
                </div>

                <a href="#" @click="currentMenu = 'bk_verifikasi'" :class="currentMenu === 'bk_verifikasi' ? 'bg-amber-500 text-white font-bold shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl transition-all relative">
                    <i class="fa-solid fa-inbox w-5 text-center text-amber-300"></i>
                    <span>1. Verifikasi Masuk</span>
                    @if(($pendingCount ?? 0) > 0)
                        <span class="ml-auto px-2 py-0.5 rounded-full text-[10px] bg-red-500 text-white font-bold">{{ $pendingCount }}</span>
                    @endif
                </a>

                <a href="#" @click="currentMenu = 'bk_konsultasi'" :class="currentMenu === 'bk_konsultasi' ? 'bg-blue-600 text-white font-bold shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl transition-all relative">
                    <i class="fa-solid fa-comments w-5 text-center text-blue-300"></i>
                    <span>2. Konsultasi Wali Kelas</span>
                    @if(($diprosesCount ?? 0) > 0)
                        <span class="ml-auto px-2 py-0.5 rounded-full text-[10px] bg-blue-500 text-white font-bold">{{ $diprosesCount }}</span>
                    @endif
                </a>

                <a href="#" @click="currentMenu = 'bk_tindak_lanjut'" :class="currentMenu === 'bk_tindak_lanjut' ? 'bg-emerald-600 text-white font-bold shadow-sm' : 'text-white/80 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl transition-all">
                    <i class="fa-solid fa-clipboard-check w-5 text-center text-emerald-300"></i>
                    <span>3. Tindak Lanjut & Monitoring</span>
                </a>

                <div class="pt-3 pb-1 px-4 text-[10px] font-bold tracking-wider uppercase text-white/40">
                    Manajemen Data
                </div>

                <a href="#" @click="currentMenu = 'laporan'" :class="currentMenu === 'laporan' ? 'bg-white/15 text-white font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl transition-all">
                    <i class="fa-solid fa-folder-open w-5 text-center"></i>
                    <span>Semua Laporan</span>
                </a>
                <a href="#" @click="currentMenu = 'pengguna'" :class="currentMenu === 'pengguna' ? 'bg-white/15 text-white font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl transition-all">
                    <i class="fa-solid fa-users-gear w-5 text-center"></i>
                    <span>Pengguna</span>
                </a>
                <a href="#" @click="currentMenu = 'kelas'" :class="currentMenu === 'kelas' ? 'bg-white/15 text-white font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl transition-all">
                    <i class="fa-solid fa-school w-5 text-center"></i>
                    <span>Kelas</span>
                </a>
                <a href="#" @click="currentMenu = 'log'" :class="currentMenu === 'log' ? 'bg-white/15 text-white font-bold shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white'" class="flex items-center gap-3 px-4 py-2.5 rounded-2xl transition-all">
                    <i class="fa-solid fa-timeline w-5 text-center"></i>
                    <span>Log Aktivitas</span>
                </a>
            </nav>
        </div>

        <!-- Profil Admin & Tombol Keluar -->
        <div class="p-6 border-t border-white/10 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/15 text-white flex items-center justify-center font-bold text-sm border border-white/10">
                    KS
                </div>
                <div>
                    <h2 class="font-bold text-sm text-white">Kepala Sekolah / Guru BK</h2>
                    <p class="text-[11px] text-white/60">Admin</p>
                </div>
            </div>
            <a href="{{ route('admin.login') }}" class="w-full py-2.5 rounded-xl bg-white/10 hover:bg-red-500/20 text-white/80 hover:text-red-200 text-xs font-bold transition-colors flex items-center justify-center gap-2 border border-white/10">
                <i class="fa-solid fa-power-off text-xs"></i>
                <span>Keluar</span>
            </a>
        </div>

    </aside>

    <!-- MAIN CONTENT AREA -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- Top Bar Mobile & Header -->
        <header class="bg-white/85 backdrop-blur-md border-b border-gray-100 px-6 py-4 flex items-center justify-between sticky top-0 z-40 shadow-sm">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-textMain border border-gray-200">
                    <i class="fa-solid fa-bars text-base"></i>
                </button>
                <div>
                    <h2 class="text-xl font-bold text-textMain capitalize" x-text="currentMenu">Ringkasan</h2>
                    <p class="text-xs text-textSec font-medium">Kondisi laporan di sekolah tahun ajaran ini. Tampilan memakai data contoh.</p>
                </div>
            </div>

            <!-- Tahun Ajaran & Ekspor Laporan -->
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 bg-gray-50 px-3 py-2 rounded-xl border border-gray-200 text-xs font-semibold text-textMain">
                    <span class="text-textSec">Tahun ajaran:</span>
                    <select class="bg-transparent focus:outline-none font-bold text-oceanMain">
                        <option value="2026/2027">2026/2027</option>
                        <option value="2025/2026">2025/2026</option>
                    </select>
                </div>
                <button class="px-4 py-2 rounded-xl bg-accent-gradient text-textMain font-bold text-xs shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-file-excel"></i>
                    <span>Ekspor laporan</span>
                </button>
            </div>
        </header>

        <!-- Konten Utama Slot -->
        @yield('content')

        <!-- Footer Ringkas -->
        <footer class="py-4 px-6 text-center text-xs text-textSec border-t border-gray-100 bg-white">
            <p>SafeVoice Dashboard Admin &bull; Sistem Pemantauan & Manajemen Pengaduan Sekolah</p>
        </footer>

    </div>

    @stack('scripts')
</body>
</html>
