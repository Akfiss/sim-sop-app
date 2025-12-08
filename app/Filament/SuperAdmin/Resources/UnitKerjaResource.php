<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\UnitKerjaResource\Pages;
use App\Models\UnitKerja;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;

class UnitKerjaResource extends Resource
{
    protected static ?string $model = UnitKerja::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase'; // Ikon koper/kerja
    protected static ?string $navigationLabel = 'Data Unit';
    protected static ?string $navigationGroup = 'Master Data'; // Opsional: Mengelompokkan menu
    protected static ?int $navigationSort = 2; // Urutan menu
    protected static ?string $modelLabel = 'Unit Kerja';
    protected static ?string $pluralModelLabel = 'Unit Kerja';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 1. Input ID Unit (Manual)
                Forms\Components\TextInput::make('id_unit')
                    ->label('Kode Unit')
                    ->required()
                    ->maxLength(5)
                    ->unique(ignoreRecord: true),

                // 2. Input Nama Unit
                Forms\Components\TextInput::make('nama_unit')
                    ->label('Nama Unit Kerja')
                    ->required()
                    ->maxLength(50),

                // 3. Dropdown Pilih Direktorat (Relasi)
                Forms\Components\Select::make('id_direktorat')
                    ->label('Direktorat')
                    ->relationship('direktorat', 'nama_direktorat') // Magic method Filament
                    ->searchable() // Agar bisa diketik saat mencari
                    ->preload() // Load data di awal agar cepat
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_unit')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_unit')
                    ->label('Nama Unit')
                    ->sortable()
                    ->searchable(),

                // Menampilkan nama direktorat dari relasi (bukan ID-nya)
                Tables\Columns\TextColumn::make('direktorat.nama_direktorat')
                    ->label('Direktorat')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                // Filter dropdown berdasarkan Direktorat
                Tables\Filters\SelectFilter::make('id_direktorat')
                    ->relationship('direktorat', 'nama_direktorat')
                    ->label('Filter Direktorat'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(false)   // Hapus Teks
                    ->tooltip('Edit Data'), // Ganti dengan Tooltip saat hover

                Tables\Actions\DeleteAction::make()
                    ->label(false)   // Hapus Teks
                    ->tooltip('Hapus Data')
                    ->successNotification(
                        Notification::make()
                        ->success()
                        ->title('Berhasil dihapus.')
                        ->body('Data unit kerja telah dihapus dari sistem.')
                    )
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnitKerja::route('/'),
            'create' => Pages\CreateUnitKerja::route('/create'),
            'edit' => Pages\EditUnitKerja::route('/{record}/edit'),
        ];
    }
}
