<?php

namespace App\Filament\Verifikator\Resources\RiwayatSopResource\Pages;

use App\Filament\Verifikator\Resources\RiwayatSopResource;
use Filament\Resources\Pages\ListRecords;

class ListRiwayatSops extends ListRecords
{
    protected static string $resource = RiwayatSopResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
