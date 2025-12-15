<?php

namespace App\Filament\Pengusul\Resources\SopAktifResource\Pages;

use App\Filament\Pengusul\Resources\SopAktifResource;
use Filament\Resources\Pages\ListRecords;

class ListSopAktifs extends ListRecords
{
    protected static string $resource = SopAktifResource::class;

    // Tidak ada Header Actions (Create) karena ini menu hanya untuk melihat
    protected function getHeaderActions(): array
    {
        return [];
    }
}
