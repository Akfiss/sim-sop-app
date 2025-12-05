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
                ->icon('heroicon-m-document-duplicate')
                ->color('primary')
                ->url(route('filament.pengusul.resources.dokumen-sops.index')), // Reset Filter

            // KARTU 2: DALAM REVIEW
            Stat::make('Dalam Review', DokumenSop::where('created_by', $userId)->where('status', 'DALAM REVIEW')->count())
                ->description('Menunggu verifikasi')
                ->icon('heroicon-m-clock')
                ->color('warning')
                ->url(route('filament.pengusul.resources.dokumen-sops.index', ['tableFilters[status][value]' => 'DALAM REVIEW'])),

            // KARTU 3: PERLU REVISI
            Stat::make('Perlu Revisi', DokumenSop::where('created_by', $userId)->where('status', 'REVISI')->count())
                ->description('Harap segera diperbaiki')
                ->icon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->url(route('filament.pengusul.resources.dokumen-sops.index', ['tableFilters[status][value]' => 'REVISI'])),

            // KARTU 4: SOP AKTIF
            Stat::make('SOP Aktif', DokumenSop::where('created_by', $userId)->where('status', 'AKTIF')->count())
                ->description('Dokumen sah & berlaku')
                ->icon('heroicon-m-check-badge')
                ->color('success')
                ->url(route('filament.pengusul.resources.dokumen-sops.index', ['tableFilters[status][value]' => 'AKTIF'])),
        ];
    }
}
