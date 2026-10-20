<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruBkController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

// Halaman Landing Page Utama (Home)
Route::get('/', function () {
    return view('home');
})->name('home');

// Area Siswa (User / Ruang Aman)
Route::prefix('user')->name('user.')->group(function () {
    Route::view('/login', 'user.login')->name('login');
    Route::view('/register', 'user.register')->name('register');
    Route::get('/beranda', [LaporanController::class, 'beranda'])->name('beranda');
    Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
    Route::get('/riwayat', [LaporanController::class, 'riwayat'])->name('riwayat');
    Route::post('/laporan/{laporan}/konfirmasi', [LaporanController::class, 'konfirmasi'])->name('laporan.konfirmasi');
    Route::get('/profile', [LaporanController::class, 'profile'])->name('profile');
});

// Area Admin & Guru BK
Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.login')->name('index');
    Route::view('/login', 'admin.login')->name('login');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::post('/kelas', [AdminController::class, 'storeKelas'])->name('kelas.store');
    Route::post('/laporan/{laporan}', [AdminController::class, 'updateLaporan'])->name('laporan.update');

    // 3 Alur Kerja Guru BK Berdasarkan Flowchart
    Route::post('/bk/laporan/{laporan}/verifikasi', [GuruBkController::class, 'verifikasi'])->name('bk.verifikasi');
    Route::post('/bk/laporan/{laporan}/konsultasi', [GuruBkController::class, 'simpanKonsultasi'])->name('bk.konsultasi');
    Route::post('/bk/laporan/{laporan}/selesai', [GuruBkController::class, 'selesaikanTindakLanjut'])->name('bk.selesai');
    Route::post('/bk/laporan/{laporan}/kembalikan', [GuruBkController::class, 'kembalikanKeProses'])->name('bk.kembalikan');
});
