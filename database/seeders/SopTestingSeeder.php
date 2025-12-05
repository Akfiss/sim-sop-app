<?php

namespace Database\Seeders;

use App\Models\DokumenSop;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SopTestingSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil User dan Unit Dummy (Pastikan table user/unit tidak kosong)
        // Sebaiknya pakai user yang biasa Anda pakai login pengusul
        $user = User::where('role', 'PENGUSUL')->first() ?? User::first();
        $unit = UnitKerja::first();

        if (!$user || !$unit) {
            $this->command->error('Harap isi data User dan Unit Kerja terlebih dahulu!');
            return;
        }

        $this->command->info('Membuat data testing SOP untuk User: ' . $user->username);

        // SKENARIO 1: SOP BARU (DALAM REVIEW)
        // Harapan: Tombol Edit/Delete NYALA. Warning Review MATI.
        DokumenSop::create([
            'id_sop' => 'TEST-001',
            'judul_sop' => '[TEST] SOP Baru Upload',
            'kategori_sop' => 'SOP',
            'status' => 'DALAM REVIEW',
            'created_by' => $user->id_user,
            'id_unit_pemilik' => $unit->id_unit,
            'file_path' => 'dummy.pdf', // File dummy
        ]);

        // SKENARIO 2: SOP AKTIF AMAN (Baru Disetujui Kemarin)
        // Harapan: Tombol Edit/Delete MATI (Disabled). Warning Review MATI.
        DokumenSop::create([
            'id_sop' => 'TEST-002',
            'judul_sop' => '[TEST] SOP Aktif (Masih Lama)',
            'kategori_sop' => 'SOP',
            'status' => 'AKTIF',
            'tgl_pengesahan' => now()->subDays(1),
            'tgl_berlaku' => now()->subDays(1),
            'tgl_review_berikutnya' => now()->addYear(), // Masih 1 tahun lagi
            'tgl_kadaluarsa' => now()->addYears(3),
            'created_by' => $user->id_user,
            'id_unit_pemilik' => $unit->id_unit,
            'file_path' => 'dummy.pdf',
        ]);

        // SKENARIO 3: SOP AKTIF (H-15 REVIEW TAHUNAN)
        // Harapan: Tombol Edit NYALA. Icon Warning Review MUNCUL.
        DokumenSop::create([
            'id_sop' => 'TEST-003',
            'judul_sop' => '[TEST] SOP Warning Review (H-15)',
            'kategori_sop' => 'SOP',
            'status' => 'AKTIF',
            'tgl_pengesahan' => now()->subMonths(11), // Sudah 11 bulan
            'tgl_berlaku' => now()->subMonths(11),
            'tgl_review_berikutnya' => now()->addDays(15), // <--- 15 HARI LAGI REVIEW (Masuk H-30)
            'tgl_kadaluarsa' => now()->addYears(2),
            'created_by' => $user->id_user,
            'id_unit_pemilik' => $unit->id_unit,
            'file_path' => 'dummy.pdf',
        ]);

        // SKENARIO 4: SOP AKTIF (H-5 EXPIRED TOTAL)
        // Harapan: Tombol Edit NYALA. Icon Warning Review MUNCUL. Notif Expired.
        DokumenSop::create([
            'id_sop' => 'TEST-004',
            'judul_sop' => '[TEST] SOP Warning Expired (H-5)',
            'kategori_sop' => 'SOP',
            'status' => 'AKTIF',
            'tgl_pengesahan' => now()->subYears(3)->addDays(5),
            'tgl_berlaku' => now()->subYears(3)->addDays(5),
            'tgl_review_berikutnya' => now()->subYear(), // Sudah lewat (tapi ketimpa expired)
            'tgl_kadaluarsa' => now()->addDays(5), // <--- 5 HARI LAGI MATI TOTAL
            'created_by' => $user->id_user,
            'id_unit_pemilik' => $unit->id_unit,
            'file_path' => 'dummy.pdf',
        ]);
    }
}
