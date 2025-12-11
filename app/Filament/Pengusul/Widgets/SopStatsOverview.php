<?php

namespace App\Filament\Pengusul\Widgets;

use App\Models\DokumenSop;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class SopStatsOverview extends BaseWidget
{
    // protected static ?string $pollingInterval = '15s'; // Auto refresh Polling

    protected function getStats(): array
    {
        $userId = Auth::id();

        // 1. Perlu Review Tahunan (H-30)

        // 4. Perlu Review ("Needs Review" - Logic dashboard ini biasanya untuk Pengusul,
        // tapi Pengusul tidak me-review. Mungkin maksudnya "SOP saya yg sedang direview" = Under Review?
        // ATAU "Mendekati Review Tahunan" (Approaching Annual Review)?
        // User request: "Needs Review" AND "Under Review".
        // "Under Review" = Status DALAM REVIEW.
        // "Needs Review" kemungkinan "Mendekati Review Tahunan" (H-30).
        $needsReviewCount = DokumenSop::where('created_by', $userId)
            ->where('status', 'AKTIF')
            ->whereDate('tgl_review_berikutnya', '<=', now()->addDays(30)) // H-30
            ->whereDate('tgl_review_berikutnya', '>=', now()) // Belum lewat (kalau lewat mungkin expired logic lain)
            ->count();

        // 5. Akan Kadaluarsa ("Approaching Expiration")
        $approachingExpiredCount = DokumenSop::where('created_by', $userId)
            ->where('status', 'AKTIF')
            ->whereDate('tgl_kadaluarsa', '<=', now()->addDays(30))
            ->whereDate('tgl_kadaluarsa', '>=', now())
            ->count();



        return [
            Stat::make('Perlu Review Tahunan', $needsReviewCount)
                ->description('Mendekati jadwal review (H-30)')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->chart([15, 10, 8, 12, 18, 12, 2])
                ->color('warning'),

            Stat::make('Akan Kadaluarsa', $approachingExpiredCount)
                ->description('Mendekati tanggal kadaluarsa (H-30)')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->chart([20, 15, 12, 8, 6, 2, 0])
                ->color('danger'),
        ];
    }
}
