<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\DokumenSopResource\Pages;
use App\Models\DokumenSop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class DokumenSopResource extends Resource
{
    protected static ?string $model = DokumenSop::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationLabel = 'Semua Dokumen SOP';
    protected static ?string $navigationGroup = 'Manajemen SOP'; // Grup Menu Baru
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Form admin biasanya full control, kita tampilkan semua field penting
                Forms\Components\Section::make('Detail Dokumen')
                    ->schema([
                        Forms\Components\TextInput::make('judul_sop')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('nomor_sk')
                            ->maxLength(50),

                        Forms\Components\Select::make('status')
                            ->options([
                                'DRAFT' => 'Draft',
                                'DALAM REVIEW' => 'Dalam Review',
                                'REVISI' => 'Revisi',
                                'AKTIF' => 'Aktif',
                                'KADALUARSA' => 'Kadaluarsa',
                                'ARCHIVED' => 'Diarsipkan (Non-Aktif)', // Status Baru
                            ])
                            ->required(),

                        Forms\Components\Select::make('kategori_sop')
                            ->options([
                                'SOP' => 'SOP',
                                'SOP_AP' => 'SOP AP',
                            ])
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul_sop')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->judul_sop),

                Tables\Columns\TextColumn::make('unitPemilik.nama_unit')
                    ->label('Unit')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'AKTIF' => 'success',
                        'DALAM REVIEW' => 'warning',
                        'ARCHIVED' => 'gray', // Warna abu-abu untuk arsip
                        'DRAFT' => 'gray',
                        'REVISI' => 'danger',
                        'KADALUARSA' => 'danger',
                        default => 'info',
                    }),

                Tables\Columns\TextColumn::make('tgl_pengesahan')
                    ->date('d M Y')
                    ->label('Tgl Sah'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'AKTIF' => 'Aktif',
                        'ARCHIVED' => 'Arsip',
                        'DALAM REVIEW' => 'Review',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    
                    // 1. EDIT DATA (Standar)
                    // Tables\Actions\EditAction::make(),

                    // 2. SOLUSI 1: ARSIPKAN (SOFT ACTION)
                    // Mengubah status jadi 'ARCHIVED' agar tidak muncul di list aktif tapi data aman
                    Tables\Actions\Action::make('archive')
                        ->label('Arsipkan (Non-Aktifkan)')
                        ->icon('heroicon-o-archive-box')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Arsipkan Dokumen ini?')
                        ->modalDescription('Dokumen akan dinonaktifkan (status Archived) dan tidak berlaku lagi, namun data tetap tersimpan dalam sistem.')
                        ->modalSubmitActionLabel('Ya, Arsipkan')
                        ->action(function (DokumenSop $record) {
                            $record->update(['status' => 'ARCHIVED']);
                            
                            Notification::make()
                                ->title('Dokumen berhasil diarsipkan')
                                ->success()
                                ->send();
                        })
                        // Tombol ini hilang jika statusnya sudah ARCHIVED
                        ->visible(fn (DokumenSop $record) => $record->status !== 'ARCHIVED'),

                    // 3. SOLUSI 3: HAPUS PERMANEN (HARD DELETE)
                    // Menghapus data dari database. Hanya Super Admin yang punya akses ini.
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus Permanen')
                        ->icon('heroicon-o-trash')
                        ->modalHeading('Hapus Dokumen Secara Permanen?')
                        ->modalDescription('PERINGATAN: Tindakan ini akan menghapus data dari database selamanya dan tidak bisa dikembalikan. Gunakan hanya untuk data sampah/salah input.')
                        ->before(function (DokumenSop $record) {
                            // Opsional: Hapus file fisik jika diperlukan
                            Storage::disk('public')->delete($record->file_path);
                        }),

                ])->icon('heroicon-m-ellipsis-vertical'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDokumenSops::route('/'),
            'create' => Pages\CreateDokumenSop::route('/create'),
            'edit' => Pages\EditDokumenSop::route('/{record}/edit'),
        ];
    }
}