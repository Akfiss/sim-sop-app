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

    /**
     * LOGIKA KEAMANAN PINTU MASUK
     * Method ini dipanggil otomatis oleh Filament setiap kali user membuka halaman panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Jika user tidak aktif, tolak semua akses
        // (Pastikan kolom 'is_active' ada di tabel Anda, atau hapus baris ini jika tidak pakai)
        if ($this->is_active === 0) {
            return false;
        }

        // Ambil ID Panel yang sedang dicoba diakses
        // Pastikan ID panel di PanelProvider Anda sesuai: 'admin', 'pengusul', 'verifikator', 'direksi'
        $panelId = $panel->getId();
        $role = $this->role; // ENUM: 'SUPER ADMIN', 'PENGUSUL', 'VERIFIKATOR', 'DIREKSI'

        // Cocokkan ID Panel dengan Role User
        return match($panelId) {
            'admin'       => $role === 'SUPER ADMIN',
            'pengusul'    => $role === 'PENGUSUL',
            'verifikator' => $role === 'VERIFIKATOR',
            'direksi'     => $role === 'DIREKSI',
            default       => false, // Panel tidak dikenal, tolak akses
        };
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

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }
}
