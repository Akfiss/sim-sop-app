<?php

namespace App\Filament\Verifikator\Resources\SopAktifResource\Pages;

use App\Filament\Verifikator\Resources\SopAktifResource;
use Filament\Resources\Pages\ListRecords;

class ListSopAktifs extends ListRecords
{
    protected static string $resource = SopAktifResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}