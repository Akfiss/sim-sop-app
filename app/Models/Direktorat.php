<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    // 7. Relasi: Satu Direktorat punya banyak Unit Kerja
    public function unitKerja()
    {
        return $this->hasMany(UnitKerja::class, 'id_direktorat', 'id_direktorat');
    }
}
