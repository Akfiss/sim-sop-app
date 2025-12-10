<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class User extends Authenticatable implements FilamentUser, HasName, CanResetPassword
{
    use Notifiable, CanResetPasswordTrait;

    protected $table = 'tb_users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'id_user', 'username', 'email', 'password',
        'nama_lengkap', 'role', 'is_active', 'id_direktorat'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    // --- LOGIKA PINTU MASUK (MULTI PANEL) ---
    public function canAccessPanel(Panel $panel): bool
    {
        // 1. Cek apakah user aktif?
        if (!$this->is_active) {
            return false;
        }

        // 2. Logika Panel ADMIN (Super Admin & Verifikator)
        if ($panel->getId() === 'admin') {
            return $this->role === 'SUPER ADMIN';
        }

        // 3. Logika Panel PENGUSUL
        if ($panel->getId() === 'pengusul') {
            return $this->role === 'PENGUSUL';
        }

        // 4. Logika Panel DIREKSI
        if ($panel->getId() === 'direksi') {
            return $this->role === 'DIREKSI';
        }

        // 5. Logika Panel VERIFIKATOR
        if ($panel->getId() === 'verifikator') {
            return $this->role === 'VERIFIKATOR';
        }

        // Default: Tolak akses
        return false;
    }

    // --- AGAR NAMA MUNCUL DI POJOK KANAN ---
    public function getFilamentName(): string
    {
        return $this->nama_lengkap;
    }

    // --- RELASI KE TABEL LAIN ---

    // Relasi ke Direktorat (One to Many)
    public function direktorat()
    {
        return $this->belongsTo(Direktorat::class, 'id_direktorat', 'id_direktorat');
    }

    // Relasi ke Unit Kerja (Many to Many)
    public function units()
    {
        return $this->belongsToMany(UnitKerja::class, 'tb_unit_user', 'id_user', 'id_unit');
    }

    // Relasi ke Riwayat SOP (One to Many)
    public function riwayatSop()
    {
        return $this->hasMany(RiwayatSop::class, 'id_user', 'id_user');
    }
}
