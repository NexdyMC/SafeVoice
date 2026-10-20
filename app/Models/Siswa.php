<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_siswa';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'nis',
        'kelas_id',
        'jenis_kelamin',
    ];

    /**
     * Get the user account for this student.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the class of this student.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Get the reports filed by this student as the reporter.
     */
    public function laporanDibuat(): HasMany
    {
        return $this->hasMany(Laporan::class, 'pelapor_id');
    }

    /**
     * Get the reports where this student is the victim.
     */
    public function laporanSebagaiKorban(): HasMany
    {
        return $this->hasMany(Laporan::class, 'korban_id');
    }
}
