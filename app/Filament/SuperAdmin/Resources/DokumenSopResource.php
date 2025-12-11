<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\DokumenSopResource\Pages;
use App\Models\DokumenSop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatSop;

class DokumenSopResource extends Resource
{
    protected static ?string $model = DokumenSop::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationLabel = 'Semua Dokumen SOP';
    protected static ?string $navigationGroup = 'Manajemen SOP';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        // ... (Keep existing form)
        return $form
            ->schema([
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
                                'ARCHIVED' => 'Diarsipkan (Non-Aktif)',
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
                        'ARCHIVED' => 'gray',
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
                    
                    // 1. EDIT DATA
                    Tables\Actions\EditAction::make(), // Mengembalikan Edit Action standard

                    // 2. ARSIPKAN (SOFT ACTION - STATUS ONLY)
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
                        ->visible(fn (DokumenSop $record) => $record->status !== 'ARCHIVED' && !$record->trashed()),

                    // 3. AKTIFKAN KEMBALI (UNARCHIVE)
                    Tables\Actions\Action::make('unarchive')
                        ->label('Aktifkan Kembali')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Aktifkan Kembali Dokumen ini?')
                        ->modalDescription('Dokumen akan dikembalikan ke status AKTIF.')
                        ->modalSubmitActionLabel('Ya, Aktifkan')
                        ->action(function (DokumenSop $record) {
                            $record->update(['status' => 'AKTIF']);
                            
                            Notification::make()
                                ->title('Dokumen berhasil diaktifkan kembali')
                                ->success()
                                ->send();
                        })
                        ->visible(fn (DokumenSop $record) => $record->status === 'ARCHIVED' && !$record->trashed()),

                    // 4. SOFT DELETE (HAPUS KE SAMPAH)
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus (Sampah)')
                        ->modalHeading('Pindahkan ke Sampah?')
                        ->modalDescription('Data akan dipindahkan ke sampah (Soft Delete) dan bisa dipulihkan. File tidak akan dihapus.'),

                    // 5. RESTORE (PULIHKAN DARI SAMPAH)
                    Tables\Actions\RestoreAction::make()
                        ->label('Pulihkan')
                        ->after(function (DokumenSop $record) {
                            // Log History
                            RiwayatSop::create([
                                'id_sop' => $record->id_sop,
                                'id_user' => Auth::id(),
                                'status_sop' => 'AKTIF', // Kembali aktif
                                'catatan' => 'Dokumen dipulihkan dari sampah oleh Admin.',
                                'dokumen_path' => $record->file_path
                            ]);
                        }),

                    // 6. HARD DELETE (HAPUS PERMANEN)
                    Tables\Actions\ForceDeleteAction::make()
                        ->label('Hapus Permanen')
                        ->icon('heroicon-o-x-circle')
                        ->modalHeading('Hapus Dokumen Secara Permanen?')
                        ->modalDescription('PERINGATAN: Tindakan ini akan menghapus data DAN FILE dari sistem selamanya. Data History juga akan ikut terhapus.')
                        ->before(function (DokumenSop $record) {
                            // Hapus file fisik
                            if ($record->file_path) {
                                Storage::disk('public')->delete($record->file_path);
                            }
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