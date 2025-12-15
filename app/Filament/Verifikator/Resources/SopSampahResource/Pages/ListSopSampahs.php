<?php

namespace App\Filament\Verifikator\Resources\SopSampahResource\Pages;

use App\Filament\Verifikator\Resources\SopSampahResource;
use Filament\Resources\Pages\ListRecords;

class ListSopSampahs extends ListRecords
{
    protected static string $resource = SopSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
