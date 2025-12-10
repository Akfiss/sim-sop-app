<?php

namespace App\Filament\Verifikator\Widgets;

use App\Models\DokumenSop;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class VerifikatorStats extends BaseWidget
{
    // Mengatur urutan tampil di dashboard (paling atas)
    protected static ?int $sort = 1;

    // Refresh data otomatis setiap 15 detik (opsional)
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // 1. Total Dokumen (Kecuali Draft)
        $totalDokumen = DokumenSop::where('status', '!=', 'DRAFT')->count();

        // 2. Butuh Verifikasi (Dalam Review)
        $butuhVerifikasi = DokumenSop::where('status', 'DALAM REVIEW')->count();

        // 3. Segera Kadaluarsa (Aktif & H-30 expired)
        $segeraKadaluarsa = DokumenSop::where('status', 'AKTIF')
            ->whereBetween('tgl_kadaluarsa', [now(), now()->addDays(30)])
            ->count();

        // 4. SOP Aktif
        $sopAktif = DokumenSop::where('status', 'AKTIF')->count();

        return [
            Stat::make('Total Dokumen', $totalDokumen)
                ->description('Semua dokumen masuk')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('gray')
                ->url(route('filament.verifikator.resources.dokumen-sops.index')),

            Stat::make('Butuh Verifikasi', $butuhVerifikasi)
                ->description('Menunggu persetujuan')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('warning') // Kuning
                ->url(route('filament.verifikator.resources.dokumen-sops.index', ['tableFilters[status][value]' => 'DALAM REVIEW'])),

            Stat::make('Segera Kadaluarsa', $segeraKadaluarsa)
                ->description('Habis dalam 30 hari')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'), // Merah

            Stat::make('SOP Aktif', $sopAktif)
                ->description('Dokumen berlaku saat ini')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'), // Hijau
        ];
    }
}