<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatSop extends Model
{
    protected $table = 'tb_riwayat_sop';
    protected $primaryKey = 'id_riwayat';
    
    // Timestamps enabled (created_at & updated_at)
    public $timestamps = true;

    protected $fillable = [
        'catatan',
        'status_sop',
        'dokumen_path',
        'id_user',
        'id_sop',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who made this history entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Get the SOP document this history belongs to.
     */
    public function dokumenSop()
    {
        return $this->belongsTo(DokumenSop::class, 'id_sop', 'id_sop');
    }
}
