<?php

namespace App\Filament\SuperAdmin\Resources\UnitKerjaResource\Pages;

use App\Filament\SuperAdmin\Resources\UnitKerjaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnitKerja extends EditRecord
{
    protected static string $resource = UnitKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
