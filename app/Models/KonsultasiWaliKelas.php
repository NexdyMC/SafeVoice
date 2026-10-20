<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KonsultasiWaliKelas extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_konsultasi_wali_kelas';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'laporan_id',
        'guru_bk_id',
        'wali_kelas_id',
        'hasil_diskusi',
        'kesimpulan',
        'tanggal_diskusi',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_diskusi' => 'datetime',
        ];
    }

    /**
     * Get the report being consulted on.
     */
    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }

    /**
     * Get the counselor in this consultation.
     */
    public function guruBk(): BelongsTo
    {
        return $this->belongsTo(GuruBk::class, 'guru_bk_id');
    }

    /**
     * Get the homeroom teacher in this consultation.
     */
    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(WaliKelas::class, 'wali_kelas_id');
    }
}
