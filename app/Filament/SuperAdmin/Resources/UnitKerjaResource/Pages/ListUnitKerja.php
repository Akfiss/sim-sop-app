<?php

namespace App\Filament\SuperAdmin\Resources\UnitKerjaResource\Pages;

use App\Filament\SuperAdmin\Resources\UnitKerjaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUnitKerja extends ListRecords
{
    protected static string $resource = UnitKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Unit Kerja'),
        ];
    }
}
