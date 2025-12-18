<?php

namespace App\Filament\Direksi\Resources;

use App\Filament\Direksi\Resources\MonitoringSopResource\Pages;
use App\Models\DokumenSop;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class MonitoringSopResource extends Resource
{
    protected static ?string $model = DokumenSop::class;
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationLabel = 'Monitoring SOP';
    protected static ?string $pluralModelLabel = 'Monitoring SOP';
    protected static ?string $navigationGroup = 'Data Seluruh SOP';
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'monitoring-sop';


    // --- 1. SETTING READ ONLY ---
    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }

    // --- 2. QUERY SCOPE (GLOBAL ACCESS) ---
    // Update: Menampilkan SEMUA data tanpa batasan Direktorat user login
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            // Kita hanya batasi Status (Hanya dokumen final yang boleh dilihat Direksi)
            ->whereIn('status', ['AKTIF', 'KADALUARSA'])
            ->withoutGlobalScopes();
    }

    // Form kosong karena hanya View Only
    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    // --- 3. INFOLIST (POP-UP DETAIL) ---
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Header
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('judul_sop')
                            ->label('Judul Dokumen')
                            ->weight('bold')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->columnSpanFull(),

                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('nomor_sk')
                                    ->label('Nomor SK')
                                    ->placeholder('-'),

                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'AKTIF' => 'success',
                                        'KADALUARSA' => 'danger',
                                        default => 'gray',
                                    }),

                                Infolists\Components\TextEntry::make('unitPemilik.nama_unit')
                                    ->label('Unit Pemilik') // Penting agar tau punya siapa
                                    ->badge()
                                    ->color('info'),

                                Infolists\Components\TextEntry::make('unitTerkait.nama_unit')
                                    ->label('Unit Terkait')
                                    ->badge()
                                    ->color('warning')
                                    ->placeholder('-')
                                    ->formatStateUsing(fn ($state, $record) => $record->is_all_units ? 'SELURUH UNIT' : $state),
                            ]),
                    ]),

                // Validitas
                Infolists\Components\Section::make('Validitas Dokumen')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('tgl_pengesahan')
                                    ->label('Disahkan')
                                    ->date('d F Y')
                                    ->icon('heroicon-m-pencil-square'),

                                Infolists\Components\TextEntry::make('tgl_review_berikutnya')
                                    ->label('Jadwal Review')
                                    ->date('d F Y')
                                    ->icon('heroicon-m-clock')
                                    ->color('warning'),

                                Infolists\Components\TextEntry::make('tgl_kadaluarsa')
                                    ->label('Kadaluarsa')
                                    ->date('d F Y')
                                    ->icon('heroicon-m-calendar-days')
                                    ->color('danger'),
                            ]),
                    ]),

                // Preview PDF
                Infolists\Components\Section::make('Preview Dokumen')
                    ->schema([
                        Infolists\Components\TextEntry::make('file_path')
                            ->label('')
                            ->view('filament.infolists.pdf-viewer')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul_sop')
                    ->label('Judul Dokumen')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->judul_sop)
                    ->description(fn ($record) => $record->nomor_sk ?? '-')
                    ->weight('bold'),

                // Kolom Unit Pemilik
                Tables\Columns\TextColumn::make('unitPemilik.nama_unit')
                    ->label('Unit Pemilik')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),

                // Kolom Direktorat (Baru: Agar Direksi mudah melihat ini dari Dir mana)
                Tables\Columns\TextColumn::make('unitPemilik.direktorat.nama_direktorat')
                    ->label('Direktorat')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true) // Tersembunyi default agar tidak penuh
                    ->searchable(),

                Tables\Columns\TextColumn::make('kategori_sop')
                    ->badge()
                    ->colors(['info' => 'SOP', 'warning' => 'SOP_AP']),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'AKTIF' => 'success',
                        'KADALUARSA' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('tgl_berlaku')
                    ->label('Tgl Berlaku')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tgl_kadaluarsa')
                    ->label('Kadaluarsa')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($state) => $state && Carbon::parse($state)->isPast() ? 'danger' : 'success'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // 1. Filter Direktorat (BARU & PENTING)
                // Memungkinkan Direksi memfilter SOP berdasarkan Direktorat tertentu
                Tables\Filters\SelectFilter::make('direktorat')
                    ->label('Filter Direktorat')
                    ->relationship('unitPemilik.direktorat', 'nama_direktorat')
                    ->searchable()
                    ->preload(),

                // 2. Filter Unit Kerja (Global, semua unit muncul)
                Tables\Filters\SelectFilter::make('id_unit_pemilik')
                    ->label('Filter Unit Kerja')
                    ->relationship('unitPemilik', 'nama_unit')
                    ->searchable()
                    ->preload(),

                // 3. Filter Kategori
                Tables\Filters\SelectFilter::make('kategori_sop')
                    ->options([
                        'SOP' => 'SOP Internal',
                        'SOP_AP' => 'SOP Antar Profesi',
                    ]),

                // 4. Filter Status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'AKTIF' => 'Aktif',
                        'KADALUARSA' => 'Kadaluarsa',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Detail')
                        ->modalHeading('Preview Detail SOP')
                        ->color('info')
                        ->icon('heroicon-o-eye'),

                    Tables\Actions\Action::make('download')
                        ->label('Unduh PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->url(fn (DokumenSop $record) => asset('storage/' . $record->file_path))
                        ->openUrlInNewTab(),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('primary')
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSopMonitoring::route('/'),
        ];
    }
}
