<?php

namespace App\Filament\Pengusul\Resources;

use App\Filament\Pengusul\Resources\RiwayatSopResource\Pages;
use App\Models\DokumenSop;
use App\Models\RiwayatSop;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RiwayatSopResource extends Resource
{
    protected static ?string $model = DokumenSop::class;

    // --- KONFIGURASI MENU ---
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Riwayat SOP';
    protected static ?string $pluralModelLabel = 'Riwayat SOP';
    protected static ?string $navigationGroup = 'Daftar Lengkap SOP';
    protected static ?int $navigationSort = 3;
    protected static ?string $slug = 'riwayat-sop';

    // --- FILTER DATA (HANYA SOP MILIK PENGUSUL) ---
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('created_by', Auth::user()->id_user)
            ->whereHas('riwayat') // Hanya tampilkan SOP yang memiliki riwayat
            ->withoutGlobalScopes();
    }

    // --- FORM (TIDAK DIGUNAKAN, VIEW ONLY) ---
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

                // 2. Kategori
                Tables\Columns\TextColumn::make('kategori_sop')
                    ->label('Kategori')
                    ->badge()
                    ->colors([
                        'info' => 'SOP',
                        'warning' => 'SOP_AP',
                    ]),

                // 3. Status Terkini
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Terkini')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'DRAFT' => 'gray',
                        'DALAM REVIEW' => 'warning',
                        'REVISI' => 'danger',
                        'AKTIF' => 'success',
                        'KADALUARSA' => 'gray',
                        'ARCHIVED' => 'gray',
                        default => 'gray',
                    }),

                // 4. Jumlah Riwayat
                Tables\Columns\TextColumn::make('riwayat_count')
                    ->label('Total Riwayat')
                    ->counts('riwayat')
                    ->badge()
                    ->color('info')
                    ->suffix(' perubahan'),

                // 5. Terakhir Diubah
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->color('gray'),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'DRAFT' => 'Draft',
                        'DALAM REVIEW' => 'Dalam Review',
                        'REVISI' => 'Revisi',
                        'AKTIF' => 'Aktif',
                        'KADALUARSA' => 'Kadaluarsa',
                        'ARCHIVED' => 'Archived',
                    ]),
            ])
            ->actions([
                // Tombol Lihat Riwayat
                Tables\Actions\Action::make('lihat_riwayat')
                    ->label('Lihat Riwayat')
                    ->icon('heroicon-o-clock')
                    ->color('primary')
                    ->modalHeading(fn (DokumenSop $record) => 'Riwayat: ' . $record->judul_sop)
                    ->modalWidth('5xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
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
