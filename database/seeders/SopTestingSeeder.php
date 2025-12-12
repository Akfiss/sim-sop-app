<?php

namespace Database\Seeders;

use App\Models\DokumenSop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SopTestingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil User Pengusul 1, 2, 3 yang SUDAH memiliki relasi Unit
        // Kita gunakan 'with' untuk eager load relasi units agar efisien
        $users = User::whereIn('username', ['pengusul1', 'pengusul2', 'pengusul3'])
            ->with('units') 
            ->get();

        if ($users->isEmpty()) {
            $this->command->error('User pengusul1, pengusul2, pengusul3 tidak ditemukan. Pastikan MasterSeeder sudah dijalankan.');
            return;
        }

        $titles = [
            'Prosedur Penanganan Pasien Gawat Darurat',
            'Standar Kebersihan Ruang Operasi',
            'Alur Pendaftaran Pasien Rawat Jalan',
            'Protokol Penggunaan Alat Pelindung Diri (APD)',
            'Tata Cara Pengelolaan Limbah Medis B3',
            'Prosedur Evakuasi Kebakaran Rumah Sakit',
            'Standar Layanan Farmasi Klinis',
            'Prosedur Sterilisasi Alat Medis',
            'Panduan Penanganan Keluhan Pasien',
            'Prosedur Pemeliharaan Fasilitas Gedung',
            'Prosedur Penerimaan Pasien Baru',
            'Standar Pelayanan Rekam Medis',
            'Prosedur Keselamatan Kerja Laboratorium',
            'Alur Rujukan Pasien BPJS',
            'Protokol Isolasi Penyakit Menular',
            'Prosedur Audit Internal Mutu',
            'Tata Tertib Jam Berkunjung Pasien',
            'Prosedur Pengadaan Obat dan Alkes',
            'Standar Asuhan Keperawatan Intensif',
            'Prosedur Pemulangan Pasien Rawat Inap',
            'Protokol Penanganan Tumpahan Bahan Kimia',
            'Standar Kalibrasi Alat Medis',
            'Prosedur Pencatatan Insiden Keselamatan Pasien',
            'Panduan Triase di UGD',
            'Prosedur Pemasangan Infus',
            'Standar Operasional Ambulans 24 Jam',
            'Prosedur Penarikan Obat Kadaluarsa',
            'Tata Cara Penggunaan APAR',
            'Prosedur Stok Opname Farmasi',
            'Panduan Edukasi Kesehatan Pasien Pulang'
        ];

        foreach ($users as $user) {
            // Ambil Unit Kerja ASLI milik user ini
            // Asumsi user pengusul pasti punya unit (di set di MasterSeeder)
            $unitMilikUser = $user->units->first();

            if (!$unitMilikUser) {
                $this->command->warn("User {$user->username} tidak memiliki unit kerja. Lewati pembuatan SOP.");
                continue;
            }

            $this->command->info("Membuat 10 SOP untuk User: {$user->username} (Unit: {$unitMilikUser->nama_unit})");

            for ($i = 0; $i < 10; $i++) {
                // Pilih Judul Random agar variatif
                $title = $titles[array_rand($titles)];

                // Tentukan Status Random
                // Bobot: Lebih banyak AKTIF untuk tes dashboard direksi
                $statusList = ['DRAFT', 'DALAM REVIEW', 'REVISI', 'AKTIF', 'AKTIF', 'AKTIF', 'AKTIF', 'KADALUARSA']; 
                $status = $statusList[array_rand($statusList)];
                
                $data = [
                    'id_sop' => 'SOP-' . strtoupper(Str::random(6)),
                    'judul_sop' => $title, // Judul dasar dulu
                    'kategori_sop' => rand(0, 1) ? 'SOP' : 'SOP_AP',
                    'status' => $status,
                    'id_unit_pemilik' => $unitMilikUser->id_unit, // PENTING: Gunakan ID Unit milik user
                    'created_by' => $user->id_user,
                    'file_path' => 'dummy.pdf',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Setting Tanggal Khusus untuk Status AKTIF (agar muncul di dashboard direksi)
                if ($status === 'AKTIF') {
                    // Skenario Tanggal (Random Dist)
                    $scenario = rand(1, 4);

                    // Default (Aman)
                    $tglPengesahan = now()->subMonth();
                    $tglReview = now()->addMonths(11);
                    $tglKadaluarsa = now()->addYears(2);

                    switch ($scenario) {
                        case 1: // WARNING REVIEW (H-30)
                            $daysUntilReview = rand(0, 30);
                            $tglPengesahan = now()->subYear()->addDays($daysUntilReview); 
                            $tglReview = now()->addDays($daysUntilReview);
                            $tglKadaluarsa = now()->addYears(2);
                            $data['judul_sop'] .= ' [WARNING REVIEW]';
                            break;

                        case 2: // WARNING EXPIRED (H-30)
                            $daysUntilExpired = rand(0, 30);
                            $tglPengesahan = now()->subYears(3)->addDays($daysUntilExpired);
                            $tglReview = now()->subYear(); 
                            $tglKadaluarsa = now()->addDays($daysUntilExpired);
                            $data['judul_sop'] .= ' [WARNING EXPIRED]';
                            break;

                        case 3: // EXPIRED (Lewat Tanggal)
                            $daysPast = rand(1, 100);
                            $tglPengesahan = now()->subYears(3)->subDays($daysPast);
                            $tglReview = now()->subYear()->subDays($daysPast);
                            $tglKadaluarsa = now()->subDays($daysPast);
                            $data['judul_sop'] .= ' [EXPIRED]';
                            
                            // Opsional: Ubah status jadi KADALUARSA jika ingin strict
                            // $data['status'] = 'KADALUARSA'; 
                            break;

                        case 4: // Aman (Normal)
                            $data['judul_sop'] .= ' [AMAN]';
                            break;
                    }

                    $data['tgl_pengesahan'] = $tglPengesahan;
                    $data['tgl_berlaku'] = $tglPengesahan;
                    $data['tgl_review_berikutnya'] = $tglReview;
                    $data['tgl_kadaluarsa'] = $tglKadaluarsa;
                }

                DokumenSop::create($data);
            }
        }
        
        $this->command->info('Selesai membuat data dummy SOP yang terhubung dengan Unit & Direktorat.');
    }
}