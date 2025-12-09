<?php

namespace App\Filament\SuperAdmin\Resources\UserResource\Pages;

use App\Filament\SuperAdmin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    // 1. Custom Header Action (Tombol Hapus di pojok kanan atas)
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Hapus User'),
        ];
    }

    // 2. Redirect ke halaman List (Daftar) setelah Simpan
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // 3. Custom Tombol Form (Simpan & Batal Bahasa Indonesia)
    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save')
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
