<?php

namespace App\Filament\Direksi\Resources\DokumenSopResource\Pages;

use App\Filament\Direksi\Resources\DokumenSopResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDokumenSop extends EditRecord
{
    protected static string $resource = DokumenSopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
