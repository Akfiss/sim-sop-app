<?php

namespace App\Filament\Pengusul\Widgets;

use App\Models\DokumenSop;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class SopPengusulStats extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = Auth::user()->id_user;

        return [
            // KARTU 1: TOTAL SOP
            Stat::make('Total Dokumen', DokumenSop::where('created_by', $userId)->count())
                ->description('Seluruh dokumen Anda')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary')
                ->url(route('filament.pengusul.resources.dokumen-sops.index')),

            // KARTU 2: DALAM REVIEW
            Stat::make('Dalam Review', DokumenSop::where('created_by', $userId)->where('status', 'DALAM REVIEW')->count())
                ->description('Menunggu verifikasi')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([2, 10, 3, 12, 1, 15, 10])
                ->color('warning')
                ->url(route('filament.pengusul.resources.dokumen-sops.index', ['tableFilters[status][value]' => 'DALAM REVIEW'])),

            // KARTU 3: PERLU REVISI
            Stat::make('Perlu Revisi', DokumenSop::where('created_by', $userId)->where('status', 'REVISI')->count())
                ->description('Harap segera diperbaiki')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->color('danger')
                ->url(route('filament.pengusul.resources.dokumen-sops.index', ['tableFilters[status][value]' => 'REVISI'])),

            // KARTU 4: SOP AKTIF
            Stat::make('SOP Aktif', DokumenSop::where('created_by', $userId)->where('status', 'AKTIF')->count())
                ->description('Dokumen sah & berlaku')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([10, 15, 8, 14, 18, 12, 20])
                ->color('success')
                ->url(route('filament.pengusul.resources.dokumen-sops.index', ['tableFilters[status][value]' => 'AKTIF'])),
        ];
    }
}
