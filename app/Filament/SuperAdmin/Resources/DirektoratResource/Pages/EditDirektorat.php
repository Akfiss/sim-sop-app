<?php

namespace App\Filament\SuperAdmin\Resources\DirektoratResource\Pages;

use App\Filament\SuperAdmin\Resources\DirektoratResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDirektorat extends EditRecord
{
    protected static string $resource = DirektoratResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
