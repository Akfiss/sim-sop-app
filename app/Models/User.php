<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'tb_users';
    protected $primaryKey = 'id_user';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'username',
        'email',
        'password',
        'nama_lengkap',
        'role',
        'is_active',
        'id_direktorat'
    ];

    // Sembunyikan password saat data user dipanggil API/JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casting tipe data otomatis
    protected $casts = [
        'is_active' => 'boolean',
        'password' => 'hashed', // Fitur baru Laravel untuk otomatis hash password
    ];

    // Relasi ke Unit Kerja (Many to Many)
    public function units()
    {
        return $this->belongsToMany(UnitKerja::class, 'tb_unit_user', 'id_user', 'id_unit');
    }
}
