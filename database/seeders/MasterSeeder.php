<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Direktorat;
use App\Models\UnitKerja;
use App\Models\User;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Data Direktorat
        // Kita pakai firstOrCreate agar tidak error jika di-seed berulang
        $dir = Direktorat::firstOrCreate(
            ['id_direktorat' => 'DIR01'],
            ['nama_direktorat' => 'Direktorat Utama']
        );

        // 2. Buat Data Unit Kerja
        $unit = UnitKerja::firstOrCreate(
            ['id_unit' => 'TI001'],
            [
                'nama_unit' => 'Teknologi Informasi',
                'id_direktorat' => 'DIR01'
            ]
        );

        // 3. Buat User Super Admin
        $user = User::firstOrCreate(
            ['email' => 'admin@rs.com'], // Cek berdasarkan email
            [
                'username' => 'superadmin',
                'password' => Hash::make('password123'), // Password default
                'nama_lengkap' => 'Super Administrator',
                'role' => 'SUPER ADMIN',
                'is_active' => true,
                'id_direktorat' => 'DIR01'
            ]
        );

        // 4. Assign User ke Unit Kerja (Isi tabel bridge tb_unit_user)
        // Cek dulu apakah sudah ada relasinya, jika belum, attach
        if (!$user->units()->where('tb_unit_kerja.id_unit', 'TI001')->exists()) {
            $user->units()->attach('TI001');
        }
    }
}
