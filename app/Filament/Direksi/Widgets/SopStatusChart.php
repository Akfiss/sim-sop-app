<?php

namespace App\Filament\Direksi\Widgets;

use App\Models\DokumenSop;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class SopStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status SOP';
    protected static ?int $sort = 2; // Urutan tampilan

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

        // Mapping Warna Transparan (RGBA)
        $colors = [
            'AKTIF'        => 'rgba(16, 185, 129, 0.5)',  // Hijau (Success)
            'DALAM REVIEW' => 'rgba(245, 158, 11, 0.5)',  // Kuning (Warning)
            'KADALUARSA'   => 'rgba(107, 114, 128, 0.5)', // Abu (Gray)
            'REVISI'       => 'rgba(239, 68, 68, 0.5)',   // Merah (Danger)
        ];

        // Mapping Warna Border (Solid)
        $borders = [
            'AKTIF'        => 'rgb(16, 185, 129)',
            'DALAM REVIEW' => 'rgb(245, 158, 11)',
            'KADALUARSA'   => 'rgb(107, 114, 128)',
            'REVISI'       => 'rgb(239, 68, 68)',
        ];

        // Cocokkan data dengan warna
        $backgrounds = [];
        $borderColors = [];
        foreach ($data as $status => $count) {
            $backgrounds[] = $colors[$status] ?? 'rgba(200, 200, 200, 0.5)';
            $borderColors[] = $borders[$status] ?? 'rgb(200, 200, 200)';
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah SOP',
                    'data' => array_values($data),
                    'backgroundColor' => ['#f59e0b', '#ef4444', '#10b981', '#6b7280'], // Kuning, Merah, Hijau, Abu
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'polarArea';
    }
}
