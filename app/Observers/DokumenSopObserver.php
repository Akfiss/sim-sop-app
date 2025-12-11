<?php

namespace App\Observers;

use App\Models\DokumenSop;
use App\Models\RiwayatSop;
use Illuminate\Support\Facades\Auth;

class DokumenSopObserver
{
    /**
     * Handle the DokumenSop "created" event.
     */
    public function created(DokumenSop $dokumenSop): void
    {
        RiwayatSop::create([
            'id_sop' => $dokumenSop->id_sop,
            'id_user' => Auth::id() ?? $dokumenSop->created_by,
            'status_sop' => $dokumenSop->status,
            'catatan' => 'Dokumen SOP baru berhasil dibuat.',
            'dokumen_path' => $dokumenSop->file_path,
        ]);
    }

    /**
     * Handle the DokumenSop "updated" event.
     */
    public function updated(DokumenSop $dokumenSop): void
    {
        if ($dokumenSop->isDirty('status') || $dokumenSop->isDirty('file_path')) {
            
            $catatan = 'Update Data SOP.';
            if ($dokumenSop->isDirty('status')) {
                $catatan = 'Status berubah menjadi ' . $dokumenSop->status;

                // Jika status REVISI dan ada catatan revisi dari verifikator
                if ($dokumenSop->status === 'REVISI' && !empty($dokumenSop->catatan_revisi)) {
                    $catatan .= '. Pesan Verifikator: ' . $dokumenSop->catatan_revisi;
                }
            }

            RiwayatSop::create([
                'id_sop' => $dokumenSop->id_sop,
                'id_user' => Auth::id() ?? $dokumenSop->updated_by ?? $dokumenSop->created_by,
                'status_sop' => $dokumenSop->status,
                'catatan' => $catatan,
                'dokumen_path' => $dokumenSop->file_path,
            ]);
        }
    }

    /**
     * Handle the DokumenSop "deleted" event.
     */
    public function deleted(DokumenSop $dokumenSop): void
    {
        // Don't log history if it's a force delete (record is gone)
        if ($dokumenSop->isForceDeleting()) {
            return;
        }

        RiwayatSop::create([
            'id_sop' => $dokumenSop->id_sop,
            'id_user' => Auth::id() ?? $dokumenSop->updated_by,
            'status_sop' => 'ARCHIVED',
            'catatan' => 'Dokumen dipindahkan ke sampah (Soft Delete).',
            'dokumen_path' => $dokumenSop->file_path,
        ]);
    }
}
