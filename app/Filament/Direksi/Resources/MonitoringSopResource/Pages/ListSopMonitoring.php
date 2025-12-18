<?php

namespace App\Filament\Direksi\Resources\MonitoringSopResource\Pages;

use App\Filament\Direksi\Resources\MonitoringSopResource;
use Filament\Resources\Pages\ListRecords;

class ListSopMonitoring extends ListRecords
{
    protected static string $resource = MonitoringSopResource::class;

    // Tidak ada Header Actions (Create) karena ini menu hanya untuk melihat
    protected function getHeaderActions(): array
    {
        return [];
    }
}
