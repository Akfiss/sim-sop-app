<?php

namespace App\Filament\Direksi\Widgets;

use App\Models\DokumenSop;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class SopStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status SOP';
    protected static ?int $sort = 2;
    
    // Tetap full width agar rapi di grid
    protected int | string | array $columnSpan = 'full'; 

    // --- REVISI: BATASI TINGGI GRAFIK ---
    protected static ?string $maxHeight = '300px'; // Ukuran compact yang enak dilihat

    protected function getData(): array
    {
        $dirId = Auth::user()->id_direktorat;

        // Hitung data per status
        $data = DokumenSop::whereHas('unitPemilik', function(Builder $q) use ($dirId) {
                $q->where('id_direktorat', $dirId);
            })
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->orderBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Mapping Warna & Label
        // Kita sesuaikan urutan warna agar match dengan key $data
        $labels = array_keys($data);
        $values = array_values($data);
        
        $colors = [];
        foreach ($labels as $status) {
            $colors[] = match ($status) {
                'AKTIF' => '#10b981', // Hijau
                'KADALUARSA' => '#ef4444', // Merah
                default => '#6b7280', // Abu
            };
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah SOP',
                    'data' => $values,
                    'backgroundColor' => $colors,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    // OPSI: Menampilkan legenda di samping agar lingkaran tidak tertekan
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'right', // Posisi legenda di kanan
                    'align' => 'center',
                ],
            ],
            'maintainAspectRatio' => false, // Agar mengikuti maxHeight
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}