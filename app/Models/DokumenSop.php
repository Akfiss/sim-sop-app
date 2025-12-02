<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini karena ada softDeletes di migration

class DokumenSop extends Model
{
    use SoftDeletes; // Aktifkan fitur soft delete

    protected $table = 'tb_dokumen_sop';
    protected $primaryKey = 'id_sop';
    protected $keyType = 'string';
    public $incrementing = false;

    // CATATAN PENTING:
    // Di migration SOP, Anda menggunakan $table->timestamps().
    // Jadi, JANGAN set $timestamps = false di sini. Biarkan default (true).

    protected $fillable = [
        'id_sop',
        'nomor_sk',
        'judul_sop',
        'kategori_sop',
        'file_path',
        'tgl_pengesahan',
        'tgl_berlaku',
        'tgl_review_berikutnya',
        'tgl_kadaluarsa',
        'status',
        'id_unit_pemilik',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // Relasi ke Unit Pemilik
    public function unitPemilik()
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit_pemilik', 'id_unit');
    }
}
