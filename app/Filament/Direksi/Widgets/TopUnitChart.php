<?php

namespace App\Filament\Direksi\Widgets;

use App\Models\UnitKerja;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TopUnitChart extends ChartWidget
{
    protected static ?string $heading = 'Kinerja Unit Kerja (Jumlah SOP)';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px'; 

    protected function getData(): array
    {
        $dirId = Auth::user()->id_direktorat;

        $units = UnitKerja::where('id_direktorat', $dirId)
            ->withCount('dokumenSop')
            ->orderByDesc('dokumen_sop_count')
            ->take(10) // Top 10
            ->get();

        // --- PALET WARNA (10 Variasi) ---
        $backgrounds = [
            'rgba(54, 162, 235, 0.6)',   // Biru
            'rgba(255, 99, 132, 0.6)',   // Merah
            'rgba(255, 206, 86, 0.6)',   // Kuning
            'rgba(75, 192, 192, 0.6)',   // Hijau Tosca
            'rgba(153, 102, 255, 0.6)',  // Ungu
            'rgba(255, 159, 64, 0.6)',   // Oranye
            'rgba(201, 203, 207, 0.6)',  // Abu-abu
            'rgba(16, 185, 129, 0.6)',   // Emerald
            'rgba(236, 72, 153, 0.6)',   // Pink
            'rgba(99, 102, 241, 0.6)',   // Indigo
        ];

        $borders = [
            'rgb(54, 162, 235)',
            'rgb(255, 99, 132)',
            'rgb(255, 206, 86)',
            'rgb(75, 192, 192)',
            'rgb(153, 102, 255)',
            'rgb(255, 159, 64)',
            'rgb(201, 203, 207)',
            'rgb(16, 185, 129)',
            'rgb(236, 72, 153)',
            'rgb(99, 102, 241)',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Total Dokumen SOP',
                    'data' => $units->pluck('dokumen_sop_count')->toArray(),
                    // GUNAKAN ARRAY WARNA
                    'backgroundColor' => $backgrounds, 
                    'borderColor' => $borders,       
                    'borderWidth' => 1,
                    'barPercentage' => 0.6, 
                    'categoryPercentage' => 0.2, 
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $units->pluck('nama_unit')->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false, // Sembunyikan legenda karena label unit sudah ada di bawah (Axis X)
                ],
            ],
            'maintainAspectRatio' => false,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}