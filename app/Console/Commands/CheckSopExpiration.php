<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DokumenSop;
use App\Models\Notifikasi;
use Carbon\Carbon;

class CheckSopExpiration extends Command
{
    protected $signature = 'sop:check-expiration';
    protected $description = 'Cek siklus hidup SOP (Review Tahunan & Kadaluarsa 3 Tahun)';

    public function handle()
    {
        $today = Carbon::now();

        // ---------------------------------------------------------
        // 1. LOGIC KADALUARSA (HARD EXPIRE - 3 TAHUN)
        // ---------------------------------------------------------
        $expiredSops = DokumenSop::where('status', 'AKTIF')
            ->whereDate('tgl_kadaluarsa', '<', $today)
            ->get();

        foreach ($expiredSops as $sop) {
            $sop->update(['status' => 'KADALUARSA']);

            $this->sendNotification(
                $sop->created_by,
                'SOP Kadaluarsa (Masa 3 Tahun Habis)',
                "SOP '{$sop->judul_sop}' telah melewati masa berlaku 3 tahun. Status kini KADALUARSA.",
                $sop->id_sop
            );

            $this->info("SOP {$sop->id_sop} set to KADALUARSA");
        }

        // ---------------------------------------------------------
        // 2. LOGIC REVIEW TAHUNAN & UPDATE TANGGAL
        // ---------------------------------------------------------

        // Ambil SOP Aktif yang punya jadwal review
        $activeSops = DokumenSop::where('status', 'AKTIF')
            ->whereNotNull('tgl_review_berikutnya')
            ->whereDate('tgl_kadaluarsa', '>', $today) // Yang belum expired total
            ->get();

        foreach ($activeSops as $sop) {
            $reviewDate = Carbon::parse($sop->tgl_review_berikutnya);

            // A. SKENARIO: SUDAH LEWAT TANGGAL REVIEW (TAPI BELUM EXPIRED)
            // Artinya review tahun ini sudah lewat, kita majukan jadwal ke tahun depan
            if ($today->greaterThan($reviewDate)) {
                $nextYearDate = $reviewDate->copy()->addYear();

                // Pastikan tahun depan belum melewati batas kadaluarsa (3 tahun)
                if ($nextYearDate->lessThanOrEqualTo(Carbon::parse($sop->tgl_kadaluarsa))) {
                    $sop->update(['tgl_review_berikutnya' => $nextYearDate]);
                    $this->info("SOP {$sop->id_sop}: Review date bumped to next year ({$nextYearDate->format('Y-m-d')})");
                } else {
                    // Kalau tahun depan sudah expired, kosongkan jadwal review (biar fokus ke expired date)
                    $sop->update(['tgl_review_berikutnya' => null]);
                }
                continue; // Lanjut ke SOP berikutnya
            }

            // B. SKENARIO: MENDEKATI TANGGAL REVIEW (H-30)
            $daysLeft = $today->diffInDays($reviewDate, false); // false = return + or - days

            // Alert H-30 Review (Mulai kirim notif setiap 3 hari)
            if ($daysLeft >= 0 && $daysLeft <= 30 && $daysLeft % 3 == 0) {
                // Tentukan ini review tahun keberapa
                $tahunKe = $sop->created_at->diffInYears($reviewDate) + 1;

                $this->sendNotification(
                    $sop->created_by,
                    "Peringatan Review Tahunan (Tahun ke-{$tahunKe})",
                    "Reminder: SOP '{$sop->judul_sop}' wajib direview ulang per tahun. Jadwal review: " . $reviewDate->format('d M Y') . " (H-{$daysLeft}).",
                    $sop->id_sop
                );

                $this->info("SOP {$sop->id_sop}: Sent Annual Review Alert (H-{$daysLeft})");
            }
        }

        // ---------------------------------------------------------
        // 3. LOGIC ALERT MENDEKATI KADALUARSA (H-30 EXPIRED)
        // ---------------------------------------------------------
        // (Ini alert khusus mau mati total 3 tahun, terpisah dari review tahunan)
        $expiringSops = DokumenSop::where('status', 'AKTIF')
            ->whereDate('tgl_kadaluarsa', '>', $today)
            ->whereDate('tgl_kadaluarsa', '<=', $today->copy()->addDays(30))
            ->get();

        foreach ($expiringSops as $sop) {
            $daysLeft = $today->diffInDays(Carbon::parse($sop->tgl_kadaluarsa), false);

            if ($daysLeft % 3 == 0) {
                $this->sendNotification(
                    $sop->created_by,
                    'PERINGATAN FINAL: SOP Akan Kadaluarsa',
                    "URGENT: SOP '{$sop->judul_sop}' akan mati total dalam {$daysLeft} hari lagi. Segera perbarui dokumen!",
                    $sop->id_sop
                );
                $this->info("SOP {$sop->id_sop}: Sent Expiration Alert (H-{$daysLeft})");
            }
        }
    }

    // Helper kirim notif
    private function sendNotification($userId, $judul, $pesan, $sopId)
    {
        Notifikasi::create([
            'id_user' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'is_read' => false,
            'created_at' => now(),
            'id_sop' => $sopId
        ]);
    }
}
