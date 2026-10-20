@extends('layouts.guest')

@section('title', 'Login Admin & BK - SafeVoice')

@section('body-class', 'bg-ocean-gradient min-h-screen flex items-center justify-center p-4')

@section('content')
<div class="bg-white rounded-3xl shadow-[0_15px_50px_rgb(0,0,0,0.4)] w-full max-w-md p-8 border-t-4 border-[#2563EB] transform transition-all duration-300 hover:shadow-2xl relative overflow-hidden">
    <!-- Efek Glow Dekoratif -->
    <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#38BDF8]/15 rounded-full blur-2xl pointer-events-none"></div>

    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl gradient-main text-white mb-4 shadow-md shadow-[#2563EB]/30">
            <i class="fa-solid fa-user-shield text-2xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Portal Guru BK & Admin</h2>
        <p class="text-sm text-gray-500 mt-1 font-medium">SafeVoice &bull; Manajemen & Pemantauan Sekolah</p>
    </div>

    <!-- Admin Login Form -->
    <form id="adminLoginForm" class="space-y-5" method="POST" action="#">
        @csrf
        <div>
            <label for="adminEmail" class="block text-sm font-semibold text-gray-700 mb-1.5">Email / NIP Petugas</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fa-solid fa-envelope text-gray-400"></i>
                </div>
                <input type="text" id="adminEmail" name="email" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl outline-none input-focus-effect transition-colors bg-dominant text-gray-700 font-medium text-sm" placeholder="admin@sekolah.sch.id atau NIP" required>
            </div>
        </div>

        <div>
            <label for="adminPassword" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="adminPassword" name="password" class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl outline-none input-focus-effect transition-colors bg-dominant text-gray-700 font-medium text-sm" placeholder="Masukkan password akun" required>
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center cursor-pointer text-gray-400 hover:text-[#2563EB] transition-colors" id="togglePassword">
                    <i class="fa-solid fa-eye" id="eyeIcon"></i>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between text-sm pt-1">
            <label class="flex items-center text-gray-600 cursor-pointer font-medium hover:text-gray-800 transition-colors">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#2563EB] focus:ring-[#2563EB] mr-2 w-4 h-4 accent-[#2563EB]">
                Ingat perangkat ini
            </label>
            <a href="#" class="text-[#2563EB] font-semibold hover:underline text-xs">Kendala akses?</a>
        </div>

        <!-- Tombol Login Admin -->
        <button type="submit" class="w-full gradient-main text-white font-bold py-3.5 rounded-xl hover:opacity-95 transition-all flex justify-center items-center gap-2 shadow-lg shadow-[#2563EB]/30 mt-2">
            <span>Masuk ke Dashboard</span>
            <i class="fa-solid fa-arrow-right"></i>
        </button>
    </form>

    <!-- Footer / Kembali ke Siswa -->
    <div class="text-center mt-6 pt-5 border-t border-gray-100 text-xs text-gray-500">
        Bukan petugas atau ingin melapor? <a href="{{ route('user.login') }}" class="text-[#2563EB] font-bold hover:underline">Masuk sebagai Siswa</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle Password
        $('#togglePassword').click(function() {
            let pwd = $('#adminPassword');
            let icon = $('#eyeIcon');
            if (pwd.attr('type') === 'password') {
                pwd.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                pwd.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Submit Simulasi Login Admin ke dashboard
        $('#adminLoginForm').submit(function(e) {
            let btn = $(this).find('button[type="submit"]');
            let originalHtml = btn.html();

            btn.html('<i class="fa-solid fa-spinner fa-spin"></i> Memverifikasi Kredensial...');
            btn.prop('disabled', true);
            btn.removeClass('gradient-main').addClass('gradient-accent text-gray-900');

            setTimeout(function() {
                btn.html('<i class="fa-solid fa-check"></i> Berhasil Masuk!');
                setTimeout(function() {
                    window.location.href = "{{ route('admin.dashboard') }}";
                }, 800);
            }, 1000);
        });
    });
</script>
@endpush
