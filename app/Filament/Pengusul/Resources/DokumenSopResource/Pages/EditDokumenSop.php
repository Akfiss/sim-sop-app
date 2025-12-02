<?php

namespace App\Filament\Pengusul\Resources\DokumenSopResource\Pages;

use App\Filament\Pengusul\Resources\DokumenSopResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDokumenSop extends EditRecord
{
    protected static string $resource = DokumenSopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
