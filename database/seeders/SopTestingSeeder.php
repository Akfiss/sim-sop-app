<?php

namespace Database\Seeders;

use App\Models\DokumenSop;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SopTestingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil User Pengusul 1, 2, 3
        $users = User::whereIn('username', ['pengusul1', 'pengusul2', 'pengusul3'])->get();
        $unit = UnitKerja::first(); // Default Unit

        if ($users->isEmpty() || !$unit) {
            $this->command->error('User pengusul1, pengusul2, pengusul3 atau Unit Kerja tidak ditemukan.');
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
            $this->command->info("Membuat 10 SOP untuk User: {$user->username}");

            for ($i = 0; $i < 10; $i++) {
                // Pilih Judul Random agar variatif
                $title = $titles[array_rand($titles)] . ' - ' . strtoupper(uniqid());

                // Tentukan Status Random
                // Bobot: Lebih banyak AKTIF untuk tes expiry date
                $statusList = ['DRAFT', 'DALAM REVIEW', 'REVISI', 'AKTIF', 'AKTIF', 'AKTIF', 'AKTIF']; 
                $status = $statusList[array_rand($statusList)];
                
                $data = [
                    'id_sop' => 'SOP-' . strtoupper(Str::random(6)), // 10 Char Limit
                    'judul_sop' => $title,
                    'kategori_sop' => rand(0, 1) ? 'SOP' : 'SOP_AP',
                    'status' => $status,
                    'id_unit_pemilik' => $unit->id_unit,
                    'created_by' => $user->id_user,
                    'file_path' => 'dummy.pdf', // Dummy path
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Setting Tanggal Khusus untuk Status AKTIF
                if ($status === 'AKTIF') {
                    // Skenario Tanggal (Random Dist)
                    $scenario = rand(1, 4);

                    // Default (Aman)
                    $tglPengesahan = now()->subMonth();
                    $tglReview = now()->addMonths(11);
                    $tglKadaluarsa = now()->addYears(2);

                    switch ($scenario) {
                        case 1: // Mendekati Review Tahunan (H-30 s/d Hari H)
                            $daysUntilReview = rand(0, 30);
                            $tglPengesahan = now()->subYear()->addDays($daysUntilReview); // Setahun lalu kurang dikit
                            $tglReview = now()->addDays($daysUntilReview);
                            $tglKadaluarsa = now()->addYears(2);
                            $data['judul_sop'] .= ' [WARNING REVIEW H-' . $daysUntilReview . ']';
                            break;

                        case 2: // Mendekati Kadaluarsa (H-30 s/d Hari H)
                            $daysUntilExpired = rand(0, 30);
                            $tglPengesahan = now()->subYears(3)->addDays($daysUntilExpired);
                            $tglReview = now()->subYear(); // Review udah lewat
                            $tglKadaluarsa = now()->addDays($daysUntilExpired);
                            $data['judul_sop'] .= ' [WARNING EXPIRED H-' . $daysUntilExpired . ']';
                            break;

                        case 3: // Sudah Kadaluarsa / Review Lewat (Expired)
                            $daysPast = rand(1, 100);
                            $tglPengesahan = now()->subYears(3)->subDays($daysPast);
                            $tglReview = now()->subYear()->subDays($daysPast);
                            $tglKadaluarsa = now()->subDays($daysPast);
                            $data['judul_sop'] .= ' [EXPIRED]';
                            // Status bisa tetap AKTIF di database tapi logic sistem anggap Expired, 
                            // atau kita set manual KADALUARSA jika mau strict.
                            // User minta: "approach annual review... approach expiration... passed active period"
                            // Kita biarkan status AKTIF biar keliatan merahnya di tabel (jika ada logic warna expired)
                            // Atau manual set status KADALUARSA?
                            // Biasanya sistem scheduler yang ubah ke KADALUARSA. Kita biarkan AKTIF tapi tanggal lewat.
                            break;

                        case 4: // Aman (Normal)
                            // Default values apply
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
        
        $this->command->info('Selesai membuat data dummy.');
    }
}
