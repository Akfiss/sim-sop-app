<?php

namespace Database\Seeders;

use App\Models\DokumenSop;
use App\Models\RiwayatSop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SopTestingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil User Pengusul
        $users = User::whereIn('username', ['pengusul1', 'pengusul2', 'pengusul3'])->get();

        // ID Verifikator (Anggap ID 1 / Super Admin)
        $idVerifikator = 1;

        foreach ($users as $user) {
            // Ambil Unit ID milik pengusul
            $unitId = DB::table('tb_unit_user')
                ->where('id_user', $user->id_user)
                ->value('id_unit');

            if (!$unitId) continue;

            $this->command->info("Generate SOP Aktif/Kadaluarsa untuk: {$user->username}");

            for ($i = 0; $i < 10; $i++) {
                // HANYA ADA 2 PILIHAN STATUS
                $isExpired = rand(0, 10) > 7; // 30% kemungkinan Kadaluarsa
                $status = $isExpired ? 'KADALUARSA' : 'AKTIF';

                $title = "SOP Standar Pelayanan " . Str::random(5);

                // --- LOGIKA TANGGAL ---
                if ($status === 'AKTIF') {
                    // Dokumen masih berlaku
                    $tglPengesahan = now()->subMonths(rand(1, 12)); // Terbit 1-12 bulan lalu
                    $tglKadaluarsa = now()->addYears(2);             // Mati 2 tahun lagi
                    $createdAt     = $tglPengesahan;
                } else {
                    // Dokumen sudah mati (Kadaluarsa)
                    // Anggap terbit 3 tahun lalu, mati bulan lalu
                    $tglPengesahan = now()->subYears(3);
                    $tglKadaluarsa = now()->subDays(rand(1, 60)); // Mati 1-60 hari lalu
                    $createdAt     = $tglPengesahan;
                }

                // 1. CREATE DOKUMEN SOP
                $sop = DokumenSop::create([
                    'id_sop' => 'SOP-' . strtoupper(Str::random(6)),
                    'judul_sop' => $title,
                    'kategori_sop' => rand(0,1) ? 'SOP' : 'SOP_AP',
                    'status' => $status, // Hanya AKTIF / KADALUARSA
                    'id_unit_pemilik' => $unitId,
                    'created_by' => $idVerifikator,
                    'file_path' => 'dummy.pdf',
                    'nomor_sk' => 'SK/RS/' . date('Y') . '/' . rand(100, 999),
                    'tgl_pengesahan' => $tglPengesahan,
                    'tgl_berlaku' => $tglPengesahan,
                    'tgl_kadaluarsa' => $tglKadaluarsa,
                    'created_at' => $createdAt,
                    'updated_at' => now(),
                ]);

                // 2. CREATE RIWAYAT (HANYA LOG AKTIF & KADALUARSA)

                // Log Pertama: Selalu "Diterbitkan / Aktif" (Meskipun sekarang sudah expired, dulunya pasti aktif)
                RiwayatSop::create([
                    'id_sop' => $sop->id_sop,
                    'id_user' => $idVerifikator,
                    'status_sop' => 'AKTIF',
                    'catatan' => 'Dokumen baru diunggah dan diterbitkan oleh Verifikator.',
                    'dokumen_path' => 'dummy.pdf',
                    'created_at' => $tglPengesahan, // Sesuai tanggal terbit
                    'updated_at' => $tglPengesahan,
                ]);

                // Log Kedua: Jika status sekarang KADALUARSA, buat log penutup
                if ($status === 'KADALUARSA') {
                    RiwayatSop::create([
                        'id_sop' => $sop->id_sop,
                        'id_user' => null, // null artinya Sistem (Otomatis)
                        'status_sop' => 'KADALUARSA',
                        'catatan' => 'Masa berlaku dokumen telah habis. Status otomatis berubah menjadi KADALUARSA.',
                        'dokumen_path' => 'dummy.pdf',
                        'created_at' => $tglKadaluarsa, // Sesuai tanggal mati
                        'updated_at' => $tglKadaluarsa,
                    ]);
                }
            }
        }
        $this->command->info('Selesai. Database bersih dari status Draft/Revisi.');
    }
}
