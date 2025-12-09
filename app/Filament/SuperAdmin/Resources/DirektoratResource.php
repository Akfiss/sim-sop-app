<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\DirektoratResource\Pages;
use App\Models\Direktorat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DirektoratResource extends Resource
{
    protected static ?string $model = Direktorat::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office'; // Ikon gedung
    protected static ?string $navigationLabel = 'Data Direktorat';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Direktorat';
    protected static ?string $pluralModelLabel = 'Direktorat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Input ID Direktorat (Manual karena CHAR)
                Forms\Components\TextInput::make('id_direktorat')
                    ->label('Kode Direktorat')
                    ->required()
                    ->maxLength(5)
                    ->unique(ignoreRecord: true), // Cek unik kecuali saat edit

                // Input Nama Direktorat
                Forms\Components\TextInput::make('nama_direktorat')
                    ->label('Nama Direktorat')
                    ->required()
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_direktorat')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_direktorat')
                    ->label('Nama Direktorat')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            // --- BAGIAN ACTION ICON ONLY ---
            ->actions([
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\EditAction::make()
                        ->label('Edit')
                        ->color('warning'),

                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->color('danger')
                        ->successNotification(
                            Notification::make()
                            ->success()
                            ->title('Berhasil dihapus.')
                            ->body('Data direktorat telah dihapus dari sistem.')
                        )
                ])
                ->icon('heroicon-m-ellipsis-vertical') // Ikon titik tiga
                ->color('gray') // Warna ikon utama
                ->tooltip('Menu Aksi') // Tooltip saat hover ikon grup
                ->extraAttributes(['class' => 'w-auto min-w-[150px]']), // Lebar minimal agar tidak terlalu kecil
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // ... function getRelations dan getPages biarkan default
    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDirektorats::route('/'),
            'create' => Pages\CreateDirektorat::route('/create'),
            'edit' => Pages\EditDirektorat::route('/{record}/edit'),
        ];
    }
}
