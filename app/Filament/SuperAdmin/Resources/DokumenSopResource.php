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
                                'AKTIF' => 'Aktif',
                                'KADALUARSA' => 'Kadaluarsa',
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
        ->recordUrl(null)
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
                        'KADALUARSA' => 'Kadaluarsa',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    // 4. Delete
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->tooltip('Hapus Data')
                        ->modalHeading('Hapus Dokumen SOP')
                        ->modalWidth('4xl')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->after(function (DokumenSop $record) {
                            RiwayatSop::create([
                                'id_sop' => $record->id_sop,
                                'id_user' => Auth::user()->id_user,
                                'status_sop' => 'KADALUARSA', // Kita anggap saat dihapus statusnya non-aktif (Kadaluarsa/Arsip)
                                'catatan' => 'Dokumen dipindahkan ke sampah (Soft Delete) oleh Admin.',
                                'dokumen_path' => $record->file_path,
                            ]);
                        }),

                    // 5. Restore (Pulihkan dari Sampah)
                    Tables\Actions\RestoreAction::make()
                    ->after(function (DokumenSop $record) {
                        RiwayatSop::create([
                            'id_sop' => $record->id_sop,
                            'id_user' => Auth::user()->id_user,
                            'status_sop' => $record->status,
                            'catatan' => 'Dokumen dipulihkan dari sampah (Restore) oleh Admin.',
                            'dokumen_path' => $record->file_path,
                        ]);
                    }),
                ])
                ->tooltip('Menu Aksi')
                ->icon('heroicon-m-ellipsis-vertical'),
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
