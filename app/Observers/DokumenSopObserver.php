<?php

namespace App\Observers;

use App\Models\DokumenSop;
use App\Models\RiwayatSop;
use Illuminate\Support\Facades\Auth;

class DokumenSopObserver
{
    /**
     * Handle the DokumenSop "created" event.
     * Logic ini tetap di sini karena saat Create, kita tidak butuh perbandingan data lama vs baru.
     */
    public function created(DokumenSop $dokumenSop): void
    {
        // Cek agar tidak error jika dijalankan via seeder (Auth null)
        $userId = Auth::id() ?? $dokumenSop->created_by;

        if ($userId) {
            RiwayatSop::create([
                'id_sop' => $dokumenSop->id_sop,
                'id_user' => $userId,
                'status_sop' => $dokumenSop->status,
                'catatan' => 'Dokumen SOP baru berhasil diunggah/dibuat.',
                'dokumen_path' => $dokumenSop->file_path,
            ]);
        }
    }

    /**
     * Handle the DokumenSop "updated" event.
     * KOSONGKAN method ini.
     * Alasannya: Kita sudah menangani log update yang lebih detail ("Field A berubah jadi B")
     * di file app/Filament/Verifikator/Resources/DokumenSopResource/Pages/EditDokumenSop.php
     */
    public function updated(DokumenSop $dokumenSop): void
    {
        // Biarkan kosong agar tidak double record
    }

    /**
     * Handle the DokumenSop "deleted" event.
     * KOSONGKAN method ini.
     * Alasannya: Kita sudah menangani log soft delete di hook ->after() 
     * pada file app/Filament/Verifikator/Resources/DokumenSopResource.php
     */
    public function deleted(DokumenSop $dokumenSop): void
    {
        // Biarkan kosong agar tidak double record
    }
}