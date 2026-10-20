<?php

namespace App\Http\Controllers;

use App\Models\GuruBk;
use App\Models\KonsultasiWaliKelas;
use App\Models\Laporan;
use App\Models\LaporanStatusLog;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GuruBkController extends Controller
{
    /**
     * Menu 1: Verifikasi & Disposisi Laporan (Flowchart 1 & 4).
     */
    public function verifikasi(Request $request, Laporan $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'keputusan' => 'required|in:teruskan,tolak',
            'alasan_tolak' => 'nullable|string|max:500',
            'guru_bk_id' => 'nullable|exists:tb_guru_bk,id',
            'wali_kelas_id' => 'nullable|exists:tb_wali_kelas,id',
        ]);

        $currentUser = auth()->user() ?? User::where('role', 'guru_bk')->first() ?? User::where('role', 'admin')->first();
        $guruBk = $currentUser?->guruBk ?? GuruBk::first();

        if ($validated['keputusan'] === 'tolak') {
            $alasan = $validated['alasan_tolak'] ?: 'Laporan ditolak oleh Guru BK karena tidak memenuhi kriteria atau bukti tidak memadai.';

            $laporan->update([
                'status' => 'ditolak',
                'guru_bk_id' => $guruBk?->id,
            ]);

            LaporanStatusLog::create([
                'laporan_id' => $laporan->id,
                'status_lama' => 'pending',
                'status_baru' => 'ditolak',
                'umpan_balik' => $alasan,
                'diubah_oleh' => $currentUser?->id ?? 1,
                'created_at' => now(),
            ]);

            return back()->with('success', "Laporan {$laporan->kode_laporan} berhasil ditolak. Alasan penolakan telah dikirim ke siswa pelapor.");
        }

        // Keputusan: Teruskan ke Wali Kelas (Layak Diproses)
        $targetGuruBkId = $validated['guru_bk_id'] ?? $guruBk?->id;

        $laporan->update([
            'status' => 'diproses',
            'guru_bk_id' => $targetGuruBkId,
        ]);

        LaporanStatusLog::create([
            'laporan_id' => $laporan->id,
            'status_lama' => 'pending',
            'status_baru' => 'diproses',
            'umpan_balik' => 'Laporan telah diverifikasi layak oleh Guru BK dan diteruskan ke Wali Kelas untuk pembahasan kronologi bersama.',
            'diubah_oleh' => $currentUser?->id ?? 1,
            'created_at' => now(),
        ]);

        return back()->with('success', "Laporan {$laporan->kode_laporan} diverifikasi dan diteruskan ke Wali Kelas.");
    }

    /**
     * Menu 2: Ruang Diskusi & Konsultasi BK - Wali Kelas (Flowchart 2).
     */
    public function simpanKonsultasi(Request $request, Laporan $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'hasil_diskusi' => 'required|string',
            'kesimpulan' => 'required|in:tindak_lanjut_bk,panggilan_orang_tua,mediasi,selesai',
            'informasi_cukup' => 'required|in:ya,tidak',
            'status_fakta' => 'nullable|in:fakta,hoaks',
            'wali_kelas_id' => 'nullable|exists:tb_wali_kelas,id',
            'guru_bk_id' => 'nullable|exists:tb_guru_bk,id',
        ]);

        $currentUser = auth()->user() ?? User::where('role', 'guru_bk')->first() ?? User::where('role', 'admin')->first();
        $guruBk = $currentUser?->guruBk ?? GuruBk::first();
        $waliKelasId = $validated['wali_kelas_id']
            ?? $laporan->pelapor?->kelas?->wali_kelas_id
            ?? WaliKelas::first()?->id;

        // 1. Simpan ke tb_konsultasi_wali_kelas
        KonsultasiWaliKelas::create([
            'laporan_id' => $laporan->id,
            'guru_bk_id' => $validated['guru_bk_id'] ?? ($guruBk?->id ?? 1),
            'wali_kelas_id' => $waliKelasId ?? 1,
            'hasil_diskusi' => $validated['hasil_diskusi'],
            'kesimpulan' => $validated['kesimpulan'],
            'tanggal_diskusi' => now(),
        ]);

        // 2. Evaluasi Flowchart Decision
        if ($validated['informasi_cukup'] === 'tidak') {
            LaporanStatusLog::create([
                'laporan_id' => $laporan->id,
                'status_lama' => 'diproses',
                'status_baru' => 'diproses',
                'umpan_balik' => 'Diskusi lanjutan dijadwalkan: Bukti dan kronologi masih memerlukan penelusuran fakta tambahan.',
                'diubah_oleh' => $currentUser?->id ?? 1,
                'created_at' => now(),
            ]);

            return back()->with('success', 'Catatan konsultasi disimpan. Status ditandai untuk diskusi lanjutan.');
        }

        // Jika informasi cukup, cek kesimpulan fakta vs hoaks
        if (($validated['status_fakta'] ?? '') === 'hoaks') {
            $laporan->update(['status' => 'ditolak']);

            LaporanStatusLog::create([
                'laporan_id' => $laporan->id,
                'status_lama' => 'diproses',
                'status_baru' => 'ditolak',
                'umpan_balik' => 'Hasil koordinasi investigasi BK dan Wali Kelas menyimpulkan laporan tidak terbukti (hoaks). Kasus ditutup.',
                'diubah_oleh' => $currentUser?->id ?? 1,
                'created_at' => now(),
            ]);

            return back()->with('success', "Kasus {$laporan->kode_laporan} disimpulkan hoaks dan status diubah menjadi ditolak.");
        }

        // Kesimpulan Fakta -> Lanjut ke tindak lanjut
        LaporanStatusLog::create([
            'laporan_id' => $laporan->id,
            'status_lama' => 'diproses',
            'status_baru' => 'diproses',
            'umpan_balik' => 'Investigasi BK & Wali Kelas membuktikan laporan adalah fakta. Kasus dilanjutkan ke tindakan penanganan.',
            'diubah_oleh' => $currentUser?->id ?? 1,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Hasil konsultasi berhasil disimpan. Kasus dinyatakan valid dan siap ditindaklanjuti.');
    }

    /**
     * Menu 3: Selesaikan Tindak Lanjut Penanganan (Flowchart 3).
     */
    public function selesaikanTindakLanjut(Request $request, Laporan $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'tindakan' => 'required|string|max:100',
            'catatan' => 'required|string|max:500',
        ]);

        $currentUser = auth()->user() ?? User::where('role', 'guru_bk')->first() ?? User::where('role', 'admin')->first();

        $laporan->update([
            'status' => 'selesai',
            'konfirmasi_siswa' => 'menunggu',
            'konfirmasi_at' => null,
        ]);

        $umpanBalik = "Tindakan penanganan [{$validated['tindakan']}] telah tuntas dilaksanakan. {$validated['catatan']}";

        LaporanStatusLog::create([
            'laporan_id' => $laporan->id,
            'status_lama' => 'diproses',
            'status_baru' => 'selesai',
            'umpan_balik' => $umpanBalik,
            'diubah_oleh' => $currentUser?->id ?? 1,
            'created_at' => now(),
        ]);

        return back()->with('success', "Kasus {$laporan->kode_laporan} berhasil diselesaikan. Menunggu respon konfirmasi rasa aman dari siswa.");
    }

    /**
     * Menu 3: Kembalikan Status ke Diproses jika Siswa Mengabarkan 'Masih Terjadi' (Flowchart 3).
     */
    public function kembalikanKeProses(Request $request, Laporan $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        $currentUser = auth()->user() ?? User::where('role', 'guru_bk')->first() ?? User::where('role', 'admin')->first();

        $laporan->update([
            'status' => 'diproses',
            'konfirmasi_siswa' => 'menunggu',
        ]);

        $pesan = $validated['catatan'] ?: 'Kasus kembali diproses untuk pembahasan ulang karena siswa mengonfirmasi bahwa perundungan masih berlanjut.';

        LaporanStatusLog::create([
            'laporan_id' => $laporan->id,
            'status_lama' => 'selesai',
            'status_baru' => 'diproses',
            'umpan_balik' => $pesan,
            'diubah_oleh' => $currentUser?->id ?? 1,
            'created_at' => now(),
        ]);

        return back()->with('success', "Kasus {$laporan->kode_laporan} berhasil dikembalikan ke dalam proses penanganan tim BK.");
    }
}
