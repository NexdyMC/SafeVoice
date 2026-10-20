<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GuruBk extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_guru_bk';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'nip',
        'no_hp',
    ];

    /**
     * Get the user account for this counselor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get reports handled by this counselor.
     */
    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class, 'guru_bk_id');
    }

    /**
     * Get consultations participated in by this counselor.
     */
    public function konsultasi(): HasMany
    {
        return $this->hasMany(KonsultasiWaliKelas::class, 'guru_bk_id');
    }
}
