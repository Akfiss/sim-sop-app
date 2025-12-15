<?php

namespace App\Filament\Verifikator\Resources;

use App\Filament\Verifikator\Resources\RiwayatSopResource\Pages;
use App\Models\DokumenSop;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RiwayatSopResource extends Resource
{
    protected static ?string $model = DokumenSop::class;

    // --- KONFIGURASI MENU ---
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Riwayat SOP';
    protected static ?string $pluralModelLabel = 'Riwayat SOP';
    protected static ?string $navigationGroup = 'Manajemen SOP'; // Satu grup dengan Verifikasi SOP
    protected static ?int $navigationSort = 2; // Urutan ke-2 (setelah Verifikasi SOP)
    protected static ?string $slug = 'riwayat-sop';

    // --- FILTER DATA ---
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('riwayat') // Hanya tampilkan SOP yang memiliki riwayat
            ->withoutGlobalScopes();
    }

    // --- FORM (VIEW ONLY) ---
    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    // --- TABEL DAFTAR SOP ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Judul SOP
                Tables\Columns\TextColumn::make('judul_sop')
                    ->label('Judul Dokumen')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->weight('bold')
                    ->description(fn (DokumenSop $record) => $record->nomor_sk ?? 'Tanpa Nomor SK'),

                // 2. Unit Pemilik (Tambahan untuk Verifikator)
                Tables\Columns\TextColumn::make('unitPemilik.nama_unit')
                    ->label('Unit Pemilik')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),

                // 3. Kategori
                Tables\Columns\TextColumn::make('kategori_sop')
                    ->label('Kategori')
                    ->badge()
                    ->colors([
                        'info' => 'SOP',
                        'warning' => 'SOP_AP',
                    ]),

                // 4. Status Terkini (Disesuaikan dengan Enum Baru)
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Terkini')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'AKTIF' => 'success',
                        'KADALUARSA' => 'danger',
                        default => 'gray',
                    }),

                // 5. Jumlah Riwayat
                Tables\Columns\TextColumn::make('riwayat_count')
                    ->label('Total Riwayat')
                    ->counts('riwayat')
                    ->badge()
                    ->color('primary')
                    ->suffix(' perubahan'),

                // 6. Terakhir Diubah
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Update')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->color('gray')
                    ->toggleable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'AKTIF' => 'Aktif',
                        'KADALUARSA' => 'Kadaluarsa',
                    ]),
                Tables\Filters\SelectFilter::make('id_unit_pemilik')
                    ->relationship('unitPemilik', 'nama_unit')
                    ->label('Filter Unit')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                // Tombol Lihat Riwayat
                Tables\Actions\Action::make('lihat_riwayat')
                    ->label('Detail Riwayat')
                    ->icon('heroicon-o-clock')
                    ->color('info')
                    ->modalHeading(fn (DokumenSop $record) => 'Riwayat: ' . $record->judul_sop)
                    ->modalWidth('5xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    // MENGGUNAKAN VIEW YANG SUDAH ADA DI PENGUSUL
                    ->modalContent(fn (DokumenSop $record) => view('filament.pengusul.modals.riwayat-timeline', [
                        'record' => $record,
                        'riwayatList' => $record->riwayat()->with('user')->orderBy('created_at', 'desc')->get(),
                    ])),
            ])
            ->paginated([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRiwayatSops::route('/'),
        ];
    }
}
