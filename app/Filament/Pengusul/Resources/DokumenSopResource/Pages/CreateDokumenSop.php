<?php

namespace App\Filament\Pengusul\Resources\DokumenSopResource\Pages;

use App\Filament\Pengusul\Resources\DokumenSopResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateDokumenSop extends CreateRecord
{
    protected static string $resource = DokumenSopResource::class;

    // Property untuk menandai apakah user menekan tombol Draft
    protected bool $isDraft = false;

    // 1. LOGIC REDIRECT KE INDEX (DAFTAR SOP) SETELAH SIMPAN
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // 2. LOGIC PENENTUAN STATUS (DRAFT vs DALAM REVIEW)
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set user pembuat
        $data['created_by'] = auth()->id();

        // Jika tombol Draft ditekan -> Status DRAFT
        // Jika tombol Kirim ditekan -> Status DALAM REVIEW
        if ($this->isDraft) {
            $data['status'] = 'DRAFT';
        } else {
            $data['status'] = 'DALAM REVIEW';
        }

        return $data;
    }

    // 3. CUSTOM TOMBOL AKSI
    protected function getFormActions(): array
    {
        return [
            // TOMBOL 1: SIMPAN & KIRIM (Primary)
            Actions\Action::make('create')
                ->label('Simpan & Kirim')
                ->submit('create')
                ->keyBindings(['mod+s']),

            // TOMBOL 2: SIMPAN SEBAGAI DRAFT (Secondary)
            Actions\Action::make('save_draft')
                ->label('Simpan sebagai Draft')
                ->color('gray')
                ->action(function () {
                    $this->isDraft = true; // Tandai sebagai draft
                    $this->create();       // Panggil fungsi create bawaan
                }),

            // TOMBOL 3: BATAL
            Actions\Action::make('cancel')
                ->label('Batal')
                ->url($this->getResource()::getUrl('index'))
                ->color('gray'),
        ];
    }
}
