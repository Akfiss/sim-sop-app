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

    // --- LOGIC 4: OTOMATIS GANTI STATUS SAAT EDIT ---
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Setiap kali Pengusul menekan tombol Simpan/Save Changes:
        // Ubah status menjadi 'DALAM REVIEW' agar Verifikator memeriksa ulang.
        // Baik itu dari status 'REVISI' maupun 'AKTIF' (yang sedang direview tahunan).

        $data['status'] = 'DALAM REVIEW';

        return $data;
    }
}
