<?php

namespace App\Filament\SuperAdmin\Widgets;

use App\Models\Direktorat;
use App\Models\UnitKerja;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminOverview extends BaseWidget
{
    // Opsional: Atur urutan widget (jika ada widget lain)
    protected static ?int $sort = 1;

    // Opsional: Auto refresh data setiap 15 detik (jika dashboard monitoring real-time)
    // protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // 1. Hitung Statistik User
        $totalUser = User::count();
        $userAktif = User::where('is_active', true)->count();
        $userNonAktif = User::where('is_active', false)->count();

        return [
            // KARTU 1: DATA USER
            Stat::make('Total User', $totalUser)
                ->description("{$userAktif} Aktif | {$userNonAktif} Non-Aktif")
                ->descriptionIcon('heroicon-m-users')
                ->color('primary') // Warna Biru/Default
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Hiasan grafik garis kecil (sparkline) - Boleh dihapus jika ingin polos
                ->url(route('filament.admin.resources.users.index')), // Klik kartu lari ke menu User

            // KARTU 2: DATA DIREKTORAT
            Stat::make('Total Direktorat', Direktorat::count())
                ->description('Direktorat Terdaftar')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success') // Warna Hijau
                ->url(route('filament.admin.resources.direktorats.index')),

            // KARTU 3: DATA UNIT KERJA
            Stat::make('Total Unit Kerja', UnitKerja::count())
                ->description('Unit Kerja di RS')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('warning') // Warna Kuning/Oranye
                ->url(route('filament.admin.resources.unit-kerjas.index')),
        ];
    }
}
