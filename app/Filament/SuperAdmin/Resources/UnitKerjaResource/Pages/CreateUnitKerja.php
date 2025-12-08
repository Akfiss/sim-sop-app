<?php

namespace App\Filament\SuperAdmin\Resources\UnitKerjaResource\Pages;

use App\Filament\SuperAdmin\Resources\UnitKerjaResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUnitKerja extends CreateRecord
{
    protected static string $resource = UnitKerjaResource::class;

    // 1. REDIRECT KE LIST SETELAH SIMPAN
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

     // 2. CUSTOM NOTIFIKASI SUKSES (INI SOLUSINYA)
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Unit Kerja Berhasil Ditambahkan') // Judul custom
            ->body('Data unit kerja baru telah tersimpan di sistem.') // Pesan custom
            ->duration(5000); // Opsional: Durasi tampil (ms)
    }

    // 3. TERJEMAHKAN TOMBOL KE BAHASA INDONESIA
    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label('Simpan')
                ->submit('create')
                ->keyBindings(['mod+s']),

            Actions\Action::make('createAnother')
                ->label('Simpan & Buat Baru')
                ->action('createAnother')
                ->color('gray'),

            Actions\Action::make('cancel')
                ->label('Batal')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }
}
