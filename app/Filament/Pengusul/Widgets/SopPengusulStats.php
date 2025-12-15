<?php

namespace App\Filament\Pengusul\Widgets;

use App\Models\DokumenSop;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Pengusul\Resources\SopAktifResource;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class SopPengusulStats extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = Auth::user()->id_user;

        // 5. Akan Kadaluarsa ("Approaching Expiration")
        $approachingExpiredCount = DokumenSop::where('created_by', $userId)
            ->where('status', 'AKTIF')
            ->whereDate('tgl_kadaluarsa', '<=', now()->addDays(30))
            ->whereDate('tgl_kadaluarsa', '>=', now())
            ->count();

        return [
            // KARTU 1: TOTAL SOP
            Stat::make('Total Dokumen', DokumenSop::where('created_by', $userId)->count())
                ->description('Seluruh dokumen Anda')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info')
                ->url(SopAktifResource::getUrl('index')),

            // KARTU 2: SOP AKTIF
            Stat::make('SOP Aktif', DokumenSop::where('created_by', $userId)->where('status', 'AKTIF')->count())
                ->description('Dokumen sah & berlaku')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->url(SopAktifResource::getUrl( 'index',['tableFilters[status][value]' => 'AKTIF'])),

            // KARTU 3: SOP TIDAK AKTIF
            Stat::make('Akan Kadaluarsa', $approachingExpiredCount)
                ->description('Mendekati tanggal kadaluarsa (H-30)')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
