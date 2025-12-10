<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UnitKerja extends Model
{
    protected $table = 'tb_unit_kerja';
    protected $primaryKey = 'id_unit';
    protected $keyType = 'string';
    public $incrementing = false;

    // Kita matikan timestamp bawaan laravel di tabel ini jika di migration tidak ada created_at/updated_at
    public $timestamps = false;

    protected $fillable = ['id_unit', 'nama_unit', 'id_direktorat'];

    // Otomatis Generate ID saat Create (agar user tidak perlu isi ID manual)
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            // Contoh ID: UNIT-12345 (Random 5 string)
            if (empty($model->id_unit)) {
                $model->id_unit = 'UNIT-' . strtoupper(Str::random(5));
            }
        });
    }

    // Relasi kebalikan: Unit Kerja milik satu Direktorat
    public function direktorat()
    {
        return $this->belongsTo(Direktorat::class, 'id_direktorat', 'id_direktorat');
    }

    // Relasi Many-to-Many ke User (melalui tabel bridge tb_unit_user)
    public function users()
    {
        return $this->belongsToMany(User::class, 'tb_unit_user', 'id_unit', 'id_user');
    }

    // Relasi One-to-Many: Satu Unit bisa memiliki banyak Dokumen SOP
    public function dokumenSop()
    {
        return $this->hasMany(DokumenSop::class, 'id_unit_pemilik', 'id_unit');
    }
}
