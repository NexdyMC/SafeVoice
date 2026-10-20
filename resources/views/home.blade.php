<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hentikan Perundungan | SafeSpace</title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Konfigurasi Custom Warna Tailwind -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            ocean: {
              900: '#0b1f38',
              /* Navy Gelap */
              800: '#143464',
              500: '#2193b0',
              /* Biru Ocean */
              400: '#6dd5ed',
              /* Cyan */
              accent: '#ff8c00' /* Oranye */
            }
          },
          fontFamily: {
            sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Google Fonts (Inter) -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #0b1f38;
    }

    ::-webkit-scrollbar-thumb {
      background: #2193b0;
      border-radius: 4px;
    }
  </style>
</head>

<body class="bg-slate-900 text-slate-100 flex flex-col relative overflow-x-hidden">

  <!-- NAVIGASI -->
  <nav id="navbar" class="fixed w-full z-50 transition-all duration-300 py-4 px-6 md:px-12 border-b border-transparent">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <a href="#" class="text-2xl font-bold flex items-center gap-3 text-white group">
        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-ocean-500 to-ocean-400 flex items-center justify-center shadow-lg shadow-ocean-500/40 group-hover:scale-110 transition-transform">
          <i class="fa-solid fa-shield-heart text-white text-lg"></i>
        </div>
        <span class="tracking-wide">Safe<span class="text-ocean-400">Space.</span></span>
      </a>

      <div class="hidden md:flex items-center gap-8">
        <a href="#hero" class="hover:text-ocean-400 transition-colors flex items-center gap-2 font-medium">
          <i class="fa-solid fa-house fa-sm"></i> Beranda
        </a>
        <a href="#tentang" class="hover:text-ocean-400 transition-colors flex items-center gap-2 font-medium">
          <i class="fa-solid fa-circle-info fa-sm"></i> Tentang Kami
        </a>
        <a href="#" class="hover:text-ocean-400 transition-colors flex items-center gap-2 font-medium">
          <i class="fa-solid fa-book-open-reader fa-sm"></i> Edukasi
        </a>
      </div>

      <div class="hidden md:block">
        <a href="#" class="text-ocean-accent hover:text-white border border-ocean-accent hover:bg-ocean-accent transition-all px-5 py-2 rounded-full font-medium flex items-center gap-2 text-sm">
          <i class="fa-solid fa-phone-volume"></i> Darurat
        </a>
      </div>

      <button class="md:hidden text-2xl text-white">
        <i class="fa-solid fa-bars"></i>
      </button>
    </div>
  </nav>

  <!-- HERO SECTION (Background Theme Ocean Gelap) -->
  <section id="hero" class="bg-gradient-to-br from-ocean-900 via-ocean-800 to-ocean-900 min-h-screen flex items-center justify-center relative z-10 pt-28 pb-20 px-6">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-3xl h-96 bg-ocean-500 rounded-full blur-[150px] opacity-20 pointer-events-none"></div>

    <div class="max-w-4xl mx-auto text-center">
      <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-ocean-800/50 border border-ocean-500/30 text-ocean-400 text-sm font-semibold mb-8 backdrop-blur-sm">
        <i class="fa-solid fa-hand-holding-heart"></i>
        <span>Anda Tidak Sendirian</span>
      </div>

      <h1 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight drop-shadow-lg">
        Bersama Kita Hentikan <br>
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-ocean-400 to-ocean-500">Perundungan.</span>
      </h1>

      <p class="text-lg md:text-xl text-slate-300 mb-10 max-w-2xl mx-auto leading-relaxed">
        Platform aman untuk berbagi cerita, melaporkan insiden perundungan, dan mendapatkan dukungan psikologis. Jangan pernah takut untuk bersuara.
      </p>

      <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <button id="btnLogin" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-blue-500 to-blue-400 hover:from-blue-600 hover:to-blue-500 text-white font-semibold rounded-xl shadow-[0_0_20px_rgba(33,147,176,0.4)] transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3">
          <i class="fa-solid fa-right-to-bracket text-lg"></i>
          Masuk Portal
        </button>

        <button id="btnAi" class="w-full sm:w-auto px-8 py-4 bg-ocean-800/40 hover:bg-ocean-800/80 border border-ocean-500/50 text-ocean-400 hover:text-white font-semibold rounded-xl backdrop-blur-sm transition-all transform hover:-translate-y-1 flex items-center justify-center gap-3 group">
          <i class="fa-solid fa-robot text-lg group-hover:animate-bounce"></i>
          Tanya Jawab AI
        </button>
      </div>
    </div>
  </section>

  <!-- SECTION DEFINISI & TUJUAN (LATAR BELAKANG PUTIH) -->
  <section id="tentang" class="bg-white text-slate-800 py-24 px-6 md:px-12 relative overflow-hidden">
    <div class="max-w-7xl mx-auto">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16 items-center">

        <!-- SISI KIRI: Card Logo & Visual Shield -->
        <div class="flex justify-center items-center">
          <div class="relative w-full max-w-md bg-gradient-to-br from-slate-50 via-cyan-50/30 to-slate-100 rounded-3xl p-10 border border-slate-200/80 shadow-2xl shadow-slate-200 flex flex-col items-center justify-center text-center group">

            <!-- Ornamen Hiasan Belakang Logo -->
            <div class="absolute -top-6 -left-6 w-24 h-24 bg-ocean-500/10 rounded-full blur-xl"></div>
            <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-ocean-400/10 rounded-full blur-xl"></div>

            <!-- Logo Utama (Sesuai Tema Ocean) -->
            <div class="w-32 h-32 rounded-3xl bg-gradient-to-tr from-ocean-900 via-ocean-800 to-ocean-500 flex items-center justify-center shadow-xl shadow-ocean-900/20 mb-6 group-hover:scale-105 transition-transform duration-300">
              <i class="fa-solid fa-shield-heart text-6xl text-white"></i>
            </div>

            <!-- Nama Brand & Tagline -->
            <h3 class="text-3xl font-extrabold text-ocean-900 tracking-tight">
              Safe<span class="text-ocean-500">Space.</span>
            </h3>
            <p class="text-sm text-slate-500 font-medium mt-2">
              Gerakan Anti-Perundungan Digital & Sekolah
            </p>

            <!-- Badge Status -->
            <div class="mt-6 inline-flex items-center gap-2 px-4 py-2 rounded-full bg-ocean-50 border border-ocean-100 text-ocean-900 text-xs font-semibold">
              <i class="fa-solid fa-circle-check text-emerald-500"></i> Platform Resmi Perlindungan Siswa
            </div>
          </div>
        </div>

        <!-- SISI KANAN: Teks Definisi & Tujuan -->
        <div class="space-y-6">
          <!-- Badge Kategori -->
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg bg-ocean-50 border border-ocean-100 text-ocean-800 text-xs font-bold uppercase tracking-wider">
            <i class="fa-solid fa-circle-info text-ocean-500"></i> Tentang Platform Kami
          </div>

          <!-- Judul Utama Section -->
          <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 leading-tight">
            Menciptakan Lingkungan yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-ocean-500 to-ocean-400">Aman & Bebas Perundungan</span>
          </h2>

          <!-- Definisi Website -->
          <p class="text-slate-600 leading-relaxed text-base md:text-lg">
            <strong class="text-slate-800">SafeSpace</strong> adalah platform digital interaktif yang dirancang sebagai ruang aman untuk pencegahan, edukasi, dan penanganan aksi perundungan (<em>bullying</em>) baik di lingkungan sekolah maupun media sosial (<em>cyberbullying</em>).
          </p>

          <!-- List Tujuan Utama -->
          <div class="space-y-4 pt-2">
            <h4 class="text-lg font-bold text-slate-900 flex items-center gap-2">
              <i class="fa-solid fa-bullseye text-ocean-500"></i> Tujuan Utama Website Ini:
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <!-- Card Tujuan 1 -->
              <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 hover:border-ocean-400/50 hover:shadow-md transition-all">
                <div class="w-9 h-9 rounded-lg bg-ocean-500/10 text-ocean-500 flex items-center justify-center mb-2">
                  <i class="fa-solid fa-user-ninja"></i>
                </div>
                <h5 class="font-bold text-slate-800 text-sm">Pelaporan Anonim</h5>
                <p class="text-xs text-slate-500 mt-1">Siswa dapat melaporkan kasus perundungan tanpa rasa takut akan intimidasi.</p>
              </div>

              <!-- Card Tujuan 2 -->
              <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 hover:border-ocean-400/50 hover:shadow-md transition-all">
                <div class="w-9 h-9 rounded-lg bg-ocean-500/10 text-ocean-500 flex items-center justify-center mb-2">
                  <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <h5 class="font-bold text-slate-800 text-sm">Edukasi & Kesadaran</h5>
                <p class="text-xs text-slate-500 mt-1">Meningkatkan kesadaran dampak buruk perundungan bagi masa depan.</p>
              </div>

              <!-- Card Tujuan 3 -->
              <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 hover:border-ocean-400/50 hover:shadow-md transition-all">
                <div class="w-9 h-9 rounded-lg bg-ocean-500/10 text-ocean-500 flex items-center justify-center mb-2">
                  <i class="fa-solid fa-robot"></i>
                </div>
                <h5 class="font-bold text-slate-800 text-sm">Asisten AI 24/7</h5>
                <p class="text-xs text-slate-500 mt-1">Layanan pertolongan pertama dan teman cerita AI yang selalu siap mendengarkan.</p>
              </div>

              <!-- Card Tujuan 4 -->
              <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/60 hover:border-ocean-400/50 hover:shadow-md transition-all">
                <div class="w-9 h-9 rounded-lg bg-ocean-500/10 text-ocean-500 flex items-center justify-center mb-2">
                  <i class="fa-solid fa-hands-holding-child"></i>
                </div>
                <h5 class="font-bold text-slate-800 text-sm">Pendampingan Pakar</h5>
                <p class="text-xs text-slate-500 mt-1">Menghubungkan korban langsung dengan konselor dan guru BK sekolah.</p>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
  </section>

  <!-- SECTION ALUR KERJA (VERTICAL TIMELINE - LATAR GELAP) -->
  <section id="alur-kerja" class="bg-gradient-to-b from-ocean-900 via-slate-900 to-ocean-900 text-slate-100 py-24 px-6 md:px-12 relative overflow-hidden">
    <!-- Cahaya Latar Belakang (Ambient Glow) -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-ocean-500/10 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto relative z-10">
      <!-- Judul Section -->
      <div class="text-center mb-16">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-ocean-800/60 border border-ocean-500/30 text-ocean-400 text-xs font-semibold mb-4 backdrop-blur-sm">
          <i class="fa-solid fa-route"></i>
          <span>Panduan Alur Penggunaan</span>
        </div>
        <h2 class="text-3xl md:text-5xl font-extrabold text-white leading-tight">
          Cara Kerja <span class="text-transparent bg-clip-text bg-gradient-to-r from-ocean-400 to-ocean-500">Pelaporan & Penanganan</span>
        </h2>
        <p class="text-slate-400 mt-4 max-w-xl mx-auto text-sm md:text-base">
          Proses terstruktur dan rahasia untuk memastikan setiap aduan perundungan ditangani hingga tuntas.
        </p>
      </div>

      <!-- Vertical Timeline Container -->
      <div class="relative border-l-2 border-ocean-500/30 ml-4 md:ml-28 space-y-10">

        <!-- Tahap 1: Login -->
        <div class="relative pl-8 md:pl-12 group">
          <!-- Node/Indikator Angka -->
          <div class="absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-ocean-900 border-2 border-ocean-400 text-ocean-400 flex items-center justify-center font-bold text-xs shadow-[0_0_12px_rgba(109,213,237,0.4)] group-hover:bg-ocean-500 group-hover:text-white transition-all">
            1
          </div>
          <!-- Kartu Konten -->
          <div class="bg-ocean-800/40 border border-ocean-700/50 hover:border-ocean-400/50 rounded-2xl p-6 backdrop-blur-sm transition-all shadow-lg hover:-translate-y-1">
            <div class="flex items-center gap-3 text-ocean-400 mb-2">
              <i class="fa-solid fa-right-to-bracket text-lg"></i>
              <span class="text-xs font-bold uppercase tracking-wider text-ocean-400">Tahap 01</span>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Login ke Aplikasi</h3>
            <p class="text-sm text-slate-300 leading-relaxed">
              Masuk menggunakan akun siswa (Username/NIS) atau akses portal layanan untuk memulai proses aduan.
            </p>
          </div>
        </div>

        <!-- Tahap 2: Masukkan Kejadian -->
        <div class="relative pl-8 md:pl-12 group">
          <div class="absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-ocean-900 border-2 border-ocean-400 text-ocean-400 flex items-center justify-center font-bold text-xs shadow-[0_0_12px_rgba(109,213,237,0.4)] group-hover:bg-ocean-500 group-hover:text-white transition-all">
            2
          </div>
          <div class="bg-ocean-800/40 border border-ocean-700/50 hover:border-ocean-400/50 rounded-2xl p-6 backdrop-blur-sm transition-all shadow-lg hover:-translate-y-1">
            <div class="flex items-center gap-3 text-ocean-400 mb-2">
              <i class="fa-solid fa-pen-to-square text-lg"></i>
              <span class="text-xs font-bold uppercase tracking-wider text-ocean-400">Tahap 02</span>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Masukkan Kejadian</h3>
            <p class="text-sm text-slate-300 leading-relaxed">
              Isi formulir laporan dengan menjelaskan detail insiden perundungan, lokasi, waktu kejadian, serta lampirkan bukti pendukung jika ada.
            </p>
          </div>
        </div>

        <!-- Tahap 3: Tunggu Konfirmasi -->
        <div class="relative pl-8 md:pl-12 group">
          <div class="absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-ocean-900 border-2 border-ocean-400 text-ocean-400 flex items-center justify-center font-bold text-xs shadow-[0_0_12px_rgba(109,213,237,0.4)] group-hover:bg-ocean-500 group-hover:text-white transition-all">
            3
          </div>
          <div class="bg-ocean-800/40 border border-ocean-700/50 hover:border-ocean-400/50 rounded-2xl p-6 backdrop-blur-sm transition-all shadow-lg hover:-translate-y-1">
            <div class="flex items-center gap-3 text-ocean-400 mb-2">
              <i class="fa-solid fa-clock-rotate-left text-lg"></i>
              <span class="text-xs font-bold uppercase tracking-wider text-ocean-400">Tahap 03</span>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Tunggu Konfirmasi</h3>
            <p class="text-sm text-slate-300 leading-relaxed">
              Laporan masuk ke sistem dan tim pencegahan akan memproses verifikasi awal dokumen serta tingkat urgensi laporan.
            </p>
          </div>
        </div>

        <!-- Tahap 4: Validasi Wali Kelas -->
        <div class="relative pl-8 md:pl-12 group">
          <div class="absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-ocean-900 border-2 border-ocean-400 text-ocean-400 flex items-center justify-center font-bold text-xs shadow-[0_0_12px_rgba(109,213,237,0.4)] group-hover:bg-ocean-500 group-hover:text-white transition-all">
            4
          </div>
          <div class="bg-ocean-800/40 border border-ocean-700/50 hover:border-ocean-400/50 rounded-2xl p-6 backdrop-blur-sm transition-all shadow-lg hover:-translate-y-1">
            <div class="flex items-center gap-3 text-ocean-400 mb-2">
              <i class="fa-solid fa-user-check text-lg"></i>
              <span class="text-xs font-bold uppercase tracking-wider text-ocean-400">Tahap 04</span>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Validasi dengan Guru Wali Kelas</h3>
            <p class="text-sm text-slate-300 leading-relaxed">
              Wali kelas menerima notifikasi untuk memvalidasi konteks kejadian dan melakukan koordinasi awal secara internal.
            </p>
          </div>
        </div>

        <!-- Tahap 5: Tunggu di Ruang BK -->
        <div class="relative pl-8 md:pl-12 group">
          <div class="absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-ocean-900 border-2 border-ocean-400 text-ocean-400 flex items-center justify-center font-bold text-xs shadow-[0_0_12px_rgba(109,213,237,0.4)] group-hover:bg-ocean-500 group-hover:text-white transition-all">
            5
          </div>
          <div class="bg-ocean-800/40 border border-ocean-700/50 hover:border-ocean-400/50 rounded-2xl p-6 backdrop-blur-sm transition-all shadow-lg hover:-translate-y-1">
            <div class="flex items-center gap-3 text-ocean-400 mb-2">
              <i class="fa-solid fa-comments text-lg"></i>
              <span class="text-xs font-bold uppercase tracking-wider text-ocean-400">Tahap 05</span>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Tunggu & Pertemuan di Ruang BK</h3>
            <p class="text-sm text-slate-300 leading-relaxed">
              Siswa dijadwalkan hadir di ruang Bimbingan Konseling (BK) untuk pendampingan konseling privat dan proses mediasi aman.
            </p>
          </div>
        </div>

        <!-- Tahap 6: Konfirmasi Selesai -->
        <div class="relative pl-8 md:pl-12 group">
          <div class="absolute -left-[17px] top-0 w-8 h-8 rounded-full bg-emerald-500 border-2 border-emerald-400 text-white flex items-center justify-center font-bold text-xs shadow-[0_0_12px_rgba(16,185,129,0.5)]">
            <i class="fa-solid fa-check"></i>
          </div>
          <div class="bg-ocean-800/40 border border-emerald-500/40 hover:border-emerald-400 rounded-2xl p-6 backdrop-blur-sm transition-all shadow-lg hover:-translate-y-1">
            <div class="flex items-center gap-3 text-emerald-400 mb-2">
              <i class="fa-solid fa-circle-check text-lg"></i>
              <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Tahap Akhir</span>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Konfirmasi Selesai</h3>
            <p class="text-sm text-slate-300 leading-relaxed">
              Penanganan kasus dinyatakan selesai dengan kesepakatan solusi, dilengkapi dengan pemantauan kondisi psikologis siswa secara berkala.
            </p>
          </div>
        </div>

      </div>
    </div>
  </section>
  
  <!-- SECTION FAQ (LATAR BELAKANG PUTIH) -->
  <section id="faq" class="bg-white text-slate-800 py-24 px-6 md:px-12 relative overflow-hidden border-t border-slate-200">
    <!-- Cahaya Latar Belakang Halus -->
    <div class="absolute top-0 right-0 w-80 h-80 bg-ocean-400/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-ocean-500/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto relative z-10">
      <!-- Judul Section -->
      <div class="text-center mb-16">
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-ocean-50 border border-ocean-100 text-ocean-800 text-xs font-semibold mb-4">
          <i class="fa-solid fa-circle-question text-ocean-500"></i>
          <span>Pertanyaan Sering Diajukan</span>
        </div>
        <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 leading-tight">
          Pertanyaan Umum <span class="text-transparent bg-clip-text bg-gradient-to-r from-ocean-500 to-ocean-400">(FAQ)</span>
        </h2>
        <p class="text-slate-600 mt-4 max-w-xl mx-auto text-sm md:text-base">
          Temukan jawaban cepat atas pertanyaan seputar privasi, proses pelaporan, dan keamanan di platform SafeSpace.
        </p>
      </div>

      <!-- List FAQ Accordion -->
      <div class="space-y-4">

        <!-- FAQ Item 1 -->
        <div class="faq-item bg-slate-50 border border-slate-200/80 rounded-2xl overflow-hidden transition-all duration-300">
          <button class="faq-button w-full px-6 py-5 text-left font-semibold text-slate-800 flex justify-between items-center gap-4 hover:text-ocean-500 transition-colors">
            <span class="text-base md:text-lg flex items-center gap-3">
              <i class="fa-solid fa-user-shield text-ocean-500 text-base"></i>
              Apakah identitas pelapor dijamin rahasia?
            </span>
            <i class="fa-solid fa-chevron-down faq-icon text-ocean-500 transition-transform duration-300"></i>
          </button>
          <div class="faq-answer hidden px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-200/60 pt-4">
            Ya, identitas kamu sepenuhnya terlindungi. Sistem kami menyediakan opsi laporan anonim dan data pribadi hanya dapat diakses oleh tim Bimbingan Konseling (BK) yang berwenang.
          </div>
        </div>

        <!-- FAQ Item 2 -->
        <div class="faq-item bg-slate-50 border border-slate-200/80 rounded-2xl overflow-hidden transition-all duration-300">
          <button class="faq-button w-full px-6 py-5 text-left font-semibold text-slate-800 flex justify-between items-center gap-4 hover:text-ocean-500 transition-colors">
            <span class="text-base md:text-lg flex items-center gap-3">
              <i class="fa-solid fa-user-tie text-ocean-500 text-base"></i>
              Siapa yang akan menindaklanjuti laporan saya?
            </span>
            <i class="fa-solid fa-chevron-down faq-icon text-ocean-500 transition-transform duration-300"></i>
          </button>
          <div class="faq-answer hidden px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-200/60 pt-4">
            Laporan akan diteruskan ke Wali Kelas dan Guru Bimbingan Konseling (BK) setelah verifikasi awal. Penanganan dilakukan secara privat tanpa mempublikasikan identitas pelapor.
          </div>
        </div>

        <!-- FAQ Item 3 -->
        <div class="faq-item bg-slate-50 border border-slate-200/80 rounded-2xl overflow-hidden transition-all duration-300">
          <button class="faq-button w-full px-6 py-5 text-left font-semibold text-slate-800 flex justify-between items-center gap-4 hover:text-ocean-500 transition-colors">
            <span class="text-base md:text-lg flex items-center gap-3">
              <i class="fa-solid fa-globe text-ocean-500 text-base"></i>
              Bisakah melaporkan perundungan di media sosial?
            </span>
            <i class="fa-solid fa-chevron-down faq-icon text-ocean-500 transition-transform duration-300"></i>
          </button>
          <div class="faq-answer hidden px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-200/60 pt-4">
            Tentu saja. Kasus perundungan siber (cyberbullying) dapat dilaporkan dengan melampirkan bukti tangkapan layar saat mengisi formulir kejadian.
          </div>
        </div>

        <!-- FAQ Item 4 -->
        <div class="faq-item bg-slate-50 border border-slate-200/80 rounded-2xl overflow-hidden transition-all duration-300">
          <button class="faq-button w-full px-6 py-5 text-left font-semibold text-slate-800 flex justify-between items-center gap-4 hover:text-ocean-500 transition-colors">
            <span class="text-base md:text-lg flex items-center gap-3">
              <i class="fa-solid fa-robot text-ocean-500 text-base"></i>
              Bagaimana cara kerja fitur Tanya Jawab AI?
            </span>
            <i class="fa-solid fa-chevron-down faq-icon text-ocean-500 transition-transform duration-300"></i>
          </button>
          <div class="faq-answer hidden px-6 pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-200/60 pt-4">
            Asisten AI bekerja 24 jam memberikan dukungan awal, saran meredakan rasa cemas, dan panduan langkah sebelum kamu berkonsultasi langsung dengan konselor.
          </div>
        </div>

      </div>
    </div>
  </section>
  
  <!-- SECTION KONTAK -->
  <section id="kontak" class="bg-gradient-to-b from-slate-900 via-ocean-900 to-slate-900 text-slate-100 py-24 px-6 md:px-12 relative overflow-hidden border-t border-ocean-800/60">
      <!-- Ambient Blur -->
      <div class="absolute top-1/2 right-10 w-96 h-96 bg-ocean-500/10 rounded-full blur-[140px] pointer-events-none"></div>

      <div class="max-w-7xl mx-auto relative z-10">
          <!-- Judul Section -->
          <div class="text-center mb-16">
              <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-ocean-800/60 border border-ocean-500/30 text-ocean-400 text-xs font-semibold mb-4 backdrop-blur-sm">
                  <i class="fa-solid fa-headset"></i>
                  <span>Hubungi Kami</span>
              </div>
              <h2 class="text-3xl md:text-5xl font-extrabold text-white leading-tight">
                  Butuh Bantuan Lebih Lanjut? <br>
                  <span class="text-transparent bg-clip-text bg-gradient-to-r from-ocean-400 to-ocean-500">Kami Siap Mendengarkan</span>
              </h2>
              <p class="text-slate-400 mt-4 max-w-xl mx-auto text-sm md:text-base">
                  Jangan ragu untuk menghubungi tim konselor kami melalui berbagai kanal komunikasi terpercaya di bawah ini.
              </p>
          </div>

          <!-- Grid Card Kontak -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
              
              <!-- Card WhatsApp -->
              <div class="bg-ocean-800/30 border border-ocean-700/50 hover:border-emerald-500/50 rounded-2xl p-8 text-center backdrop-blur-sm transition-all duration-300 hover:-translate-y-2 group shadow-lg">
                  <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center text-3xl mx-auto mb-6 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all">
                      <i class="fa-brands fa-whatsapp"></i>
                  </div>
                  <h3 class="text-xl font-bold text-white mb-2">WhatsApp Konseling</h3>
                  <p class="text-xs text-slate-400 mb-6">Layanan chat langsung cepat tanggap dengan konselor piket Bimbingan Konseling.</p>
                  <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm transition-colors shadow-lg shadow-emerald-900/30">
                      <i class="fa-brands fa-whatsapp text-lg"></i>
                      Chat WhatsApp
                  </a>
              </div>

              <!-- Card Email -->
              <div class="bg-ocean-800/30 border border-ocean-700/50 hover:border-ocean-400/50 rounded-2xl p-8 text-center backdrop-blur-sm transition-all duration-300 hover:-translate-y-2 group shadow-lg">
                  <div class="w-16 h-16 rounded-2xl bg-ocean-500/10 text-ocean-400 border border-ocean-500/20 flex items-center justify-center text-3xl mx-auto mb-6 group-hover:scale-110 group-hover:bg-ocean-500 group-hover:text-white transition-all">
                      <i class="fa-solid fa-envelope"></i>
                  </div>
                  <h3 class="text-xl font-bold text-white mb-2">Email Layanan</h3>
                  <p class="text-xs text-slate-400 mb-6">Kirimkan berkas, aduan tertulis lengkap, atau surat kerjasama edukasi.</p>
                  <a href="mailto:bantuan@safespace.sch.id" class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-ocean-800 hover:bg-ocean-700 border border-ocean-500/40 text-ocean-300 hover:text-white font-semibold text-sm transition-colors">
                      <i class="fa-solid fa-paper-plane text-sm"></i>
                      Kirim Email
                  </a>
              </div>

              <!-- Card Ruang BK / Tatap Muka -->
              <div class="bg-ocean-800/30 border border-ocean-700/50 hover:border-ocean-accent/50 rounded-2xl p-8 text-center backdrop-blur-sm transition-all duration-300 hover:-translate-y-2 group shadow-lg">
                  <div class="w-16 h-16 rounded-2xl bg-ocean-accent/10 text-ocean-accent border border-ocean-accent/20 flex items-center justify-center text-3xl mx-auto mb-6 group-hover:scale-110 group-hover:bg-ocean-accent group-hover:text-white transition-all">
                      <i class="fa-solid fa-location-dot"></i>
                  </div>
                  <h3 class="text-xl font-bold text-white mb-2">Konseling Tatap Muka</h3>
                  <p class="text-xs text-slate-400 mb-6">Kunjungi Ruang Bimbingan Konseling Gedung Utama Lantai 2 Sekolah.</p>
                  <button id="btnJadwalBK" class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-ocean-800 hover:bg-ocean-700 border border-ocean-500/40 text-ocean-300 hover:text-white font-semibold text-sm transition-colors">
                      <i class="fa-solid fa-clock text-sm"></i>
                      Cek Jam Operasional
                  </button>
              </div>

          </div>
      </div>
  </section>

<!-- LOGIKA JQUERY INTERAKSI KONTAK -->
<script>
    $(document).ready(function() {
        $('#btnJadwalBK').click(function() {
            alert("Jadwal Konseling Ruang BK:\nSenin - Jumat: 07.00 - 15.30 WIB\nDiluar jam tersebut dapat menghubungi WhatsApp emergency.");
    });z
        });
</script>
  <!-- FOOTER SECTION -->
  <footer class="bg-ocean-900 border-t border-ocean-800/80 text-slate-300 pt-16 pb-8 px-6 md:px-12 relative overflow-hidden">
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-ocean-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="max-w-7xl mx-auto relative z-10">
      <!-- Grid Atas Footer -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-ocean-800/60">

        <!-- Kolom 1: Brand & Sosial Media -->
        <div class="space-y-4">
          <a href="#" class="text-2xl font-bold flex items-center gap-3 text-white group">
            <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-ocean-500 to-ocean-400 flex items-center justify-center shadow-lg shadow-ocean-500/40 group-hover:scale-110 transition-transform">
              <i class="fa-solid fa-shield-heart text-white text-lg"></i>
            </div>
            <span class="tracking-wide">Safe<span class="text-ocean-400">Space.</span></span>
          </a>
          <p class="text-sm text-slate-400 leading-relaxed">
            Platform digital pencegahan perundungan dan ruang aman bagi siswa untuk saling mendukung, melapor, serta mendapatkan bantuan profesional.
          </p>
          <!-- Icon Sosial Media -->
          <div class="flex items-center gap-3 pt-2">
            <a href="#" class="w-9 h-9 rounded-lg bg-ocean-800/60 border border-ocean-700/50 hover:bg-ocean-500 hover:text-white hover:border-ocean-500 transition-all flex items-center justify-center text-slate-300">
              <i class="fa-brands fa-instagram"></i>
            </a>
            <a href="#" class="w-9 h-9 rounded-lg bg-ocean-800/60 border border-ocean-700/50 hover:bg-ocean-500 hover:text-white hover:border-ocean-500 transition-all flex items-center justify-center text-slate-300">
              <i class="fa-brands fa-tiktok"></i>
            </a>
            <a href="#" class="w-9 h-9 rounded-lg bg-ocean-800/60 border border-ocean-700/50 hover:bg-ocean-500 hover:text-white hover:border-ocean-500 transition-all flex items-center justify-center text-slate-300">
              <i class="fa-brands fa-youtube"></i>
            </a>
          </div>
        </div>

        <!-- Kolom 2: Navigasi Cepat -->
        <div>
          <h4 class="text-white font-bold text-base mb-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-ocean-400"></span> Navigasi
          </h4>
          <ul class="space-y-2.5 text-sm">
            <li><a href="#hero" class="hover:text-ocean-400 transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-ocean-500"></i> Beranda</a></li>
            <li><a href="#tentang" class="hover:text-ocean-400 transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-ocean-500"></i> Tentang Kami</a></li>
            <li><a href="#" class="hover:text-ocean-400 transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-ocean-500"></i> Edukasi Anti-Bullying</a></li>
            <li><a href="#" class="hover:text-ocean-400 transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-ocean-500"></i> Portal Laporan Anonim</a></li>
            <li><a href="#" class="hover:text-ocean-400 transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-ocean-500"></i> Tanya Jawab AI</a></li>
          </ul>
        </div>

        <!-- Kolom 3: Layanan & Bantuan -->
        <div>
          <h4 class="text-white font-bold text-base mb-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-ocean-400"></span> Layanan & Bantuan
          </h4>
          <ul class="space-y-2.5 text-sm">
            <li><a href="#" class="hover:text-ocean-400 transition-colors flex items-center gap-2"><i class="fa-solid fa-headset text-[12px] text-ocean-500"></i> Konseling Psikologi</a></li>
            <li><a href="#" class="hover:text-ocean-400 transition-colors flex items-center gap-2"><i class="fa-solid fa-user-shield text-[12px] text-ocean-500"></i> Pendampingan Guru BK</a></li>
            <li><a href="#" class="hover:text-ocean-400 transition-colors flex items-center gap-2"><i class="fa-solid fa-book text-[12px] text-ocean-500"></i> Panduan Orang Tua</a></li>
            <li><a href="#" class="hover:text-ocean-400 transition-colors flex items-center gap-2"><i class="fa-solid fa-circle-question text-[12px] text-ocean-500"></i> Pertanyaan Sering Diajukan</a></li>
          </ul>
        </div>

        <!-- Kolom 4: Hotline Darurat -->
        <div>
          <h4 class="text-white font-bold text-base mb-4 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-ocean-accent"></span> Layanan Darurat
          </h4>
          <div class="p-4 rounded-xl bg-ocean-800/40 border border-ocean-700/50 space-y-3 backdrop-blur-sm">
            <p class="text-xs text-slate-400">Butuh bantuan penanganan cepat atau krisis?</p>
            <a href="tel:119" class="flex items-center gap-3 text-ocean-accent font-bold text-lg hover:underline">
              <i class="fa-solid fa-phone-volume animate-pulse"></i> Hotline 119 Ext 8
            </a>
            <p class="text-[11px] text-slate-400">Layanan Bantuan Kesehatan Jiwa & Perlindungan Anak</p>
          </div>
        </div>

      </div>

      <!-- Bawah Footer (Copyright & Hak Cipta) -->
      <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-400">
        <p>&copy; 2026 SafeSpace. All rights reserved. Dibuat dengan kepedulian untuk masa depan tanpa perundungan.</p>
        <div class="flex flex-wrap items-center gap-6">
          <a href="#" class="hover:text-ocean-400 transition-colors">Kebijakan Privasi</a>
          <a href="#" class="hover:text-ocean-400 transition-colors">Syarat & Ketentuan</a>
          <a href="#" class="hover:text-ocean-400 transition-colors">Pedoman Komunitas</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- LOGIKA JQUERY -->
  <script>
    $(document).ready(function() {
      $('.faq-button').click(function() {
          var $answer = $(this).next('.faq-answer');
          var $icon = $(this).find('.faq-icon');
          var $parent = $(this).parent('.faq-item');
  
          // Tutup FAQ lain yang sedang terbuka
          $('.faq-answer').not($answer).slideUp(300);
          $('.faq-icon').not($icon).removeClass('rotate-180');
          $('.faq-item').not($parent).removeClass('border-ocean-400/60 bg-ocean-800/60');
  
          // Toggle FAQ yang diklik
          $answer.slideToggle(300);
          $icon.toggleClass('rotate-180');
          $parent.toggleClass('border-ocean-400/60 bg-ocean-800/60');
      });
      // Efek scroll Navbar
      $(window).scroll(function() {
        if ($(this).scrollTop() > 50) {
          $('#navbar').addClass('bg-ocean-900/90 backdrop-blur-md shadow-lg border-b border-ocean-800');
          $('#navbar').removeClass('border-transparent py-4').addClass('py-3');
        } else {
          $('#navbar').removeClass('bg-ocean-900/90 backdrop-blur-md shadow-lg border-b border-ocean-800');
          $('#navbar').addClass('border-transparent py-4').removeClass('py-3');
        }
      });

      // Simulasi Klik Tombol Hero
      $('#btnLogin').click(function() {
        window.location.href = "{{ route('user.login') }}";
      });

      $('#btnAi').click(function() {
        alert('Membuka asisten AI untuk layanan konseling interaktif...');
      });
    });
  </script>
</body>

</html>