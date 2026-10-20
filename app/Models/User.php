<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tb_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the student profile associated with the user.
     */
    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    /**
     * Get the counselor (Guru BK) profile associated with the user.
     */
    public function guruBk(): HasOne
    {
        return $this->hasOne(GuruBk::class, 'user_id');
    }

    /**
     * Get the homeroom teacher (Wali Kelas) profile associated with the user.
     */
    public function waliKelas(): HasOne
    {
        return $this->hasOne(WaliKelas::class, 'user_id');
    }

    /**
     * Status logs modified by this user.
     */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(LaporanStatusLog::class, 'diubah_oleh');
    }

    /**
     * Check if user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a Guru BK.
     */
    public function isGuruBk(): bool
    {
        return $this->role === 'guru_bk';
    }

    /**
     * Check if user is a Wali Kelas.
     */
    public function isWaliKelas(): bool
    {
        return $this->role === 'wali_kelas';
    }

    /**
     * Check if user is a Siswa.
     */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }
}
