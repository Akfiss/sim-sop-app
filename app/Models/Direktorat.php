<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Direktorat extends Model
{
    // 1. Definisikan nama tabel (karena tidak standar 'direktorats')
    protected $table = 'tb_direktorat';

    // 2. Definisikan Primary Key (karena bukan 'id')
    protected $primaryKey = 'id_direktorat';

    // 3. Tipe data PK adalah string (CHAR), bukan integer
    protected $keyType = 'string';

    // 4. Matikan auto-increment karena kita input manual kode-nya (misal: DIR01)
    public $incrementing = false;

    // 5. Matikan timestamp bawaan Laravel jika di migration tidak ada created_at/updated_at
    public $timestamps = false;

    // 6. Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = ['id_direktorat', 'nama_direktorat'];

    // Otomatis Generate ID saat Create (agar user tidak perlu isi ID manual)
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            // Contoh ID: DIR-12345 (Random 5 string)
            if (empty($model->id_direktorat)) {
                $model->id_direktorat = 'DIR-' . strtoupper(Str::random(5));
            }
        });
    }

    // 7. Relasi: Satu Direktorat punya banyak Unit Kerja
    public function unitKerja()
    {
        return $this->hasMany(UnitKerja::class, 'id_direktorat', 'id_direktorat');
    }
}
