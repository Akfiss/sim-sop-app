<?php

namespace App\Filament\Direksi\Resources\DokumenSopResource\Pages;

use App\Filament\Direksi\Resources\DokumenSopResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDokumenSop extends ViewRecord
{
    protected static string $resource = DokumenSopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
