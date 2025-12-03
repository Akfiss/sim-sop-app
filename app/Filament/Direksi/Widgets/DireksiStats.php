<?php

namespace App\Filament\Direksi\Widgets;

use App\Models\DokumenSop;
use App\Models\UnitKerja;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class DireksiStats extends BaseWidget
{
    protected function getStats(): array
    {
        $dirId = Auth::user()->id_direktorat;

        // Query Dasar: Ambil SOP milik direktorat ini
        $sopQuery = DokumenSop::whereHas('unitPemilik', function(Builder $q) use ($dirId) {
            $q->where('id_direktorat', $dirId);
        });

        return [
            Stat::make('Total Dokumen SOP', $sopQuery->count())
                ->description('Seluruh unit di bawah Direktorat')
                ->icon('heroicon-m-document-duplicate')
                ->color('primary'),

            Stat::make('SOP Aktif', (clone $sopQuery)->where('status', 'AKTIF')->count())
                ->description('Dokumen valid dan berlaku')
                ->icon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Unit Kerja', UnitKerja::where('id_direktorat', $dirId)->count())
                ->description('Jumlah Unit di bawah naungan Anda')
                ->icon('heroicon-m-building-office-2')
                ->color('info'),
        ];
    }
}
