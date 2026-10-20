@extends('layouts.guest')

@section('title', 'Login - SafeVoice Ocean Dusk Modern')

@section('content')
<div class="bg-white rounded-2xl shadow-[0_12px_40px_rgb(0,0,0,0.3)] w-full max-w-md p-8 border-t-4 border-[#2563EB] transform transition-all duration-300 hover:shadow-2xl relative overflow-hidden">
    <!-- Efek Glow Dekoratif di Sudut Atas -->
    <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#38BDF8]/15 rounded-full blur-2xl pointer-events-none"></div>

    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full gradient-main text-white mb-4 shadow-md shadow-[#2563EB]/30">
            <i class="fa-solid fa-shield-halved text-2xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Selamat Datang</h2>
        <p class="text-sm text-gray-500 mt-1.5 font-medium">Masuk dengan sistem <span class="text-gradient-accent font-bold">Ocean Dusk</span></p>
    </div>

    <!-- Login Form -->
    <form id="loginForm" class="space-y-5" method="POST" action="#">
        @csrf
        <div>
            <label for="username" class="block text-sm font-semibold text-gray-700 mb-1.5">Username / NIS</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fa-solid fa-user text-gray-400"></i>
                </div>
                <input type="text" id="username" name="username" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl outline-none input-focus-effect transition-colors bg-dominant text-gray-700 font-medium" placeholder="Masukkan username atau NIS" required>
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="password" name="password" class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl outline-none input-focus-effect transition-colors bg-dominant text-gray-700 font-medium" placeholder="Masukkan password" required>
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center cursor-pointer text-gray-400 hover:text-[#2563EB] transition-colors" id="togglePassword">
                    <i class="fa-solid fa-eye" id="eyeIcon"></i>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between text-sm pt-1">
            <label class="flex items-center text-gray-600 cursor-pointer font-medium hover:text-gray-800 transition-colors">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#2563EB] focus:ring-[#2563EB] mr-2 w-4 h-4 accent-[#2563EB]">
                Ingat saya
            </label>
            <a href="#" class="text-gradient-accent hover:opacity-80 font-bold transition-opacity">Lupa Password?</a>
        </div>

        <!-- Tombol Login Utama -->
        <button type="submit" class="w-full gradient-main text-white font-semibold py-3 rounded-xl hover:opacity-95 transition-all flex justify-center items-center gap-2 group shadow-lg shadow-[#2563EB]/25 mt-2">
            <span>Masuk Sekarang</span>
            <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
        </button>
    </form>

    <!-- Divider -->
    <div class="mt-8 mb-6 relative flex items-center">
        <div class="flex-grow border-t border-gray-100"></div>
        <span class="flex-shrink-0 mx-4 text-gray-400 text-xs font-semibold uppercase tracking-wider">Atau masuk dengan</span>
        <div class="flex-grow border-t border-gray-100"></div>
    </div>

    <!-- Social Login Buttons -->
    <div class="space-y-3 grid grid-cols-2 gap-4">
        <button type="button" class="w-full flex items-center justify-center gap-3 border border-gray-200 rounded-xl text-gray-600 bg-white hover:bg-gray-50 transition-colors font-medium hover:border-gray-300 shadow-sm text-sm">
            <i class="fa-brands fa-google text-red-500 text-lg"></i>
        </button>
        <button type="button" class="w-full flex items-center justify-center gap-3 border border-gray-200 rounded-xl text-gray-600 bg-white hover:bg-gray-50 transition-colors font-medium hover:border-gray-300 shadow-sm text-sm">
            <i class="fa-brands fa-github text-gray-900 text-lg"></i>
        </button>
    </div>

    <!-- Footer Links -->
    <div class="text-center mt-6 text-sm text-gray-500 space-y-1">
        <div>
            Belum punya akun? <a href="{{ route('user.register') }}" class="text-gradient-accent font-bold hover:underline">Daftar di sini</a>
        </div>
        <div class="text-xs pt-1">
            Petugas BK / Admin? <a href="{{ route('admin.login') }}" class="text-[#2563EB] font-bold hover:underline">Masuk Portal Admin</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle Password Visibility
        $('#togglePassword').click(function() {
            let pwdInput = $('#password');
            let icon = $('#eyeIcon');
            
            if (pwdInput.attr('type') === 'password') {
                pwdInput.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash').addClass('text-[#2563EB]').removeClass('text-gray-400');
            } else {
                pwdInput.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye').addClass('text-gray-400').removeClass('text-[#2563EB]');
            }
        });

        // Efek interaktif submit
        $('#loginForm').submit(function(e) {
            let btn = $(this).find('button[type="submit"]');
            let originalHtml = btn.html();
            
            btn.html('<i class="fa-solid fa-spinner fa-spin"></i> Memproses...');
            btn.prop('disabled', true);
            btn.removeClass('gradient-main').addClass('gradient-accent text-gray-900 font-bold');

            setTimeout(function() {
                btn.html('<i class="fa-solid fa-check"></i> Berhasil!');
                setTimeout(function() {
                    window.location.href = "{{ route('user.beranda') }}";
                }, 800);
            }, 1000);
        });
    });
</script>
@endpush
