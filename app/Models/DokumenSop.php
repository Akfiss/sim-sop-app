<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DokumenSop extends Model
{
    use SoftDeletes;

    protected $table = 'tb_dokumen_sop';
    protected $primaryKey = 'id_sop';
    protected $keyType = 'string';
    public $incrementing = false;

    // Pastikan timestamps menyala karena di migration ada created_at
    public $timestamps = true;

    // Property sementara untuk menampung pesan revisi (tidak disimpan di database tabel ini)
    public $catatan_revisi = null;

    protected $fillable = [
        'id_sop', 'nomor_sk', 'judul_sop', 'kategori_sop', 'is_all_units',
        'file_path', 'tgl_pengesahan', 'tgl_berlaku',
        'tgl_review_berikutnya', 'tgl_kadaluarsa', 'status',
        'id_unit_pemilik', 'created_by', 'updated_by', 'deleted_by'
    ];

    // Otomatis Generate ID saat Create (agar user tidak perlu isi ID manual)
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            // Contoh ID: SOP-12345 (Random 5 string)
            if (empty($model->id_sop)) {
                $model->id_sop = 'SOP-' . strtoupper(Str::random(5));
            }
        });
    }

    // Relasi Pemilik (One to Many)
    public function unitPemilik()
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit_pemilik', 'id_unit');
    }

    // Relasi Pembuat (One to Many)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    // Relasi SOP AP - Unit Terkait (Many to Many)
    // Lewat tabel pivot: tb_sop_unit_terkait
    public function unitTerkait()
    {
        return $this->belongsToMany(
            UnitKerja::class,
            'tb_sop_unit_terkait',
            'id_sop',
            'id_unit'
        );
    }

    // Relasi One-to-Many: Satu SOP memiliki banyak riwayat
    public function riwayat()
    {
        return $this->hasMany(RiwayatSop::class, 'id_sop', 'id_sop')
            ->orderBy('created_at', 'desc');
    }
}
