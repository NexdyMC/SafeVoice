<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laporan extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_laporan';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kode_laporan',
        'pelapor_id',
        'korban_id',
        'terlapor',
        'jenis_bullying',
        'deskripsi',
        'tanggal_kejadian',
        'lokasi_kejadian',
        'is_anonim',
        'status',
        'guru_bk_id',
        'konfirmasi_siswa',
        'konfirmasi_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_kejadian' => 'date',
            'is_anonim' => 'boolean',
            'konfirmasi_at' => 'datetime',
        ];
    }

    /**
     * Get the student who reported the incident.
     */
    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'pelapor_id');
    }

    /**
     * Get the student victim of the incident.
     */
    public function korban(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'korban_id');
    }

    /**
     * Get the counselor assigned to handle this report.
     */
    public function guruBk(): BelongsTo
    {
        return $this->belongsTo(GuruBk::class, 'guru_bk_id');
    }

    /**
     * Get the status change history logs for this report.
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(LaporanStatusLog::class, 'laporan_id');
    }

    /**
     * Get the file attachments/evidence for this report.
     */
    public function bukti(): HasMany
    {
        return $this->hasMany(BuktiLaporan::class, 'laporan_id');
    }

    /**
     * Get consultations between Guru BK and Wali Kelas for this report.
     */
    public function konsultasi(): HasMany
    {
        return $this->hasMany(KonsultasiWaliKelas::class, 'laporan_id');
    }
}
