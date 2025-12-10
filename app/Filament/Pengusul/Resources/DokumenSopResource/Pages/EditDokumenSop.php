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
            // 1. TOMBOL SIMPAN BIASA (Tetap di status saat ini: DRAFT/REVISI)
            Actions\Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save') // Memanggil fungsi save() bawaan Filament
                ->keyBindings(['mod+s']),

            // 2. TOMBOL KIRIM VERIFIKASI (Ubah status jadi DALAM REVIEW)
            // Hanya muncul jika statusnya DRAFT atau REVISI
            Actions\Action::make('submit_to_verifier')
                ->label('Simpan & Kirim Verifikasi')
                ->color('primary') // Warna Biru Utama
                ->icon('heroicon-o-paper-airplane')
                ->visible(fn () => in_array($this->record->status, ['DRAFT', 'REVISI']))
                ->requiresConfirmation()
                ->modalHeading('Kirim Dokumen?')
                ->modalDescription('Dokumen akan dikirim ke Verifikator dan tidak bisa diedit sementara waktu.')
                ->modalSubmitActionLabel('Ya, Kirim')
                ->action(function () {
                    // 1. Simpan data form terbaru dulu (validasi berjalan di sini)
                    $this->save(); 
                    
                    // 2. Update status manual menjadi DALAM REVIEW
                    $this->record->update(['status' => 'DALAM REVIEW']);
                    
                    // 3. Notifikasi & Redirect
                    \Filament\Notifications\Notification::make()
                        ->title('Dokumen Berhasil Dikirim')
                        ->body('Status dokumen kini DALAM REVIEW menunggu verifikasi.')
                        ->success()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),

            // 3. TOMBOL BATAL
            Actions\Action::make('cancel')
                ->label('Batal')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }
}
