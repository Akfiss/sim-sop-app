<?php

namespace App\Filament\Direksi\Widgets;

use App\Models\DokumenSop;
use App\Models\UnitKerja;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TopUnitChart extends ChartWidget
{
    protected static ?string $heading = 'Kinerja Unit Kerja (Jumlah SOP)';
    protected static ?int $sort = 3;
    // protected int | string | array $columnSpan = 'full'; // Lebar Penuh

    protected function getData(): array
    {
        $dirId = Auth::user()->id_direktorat;

        // Ambil unit kerja beserta jumlah SOP-nya, urutkan dari terbanyak
        $units = UnitKerja::where('id_direktorat', $dirId)
            ->withCount('dokumenSop')
            ->orderByDesc('dokumen_sop_count')
            ->take(10) // Ambil Top 10
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Dokumen SOP',
                    'data' => $units->pluck('dokumen_sop_count')->toArray(),
                    // 2. WARNA TRANSPARAN (Ungu Glassy)
                    'backgroundColor' => 'rgba(139, 92, 246, 0.4)', // Ungu transparan
                    'borderColor' => 'rgb(139, 92, 246)',       // Ungu solid (Garis tepi)
                    'borderWidth' => 1,
                    'barPercentage' => 0.5, // Batang tidak terlalu gemuk
                ],
            ],
            'labels' => $units->pluck('nama_unit')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
