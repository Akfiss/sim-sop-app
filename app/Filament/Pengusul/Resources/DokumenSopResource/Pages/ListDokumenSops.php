<?php

namespace App\Filament\Pengusul\Resources\DokumenSopResource\Pages;

use App\Filament\Pengusul\Resources\DokumenSopResource;
use App\Filament\Pengusul\Widgets\SopPengusulStats; // <--- Import Widget Baru
use App\Models\DokumenSop;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDokumenSops extends ListRecords
{
    protected static string $resource = DokumenSopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // --- TAMBAHKAN INI UNTUK MEMUNCULKAN CARD DI ATAS TABEL ---
    protected function getHeaderWidgets(): array
    {
        return [
            SopPengusulStats::class,
        ];
    }
}
