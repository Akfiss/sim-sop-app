<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Direktorat;
use App\Models\UnitKerja;
use App\Models\User;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. BUAT 5 DIREKTORAT BERBEDA
        // ==========================================
        $listDirektorat = [
            ['id' => 'DIR01', 'nama' => 'Direktorat Utama'],
            ['id' => 'DIR02', 'nama' => 'Direktorat Pelayanan Medik & Keperawatan'],
            ['id' => 'DIR03', 'nama' => 'Direktorat SDM, Pendidikan & Penelitian'],
            ['id' => 'DIR04', 'nama' => 'Direktorat Keuangan & BMN'],
            ['id' => 'DIR05', 'nama' => 'Direktorat Perencanaan & Umum'],
        ];

        foreach ($listDirektorat as $d) {
            Direktorat::firstOrCreate(
                ['id_direktorat' => $d['id']],
                ['nama_direktorat' => $d['nama']]
            );
        }

        // ==========================================
        // 2. BUAT 10 UNIT KERJA BERBEDA (LINGKUNGAN RSUP)
        // ==========================================
        $listUnit = [
            ['id' => 'UN001', 'nama' => 'Instalasi Gawat Darurat (IGD)', 'dir' => 'DIR02'],
            ['id' => 'UN002', 'nama' => 'Instalasi Rawat Jalan', 'dir' => 'DIR02'],
            ['id' => 'UN003', 'nama' => 'Instalasi Farmasi', 'dir' => 'DIR02'],
            ['id' => 'UN004', 'nama' => 'Bagian SDM', 'dir' => 'DIR03'],
            ['id' => 'UN005', 'nama' => 'Bagian Diklat', 'dir' => 'DIR03'],
            ['id' => 'UN006', 'nama' => 'Bagian Anggaran', 'dir' => 'DIR04'],
            ['id' => 'UN007', 'nama' => 'Bagian Akuntansi', 'dir' => 'DIR04'],
            ['id' => 'UN008', 'nama' => 'Instalasi SIMRS (IT)', 'dir' => 'DIR05'],
            ['id' => 'UN009', 'nama' => 'Bagian Umum', 'dir' => 'DIR05'],
            ['id' => 'UN010', 'nama' => 'Instalasi Humas', 'dir' => 'DIR01'],
        ];

        foreach ($listUnit as $u) {
            UnitKerja::firstOrCreate(
                ['id_unit' => $u['id']],
                [
                    'nama_unit' => $u['nama'],
                    'id_direktorat' => $u['dir'] // Relasi ke direktorat
                ]
            );
        }

        // Password default untuk semua akun agar mudah diingat
        $passwordDefault = Hash::make('password123');

        // ==========================================
        // 3. AKUN SUPER ADMIN (NO UNIT, NO DIREKTORAT)
        // ==========================================
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@rsup.com',
                'password' => $passwordDefault,
                'nama_lengkap' => 'Super Administrator',
                'role' => 'SUPER ADMIN',
                'is_active' => true,
                'id_direktorat' => null, // Tidak terikat direktorat
            ]
        );

        // ==========================================
        // 4. AKUN VERIFIKATOR (NO UNIT, NO DIREKTORAT)
        // ==========================================
        User::firstOrCreate(
            ['username' => 'verifikator'],
            [
                'email' => 'verifikator@rsup.com',
                'password' => $passwordDefault,
                'nama_lengkap' => 'Tim Penjamin Mutu',
                'role' => 'VERIFIKATOR',
                'is_active' => true,
                'id_direktorat' => null, // Tidak terikat direktorat
            ]
        );

        // ==========================================
        // 5. AKUN DIREKSI (5 AKUN, 5 DIREKTORAT BERBEDA)
        // ==========================================
        foreach ($listDirektorat as $index => $d) {
            $num = $index + 1;
            User::firstOrCreate(
                ['username' => 'direksi' . $num],
                [
                    'email' => "direksi{$num}@rsup.com",
                    'password' => $passwordDefault,
                    'nama_lengkap' => "Direktur " . str_replace('Direktorat ', '', $d['nama']),
                    'role' => 'DIREKSI',
                    'is_active' => true,
                    'id_direktorat' => $d['id'], // Assign ke masing-masing direktorat
                ]
            );
        }

        // ==========================================
        // 6. AKUN PENGUSUL (3 AKUN, 3 UNIT BERBEDA)
        // ==========================================
        // Kita pilih 3 unit secara spesifik: IGD (UN001), Farmasi (UN003), dan SIMRS (UN008)
        $targetUnits = ['UN001', 'UN003', 'UN008'];

        foreach ($targetUnits as $index => $unitId) {
            $num = $index + 1;
            $unitData = UnitKerja::find($unitId);

            $userPengusul = User::firstOrCreate(
                ['username' => 'pengusul' . $num],
                [
                    'email' => "pengusul{$num}@rsup.com",
                    'password' => $passwordDefault,
                    'nama_lengkap' => "Ka. " . $unitData->nama_unit,
                    'role' => 'PENGUSUL',
                    'is_active' => true,
                    // Biasanya user mewarisi direktorat dari unit kerjanya
                    'id_direktorat' => $unitData->id_direktorat, 
                ]
            );

            // Assign User ke Unit Kerja (Tabel Pivot)
            // Menggunakan syncWithoutDetaching agar tidak duplikat jika seeder dijalankan ulang
            $userPengusul->units()->syncWithoutDetaching([$unitId]);
        }
    }
}