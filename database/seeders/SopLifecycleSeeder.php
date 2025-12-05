<?php

namespace Database\Seeders;

use App\Models\DokumenSop;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SopLifecycleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil User Pengusul & Unit Kerja (Pastikan ada data)
        $user = User::where('role', 'PENGUSUL')->first() ?? User::first();
        $unit = UnitKerja::first();

        if (!$user || !$unit) {
            $this->command->error('Data User atau Unit Kerja kosong. Harap isi data master dulu.');
            return;
        }

        $this->command->info("Membuat Data Testing Lifecycle untuk User: {$user->username}");

        // DATA 1: SOP BARU (AMAN)
        // Kondisi: Baru aktif 1 bulan. Review masih 11 bulan lagi.
        // Harapan: Tidak ada tombol warning. Status Hijau.
        DokumenSop::create([
            'id_sop' => 'LC-01',
            'judul_sop' => '[TEST] SOP Aman (Baru 1 Bulan)',
            'kategori_sop' => 'SOP',
            'status' => 'AKTIF',
            'tgl_pengesahan' => now()->subMonth(),
            'tgl_berlaku' => now()->subMonth(),
            'tgl_review_berikutnya' => now()->addMonths(11), // Jauh dari H-30
            'tgl_kadaluarsa' => now()->addYears(2)->addMonths(11),
            'created_by' => $user->id_user,
            'id_unit_pemilik' => $unit->id_unit,
            'file_path' => 'dummy.pdf',
        ]);

        // DATA 2: MENDEKATI REVIEW TAHUNAN (H-20)
        // Kondisi: Aktif hampir setahun. 20 hari lagi jadwal review.
        // Harapan: MUNCUL TOMBOL KUNING "Review Tahunan".
        DokumenSop::create([
            'id_sop' => 'LC-02',
            'judul_sop' => '[TEST] SOP Warning Review (H-20)',
            'kategori_sop' => 'SOP',
            'status' => 'AKTIF',
            'tgl_pengesahan' => now()->subMonths(11)->subDays(10),
            'tgl_berlaku' => now()->subMonths(11)->subDays(10),
            'tgl_review_berikutnya' => now()->addDays(20), // <--- MASUK FASE H-30 REVIEW
            'tgl_kadaluarsa' => now()->addYears(2)->addDays(20),
            'created_by' => $user->id_user,
            'id_unit_pemilik' => $unit->id_unit,
            'file_path' => 'dummy.pdf',
        ]);

        // DATA 3: MENDEKATI REVIEW TAHUNAN (H-1 / BESOK)
        // Kondisi: Sangat mendesak untuk direview.
        // Harapan: MUNCUL TOMBOL KUNING "Review Tahunan".
        DokumenSop::create([
            'id_sop' => 'LC-03',
            'judul_sop' => '[TEST] SOP Warning Review (H-1)',
            'kategori_sop' => 'SOP',
            'status' => 'AKTIF',
            'tgl_pengesahan' => now()->subYear()->addDay(),
            'tgl_berlaku' => now()->subYear()->addDay(),
            'tgl_review_berikutnya' => now()->addDay(), // <--- BESOK HARUS REVIEW
            'tgl_kadaluarsa' => now()->addYears(2),
            'created_by' => $user->id_user,
            'id_unit_pemilik' => $unit->id_unit,
            'file_path' => 'dummy.pdf',
        ]);

        // DATA 4: MENDEKATI KADALUARSA TOTAL (H-15)
        // Kondisi: Sudah berjalan hampir 3 tahun. 15 hari lagi mati total.
        // Harapan: MUNCUL TOMBOL MERAH "Akan Kadaluarsa". Tombol Review TIDAK BOLEH Muncul.
        DokumenSop::create([
            'id_sop' => 'LC-04',
            'judul_sop' => '[TEST] SOP Warning Expired (H-15)',
            'kategori_sop' => 'SOP',
            'status' => 'AKTIF',
            'tgl_pengesahan' => now()->subYears(3)->addDays(15),
            'tgl_berlaku' => now()->subYears(3)->addDays(15),
            'tgl_review_berikutnya' => now()->subYear(), // Tanggal review sudah lewat (tidak relevan lagi karena mau expired)
            'tgl_kadaluarsa' => now()->addDays(15), // <--- MASUK FASE H-30 EXPIRED
            'created_by' => $user->id_user,
            'id_unit_pemilik' => $unit->id_unit,
            'file_path' => 'dummy.pdf',
        ]);

        // DATA 5: SUDAH LEWAT JADWAL REVIEW (DICUEKIN)
        // Kondisi: Jadwal review harusnya 5 hari lalu.
        // Harapan: Sistem Scheduler (bukan UI ini) nanti akan otomatis memajukannya.
        // Tapi di UI saat ini, Tombol Kuning masih akan muncul (karena logic diffInDays < 30 mencakup angka negatif/masa lalu jika tidak dibatasi 0).
        DokumenSop::create([
            'id_sop' => 'LC-05',
            'judul_sop' => '[TEST] SOP Telat Review (-5 Hari)',
            'kategori_sop' => 'SOP',
            'status' => 'AKTIF',
            'tgl_pengesahan' => now()->subYear()->subDays(5),
            'tgl_berlaku' => now()->subYear()->subDays(5),
            'tgl_review_berikutnya' => now()->subDays(5), // <--- SUDAH LEWAT 5 HARI
            'tgl_kadaluarsa' => now()->addYears(2),
            'created_by' => $user->id_user,
            'id_unit_pemilik' => $unit->id_unit,
            'file_path' => 'dummy.pdf',
        ]);
    }
}
