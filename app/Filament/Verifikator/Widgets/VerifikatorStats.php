<?php

namespace App\Filament\Verifikator\Widgets;

use App\Models\DokumenSop;
// Import Resource yang mau dituju
use App\Filament\Verifikator\Resources\DokumenSopResource;
use App\Filament\Verifikator\Resources\SopAktifResource; 
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class VerifikatorStats extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // Pastikan menggunakan id_user (primary key custom Anda)
        $userId = Auth::user()->id_user; 

        // 1. Mendekati Review Tahunan (Aktif & H-30 review)
        $needsReviewCount = DokumenSop::where('created_by', $userId)
            ->where('status', 'AKTIF')
            ->whereDate('tgl_review_berikutnya', '<=', now()->addDays(30)) 
            ->whereDate('tgl_review_berikutnya', '>=', now()) 
            ->count();

        // 2. Segera Kadaluarsa (Aktif & H-30 expired)
        $segeraKadaluarsa = DokumenSop::where('created_by', $userId)
            ->where('status', 'AKTIF')
            ->whereDate('tgl_kadaluarsa', '<=', now()->addDays(30))
            ->whereDate('tgl_kadaluarsa', '>=', now())
            ->count();

        return [
            // CARD 1: Total Dokumen -> Link ke Verifikasi SOP (DokumenSopResource)
            Stat::make('Total Dokumen', DokumenSop::where('created_by', $userId)->count())
                ->description('Seluruh dokumen Anda')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info')
                // BEST PRACTICE: Gunakan getUrl() dari Resource
                ->url(DokumenSopResource::getUrl('index')), 

            Stat::make('Perlu Review Tahunan', $needsReviewCount)
                ->description('Mendekati jadwal review (H-30)')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'), 

            Stat::make('Segera Kadaluarsa', $segeraKadaluarsa)
                ->description('Habis dalam 30 hari')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'), 

            // CARD 4: SOP Aktif -> Link ke SOP Aktif (SopAktifResource)
            Stat::make('SOP Aktif', DokumenSop::where('created_by', $userId)->where('status', 'AKTIF')->count())
                ->description('Dokumen sah dan berlaku')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                // BEST PRACTICE: Gunakan getUrl() dari Resource
                ->url(SopAktifResource::getUrl('index')),
        ];
    }
}