<?php

namespace App\Filament\SuperAdmin\Resources\SopSampahResource\Pages;

use App\Filament\SuperAdmin\Resources\SopSampahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSopSampahs extends ListRecords
{
    protected static string $resource = SopSampahResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
