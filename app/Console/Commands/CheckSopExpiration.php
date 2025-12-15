<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DokumenSop;
use App\Models\Notifikasi;
use App\Models\User;
use App\Events\NewNotification; // Import Event Realtime
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Wajib import DB

class CheckSopExpiration extends Command
{
    protected $signature = 'sop:check-expiration';
    protected $description = 'Cek siklus hidup SOP (Review Tahunan & Kadaluarsa 3 Tahun) dan Notifikasi Unit';

    public function handle()
    {
        $today = Carbon::now();

        $this->info("Memulai pengecekan SOP pada tanggal: " . $today->format('d M Y'));

        // ---------------------------------------------------------
        // 1. LOGIC AUTO-SKIP REVIEW
        // ---------------------------------------------------------
        $missedReviews = DokumenSop::where('status', 'AKTIF')
            ->whereDate('tgl_review_berikutnya', '<', $today)
            ->whereDate('tgl_kadaluarsa', '>', $today)
            ->get();

        foreach ($missedReviews as $sop) {
            $nextReview = Carbon::parse($sop->tgl_review_berikutnya)->addYear();

            if ($nextReview->gte(Carbon::parse($sop->tgl_kadaluarsa))) {
                $sop->update(['tgl_review_berikutnya' => null]);
            } else {
                $sop->update(['tgl_review_berikutnya' => $nextReview]);
            }
            $this->info("Auto-bump review date for SOP {$sop->judul_sop}");
        }

        // ---------------------------------------------------------
        // 2. LOGIC KADALUARSA (HARD EXPIRE - 3 TAHUN)
        // ---------------------------------------------------------
        $expiredSops = DokumenSop::where('status', 'AKTIF')
            ->whereDate('tgl_kadaluarsa', '<', $today)
            ->get();

        foreach ($expiredSops as $sop) {
            $sop->update(['status' => 'KADALUARSA']);

            // REVISI: Kirim ke Unit Pemilik, BUKAN created_by
            $this->sendToUnit(
                $sop->id_unit_pemilik, // Ambil Unit Pemilik
                'SOP Kadaluarsa (Masa 3 Tahun Habis)',
                "SOP '{$sop->judul_sop}' telah melewati masa berlaku 3 tahun. Status kini KADALUARSA.",
                $sop->id_sop
            );

            $this->info("SOP {$sop->judul_sop} set to KADALUARSA");
        }

        // ---------------------------------------------------------
        // 3. LOGIC REVIEW TAHUNAN & UPDATE TANGGAL
        // ---------------------------------------------------------
        $activeSops = DokumenSop::where('status', 'AKTIF')
            ->whereNotNull('tgl_review_berikutnya')
            ->whereDate('tgl_kadaluarsa', '>', $today)
            ->get();

        foreach ($activeSops as $sop) {
            $reviewDate = Carbon::parse($sop->tgl_review_berikutnya);

            // A. SKENARIO LEWAT TANGGAL (Bump Year)
            if ($today->greaterThan($reviewDate)) {
                $nextYearDate = $reviewDate->copy()->addYear();
                if ($nextYearDate->lessThanOrEqualTo(Carbon::parse($sop->tgl_kadaluarsa))) {
                    $sop->update(['tgl_review_berikutnya' => $nextYearDate]);
                } else {
                    $sop->update(['tgl_review_berikutnya' => null]);
                }
                continue;
            }

            // B. SKENARIO REMINDER H-30
            $daysLeft = $today->diffInDays($reviewDate, false);

            if ($daysLeft >= 0 && $daysLeft <= 30 && $daysLeft % 3 == 0) {
                $tahunKe = $sop->created_at->diffInYears($reviewDate) + 1;

                // REVISI: Kirim ke Unit Pemilik
                $this->sendToUnit(
                    $sop->id_unit_pemilik,
                    "Peringatan Review Tahunan (Tahun ke-{$tahunKe})",
                    "Reminder: SOP '{$sop->judul_sop}' wajib direview ulang per tahun. Jadwal: " . $reviewDate->format('d M Y') . " (H-{$daysLeft}).",
                    $sop->id_sop
                );
            }
        }

        // ---------------------------------------------------------
        // 4. LOGIC ALERT MENDEKATI KADALUARSA (H-30 EXPIRED)
        // ---------------------------------------------------------
        $expiringSops = DokumenSop::where('status', 'AKTIF')
            ->whereDate('tgl_kadaluarsa', '>', $today)
            ->whereDate('tgl_kadaluarsa', '<=', $today->copy()->addDays(30))
            ->get();

        foreach ($expiringSops as $sop) {
            $daysLeft = $today->diffInDays(Carbon::parse($sop->tgl_kadaluarsa), false);

            if ($daysLeft % 3 == 0) {
                // REVISI: Kirim ke Unit Pemilik
                $this->sendToUnit(
                    $sop->id_unit_pemilik,
                    'PERINGATAN FINAL: SOP Akan Kadaluarsa',
                    "URGENT: SOP '{$sop->judul_sop}' akan mati total dalam {$daysLeft} hari lagi. Segera perbarui dokumen!",
                    $sop->id_sop
                );
            }
        }
    }

    /**
     * Helper Baru: Kirim Notifikasi ke SEMUA USER di dalam UNIT
     */
    private function sendToUnit($unitId, $judul, $pesan, $sopId)
    {
        // 1. Ambil semua ID User yang ada di unit tersebut (via tb_unit_user)
        $userIds = DB::table('tb_unit_user')
            ->where('id_unit', $unitId)
            ->pluck('id_user');

        // 2. Loop dan kirim notifikasi
        foreach ($userIds as $userId) {
            $notif = Notifikasi::create([
                'id_user' => $userId,
                'judul'   => $judul,
                'pesan'   => $pesan,
                'is_read' => false,
                'id_sop'  => $sopId
            ]);

            // 3. Trigger Event Realtime (Agar lonceng berbunyi tanpa refresh)
            try {
                NewNotification::dispatch($notif);
            } catch (\Exception $e) {
                // Abaikan error broadcast jika websocket tidak disetting, biar cron job tidak mati
            }
        }
    }
}
