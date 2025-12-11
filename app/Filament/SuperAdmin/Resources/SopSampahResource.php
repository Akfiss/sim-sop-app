<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\SopSampahResource\Pages;
use App\Models\DokumenSop;
use App\Models\RiwayatSop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SopSampahResource extends Resource
{
    protected static ?string $model = DokumenSop::class;

    protected static ?string $navigationIcon = 'heroicon-o-trash';
    protected static ?string $navigationLabel = 'Sampah';
    protected static ?string $modelLabel = 'Dokumen Sampah';
    protected static ?string $navigationGroup = 'Manajemen SOP';
    protected static ?int $navigationSort = 2;
    protected static ?string $slug = 'sampah-sop';

    // Query hanya menampilkan data yang sudah di-soft delete
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->onlyTrashed();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]); // Read-only di Trash
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul_sop')
                    ->label('Judul Dokumen')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('unitPemilik.nama_unit')
                    ->label('Unit')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->label('Dihapus Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->color('danger'),
            ])
            ->actions([
                // 1. RESTORE
                Tables\Actions\RestoreAction::make()
                    ->label('Pulihkan')
                    ->after(function (DokumenSop $record) {
                        // Log History
                        RiwayatSop::create([
                            'id_sop' => $record->id_sop,
                            'id_user' => Auth::id(),
                            'status_sop' => $record->status, // Status lama tetap
                            'catatan' => 'Dokumen dipulihkan dari sampah (Trash) oleh Admin.',
                            'dokumen_path' => $record->file_path
                        ]);
                    }),

                // 2. FORCE DELETE
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSopSampahs::route('/'),
        ];
    }
}
