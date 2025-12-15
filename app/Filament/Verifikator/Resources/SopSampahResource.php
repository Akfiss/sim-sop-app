<?php

namespace App\Filament\Verifikator\Resources;

use App\Filament\Verifikator\Resources\SopSampahResource\Pages;
use App\Models\DokumenSop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\RiwayatSop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SopSampahResource extends Resource
{
    protected static ?string $model = DokumenSop::class;

    protected static ?string $navigationIcon = 'heroicon-o-trash';
    protected static ?string $navigationLabel = 'Sampah';
    protected static ?string $pluralModelLabel = 'Sampah';
    protected static ?string $navigationGroup = 'Manajemen SOP';
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'sampah';

    // Query khusus hanya data yang dihapus (Soft Delete)
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->onlyTrashed()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Form view only jika user ingin melihat detail sebelum restore
            Forms\Components\TextInput::make('judul_sop')
                ->label('Judul')
                ->disabled(),
            Forms\Components\TextInput::make('deleted_at')
                ->label('Dihapus Pada')
                ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul_sop')
                    ->label('Judul Dokumen')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (DokumenSop $record) => $record->nomor_sk ?? '-'),

                Tables\Columns\TextColumn::make('unitPemilik.nama_unit')
                    ->label('Unit Pemilik')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Waktu Dihapus')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->color('danger'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // HANYA ADA TOMBOL RESTORE (PULIHKAN)
                Tables\Actions\RestoreAction::make()
                    ->label('Pulihkan')
                    ->color('success')
                    ->icon('heroicon-o-arrow-path')
                    ->after(function (DokumenSop $record) {
                        RiwayatSop::create([
                            'id_sop' => $record->id_sop,
                            'id_user' => Auth::user()->id_user,
                            'status_sop' => 'AKTIF', // Saat dipulihkan, status kembali AKTIF
                            'catatan' => 'Dokumen dipulihkan kembali dari sampah oleh Verifikator.',
                            'dokumen_path' => $record->file_path,
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Pulihkan Terpilih'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSopSampahs::route('/'),
        ];
    }
}
