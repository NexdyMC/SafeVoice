@extends('layouts.guest')

@section('title', 'Daftar Akun Siswa - SafeVoice Ocean Dusk Modern')

@section('content')
<div class="bg-white rounded-2xl shadow-[0_12px_40px_rgb(0,0,0,0.3)] w-full max-w-md p-8 border-t-4 border-[#2563EB] transform transition-all duration-300 hover:shadow-2xl relative overflow-hidden my-6">
    <!-- Efek Glow Dekoratif di Sudut Atas -->
    <div class="absolute -top-12 -right-12 w-32 h-32 bg-[#38BDF8]/15 rounded-full blur-2xl pointer-events-none"></div>

    <!-- Header -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full gradient-main text-white mb-3 shadow-md shadow-[#2563EB]/30">
            <i class="fa-solid fa-user-plus text-2xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800">Buat Akun Baru</h2>
        <p class="text-sm text-gray-500 mt-1 font-medium">Daftar sekarang dengan sistem <span class="text-gradient-accent font-bold">Ocean Dusk</span></p>
    </div>

    <!-- Register Form -->
    <form id="registerForm" class="space-y-4" method="POST" action="#">
        @csrf
        <div>
            <label for="fullname" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fa-solid fa-id-card text-gray-400"></i>
                </div>
                <input type="text" id="fullname" name="nama_lengkap" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl outline-none input-focus-effect transition-colors bg-dominant text-gray-700 font-medium text-sm" placeholder="Masukkan nama lengkap" required>
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fa-solid fa-envelope text-gray-400"></i>
                </div>
                <input type="email" id="email" name="email" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl outline-none input-focus-effect transition-colors bg-dominant text-gray-700 font-medium text-sm" placeholder="nama@email.com" required>
            </div>
        </div>

        <div>
            <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">NIS / Username</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fa-solid fa-user text-gray-400"></i>
                </div>
                <input type="text" id="username" name="username" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl outline-none input-focus-effect transition-colors bg-dominant text-gray-700 font-medium text-sm" placeholder="Nomor Induk Siswa" required>
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="password" name="password" class="w-full pl-10 pr-10 py-2.5 border border-gray-200 rounded-xl outline-none input-focus-effect transition-colors bg-dominant text-gray-700 font-medium text-sm" placeholder="Buat password minimal 8 karakter" required>
                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center cursor-pointer text-gray-400 hover:text-[#2563EB] transition-colors" id="togglePassword">
                    <i class="fa-solid fa-eye" id="eyeIcon"></i>
                </div>
            </div>
        </div>

        <div class="flex items-center text-sm pt-1">
            <label class="flex items-center text-gray-600 cursor-pointer font-medium text-xs">
                <input type="checkbox" class="rounded border-gray-300 text-[#2563EB] focus:ring-[#2563EB] mr-2 w-4 h-4 accent-[#2563EB]" required>
                Saya menyetujui <a href="#" class="text-gradient-accent ml-1 font-bold hover:underline">Syarat & Kebijakan Privasi</a>
            </label>
        </div>

        <!-- Tombol Register Utama -->
        <button type="submit" class="w-full gradient-main text-white font-semibold py-3 rounded-xl hover:opacity-95 transition-all flex justify-center items-center gap-2 group shadow-lg shadow-[#2563EB]/25 mt-2">
            <span>Daftar Sekarang</span>
            <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
        </button>
    </form>

    <!-- Divider -->
    <div class="mt-6 mb-5 relative flex items-center">
        <div class="flex-grow border-t border-gray-100"></div>
        <span class="flex-shrink-0 mx-4 text-gray-400 text-xs font-semibold uppercase tracking-wider">Atau daftar dengan</span>
        <div class="flex-grow border-t border-gray-100"></div>
    </div>

    <!-- Social Register Buttons -->
    <div class="space-y-3">
        <button type="button" class="w-full flex items-center justify-center gap-3 py-2.5 border border-gray-200 rounded-xl text-gray-600 bg-white hover:bg-gray-50 transition-colors font-medium hover:border-gray-300 shadow-sm text-sm">
            <i class="fa-brands fa-google text-red-500 text-lg"></i>
            <span>Daftar dengan Google</span>
        </button>
    </div>

    <!-- Footer Link to Login -->
    <div class="text-center mt-6 text-sm text-gray-500">
        Sudah punya akun? <a href="{{ route('user.login') }}" class="text-gradient-accent font-bold hover:underline">Masuk di sini</a>
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
        $('#registerForm').submit(function(e) {
            let btn = $(this).find('button[type="submit"]');
            let originalHtml = btn.html();
            
            btn.html('<i class="fa-solid fa-spinner fa-spin"></i> Memproses Pendaftaran...');
            btn.prop('disabled', true);
            btn.removeClass('gradient-main').addClass('gradient-accent text-gray-900 font-bold');

            setTimeout(function() {
                btn.html('<i class="fa-solid fa-check"></i> Pendaftaran Berhasil!');
                setTimeout(function() {
                    window.location.href = "{{ route('user.login') }}";
                }, 800);
            }, 1000);
        });
    });
</script>
@endpush
