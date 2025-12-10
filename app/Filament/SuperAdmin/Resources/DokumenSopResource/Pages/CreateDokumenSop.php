<?php

namespace App\Filament\SuperAdmin\Resources\DokumenSopResource\Pages;

use App\Filament\SuperAdmin\Resources\DokumenSopResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDokumenSop extends CreateRecord
{
    protected static string $resource = DokumenSopResource::class;
    
    // Redirect ke list setelah simpan
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}