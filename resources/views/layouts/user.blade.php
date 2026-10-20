<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ruang Aman - SafeVoice')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Font Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brandDark: '#0A2540',
                        brandBlue: '#2060EE',
                        brandCyan: '#06B6D4',
                        brandCoral: '#FF6B6B',
                        brandAmber: '#F59E0B',
                        surfaceMuted: '#F1F5F9',
                        textMain: '#0F172A',
                        textSec: '#64748B',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft-float': '0 20px 40px -15px rgba(15, 23, 42, 0.08), 0 0 1px 1px rgba(15, 23, 42, 0.04)',
                        'glow-blue': '0 10px 25px -5px rgba(32, 96, 238, 0.35)',
                        'glow-coral': '0 10px 25px -5px rgba(255, 107, 107, 0.35)',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-aurora {
            background: linear-gradient(135deg, #0A2540 0%, #154577 45%, #2060EE 100%);
        }
        .gradient-coral {
            background: linear-gradient(135deg, #FF6B6B 0%, #FFA07A 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }
        .dock-glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(226, 232, 240, 0.8);
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
<body class="bg-slate-100 text-textMain min-h-full flex flex-col antialiased selection:bg-brandBlue selection:text-white">

    <!-- Container Utama: Max 768px (Mobile & Tablet Friendly) -->
    <div class="w-full max-w-[768px] mx-auto min-h-screen bg-slate-50/90 shadow-2xl shadow-slate-300/40 flex flex-col relative pb-28 sm:pb-24 border-x border-slate-200/60">

        <!-- Bar Atas Modern dengan Aksen Humanis -->
        <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-slate-200/70 px-5 py-3.5 flex items-center justify-between transition-all">
            <a href="{{ route('user.beranda') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-2xl gradient-aurora flex items-center justify-center text-white shadow-glow-blue group-hover:scale-105 transition-transform">
                    <i class="fa-solid fa-shield-heart text-lg text-cyan-300"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-base tracking-tight text-slate-900 leading-tight">Ruang Aman</h1>
                    <p class="text-[11px] text-slate-500 font-semibold flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Layanan BK Sekolah
                    </p>
                </div>
            </a>

            <div class="flex items-center gap-2.5">
                <!-- Tombol Darurat Cepat -->
                <a href="https://wa.me/6281234567890?text=Halo%20Guru%20BK,%20saya%20membutuhkan%20bantuan%20darurat%20terkait%20perundungan." target="_blank" class="px-3 py-1.5 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition-all border border-rose-200/80 flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-headset text-rose-600"></i>
                    <span class="hidden sm:inline">Kontak Darurat</span>
                </a>

                <!-- Profil Link -->
                <a href="{{ route('user.profile') }}" class="w-9 h-9 rounded-full bg-gradient-to-tr from-brandBlue to-cyan-400 text-white flex items-center justify-center font-bold text-xs shadow-md hover:scale-105 transition-transform" title="Profil Siswa">
                    <i class="fa-solid fa-user-graduate"></i>
                </a>
            </div>
        </header>

        <!-- Konten Halaman -->
        @yield('content')

        <!-- Footer Ringkas & Elegan -->
        <footer class="py-5 text-center text-xs text-slate-400 border-t border-slate-200/70 bg-white/50 mt-auto px-4">
            <p class="font-medium">&copy; 2026 SafeVoice Ruang Aman &bull; Menjaga Kerahasiaan & Melindungi Setiap Ceritamu</p>
        </footer>

        <!-- Floating Dock Navigation Bawah (Modern App Style) -->
        <nav class="fixed bottom-4 left-4 right-4 z-40 dock-glass px-6 py-2.5 max-w-[500px] mx-auto rounded-3xl shadow-soft-float flex items-center justify-around border border-slate-200/80">
            <a href="{{ route('user.beranda') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-2xl transition-all {{ request()->routeIs('user.beranda') ? 'text-brandBlue font-extrabold scale-105 bg-blue-50/80' : 'text-slate-500 hover:text-brandBlue font-medium' }}">
                <i class="fa-solid fa-house-chimney text-lg"></i>
                <span class="text-[10px] tracking-tight">Beranda</span>
            </a>
            <a href="{{ route('user.riwayat') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-2xl transition-all relative {{ request()->routeIs('user.riwayat') ? 'text-brandBlue font-extrabold scale-105 bg-blue-50/80' : 'text-slate-500 hover:text-brandBlue font-medium' }}">
                <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                <span class="text-[10px] tracking-tight">Riwayat</span>
            </a>
            <a href="{{ route('user.profile') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-2xl transition-all {{ request()->routeIs('user.profile') ? 'text-brandBlue font-extrabold scale-105 bg-blue-50/80' : 'text-slate-500 hover:text-brandBlue font-medium' }}">
                <i class="fa-solid fa-user-shield text-lg"></i>
                <span class="text-[10px] tracking-tight">Pusat Bantuan</span>
            </a>
        </nav>

    </div>

    @stack('scripts')
</body>
</html>
