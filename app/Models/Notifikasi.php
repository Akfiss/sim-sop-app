<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'tb_notifikasi';
    protected $primaryKey = 'id_notifikasi';
    public $timestamps = false; // Kita handle manual sesuai PDM (hanya created_at)

    protected $fillable = [
        'judul', 'pesan', 'is_read', 'created_at', 'id_user', 'id_sop'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke SOP (Opsional, jika ingin klik notif langsung ke SOP)
    public function sop()
    {
        return $this->belongsTo(DokumenSop::class, 'id_sop', 'id_sop');
    }
}
