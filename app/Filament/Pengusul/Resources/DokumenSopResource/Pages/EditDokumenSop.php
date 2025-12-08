<?php

namespace App\Filament\Pengusul\Resources\DokumenSopResource\Pages;

use App\Filament\Pengusul\Resources\DokumenSopResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDokumenSop extends EditRecord
{
    protected static string $resource = DokumenSopResource::class;

    // 1. HEADER ACTIONS (HAPUS)
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus Dokumen'),
        ];
    }

    // 2. LOGIC REDIRECT KE INDEX SETELAH UPDATE
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // 3. CUSTOM TOMBOL FORM (BAHASA INDONESIA)
    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save')
                ->keyBindings(['mod+s']),

            Actions\Action::make('cancel')
                ->label('Batal')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }

    // 4. LOGIC TAMBAHAN: JIKA DRAFT DIEDIT DAN DISIMPAN -> TETAP DRAFT ATAU KE REVIEW?
    // Opsional: Jika Anda ingin saat Draft diedit otomatis jadi 'DALAM REVIEW', tambahkan ini:
    /*
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Jika user klik 'Simpan Perubahan', status otomatis naik jadi 'DALAM REVIEW'
        // Kecuali Anda mau statusnya tetap DRAFT sampai diajukan manual.
        // Untuk saat ini, kita biarkan logic default (status tidak berubah kecuali diubah manual)
        // Atau force ke DALAM REVIEW jika sebelumnya DRAFT:

        if ($this->record->status === 'DRAFT') {
             $data['status'] = 'DALAM REVIEW';
        }

        return $data;
    }
    */
}
