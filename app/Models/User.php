<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser; // 1. Import Interface
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 2. Implementasikan Interface FilamentUser
class User extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $table = 'tb_users';
    protected $primaryKey = 'id_user';
    protected $keyType = 'string';
    public $incrementing = false;
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

    // 3. Wajib: Logika siapa yang boleh akses Dashboard
    public function canAccessPanel(Panel $panel): bool
    {
        // Contoh: Hanya yang statusnya active dan role tertentu
        // return $this->is_active && $this->role === 'SUPER ADMIN';

        // Untuk tahap development, kita izinkan semua user aktif login:
        return $this->is_active;
    }

    // 4. Opsional: Beritahu Filament kolom mana yang jadi "Nama Tampilan"
    public function getFilamentName(): string
    {
        return $this->nama_lengkap;
    }
}
