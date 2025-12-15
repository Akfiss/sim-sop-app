<?php

namespace App\Filament\Direksi\Resources;

use App\Filament\Direksi\Resources\DokumenSopResource\Pages;
use App\Models\DokumenSop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Filament\Infolists; // Untuk fitur View/Mata
use Filament\Infolists\Infolist;
use Illuminate\Support\HtmlString;

class DokumenSopResource extends Resource
{
    protected static ?string $model = DokumenSop::class;
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationLabel = 'Monitoring SOP';
    protected static ?string $pluralModelLabel = 'Data SOP Direktorat';

    // --- 1. SETTING READ ONLY ---
    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }

    // --- 2. QUERY SCOPE (PENTING!) ---
    // Hanya tampilkan SOP yang Unit Pemiliknya berada di bawah Direktorat user yang login
    public static function getEloquentQuery(): Builder
    {
        $userDirektorat = Auth::user()->id_direktorat;

        return parent::getEloquentQuery()
            ->where(function (Builder $query) use ($userDirektorat) {
                // 1. SOP yang Dimiliki oleh Unit di bawah Direktorat user
                $query->whereHas('unitPemilik', function (Builder $q) use ($userDirektorat) {
                    $q->where('id_direktorat', $userDirektorat);
                })
                // 2. ATAU SOP Kategori 'SOP_AP' yang terkait dengan Unit di bawah Direktorat user
                ->orWhere(function (Builder $q) use ($userDirektorat) {
                     $q->where('kategori_sop', 'SOP_AP')
                       ->where(function (Builder $subQ) use ($userDirektorat) {
                           // 2a. Berlaku untuk SEMUA Unit
                           $subQ->where('is_all_units', true)
                           // 2b. Atau Terkait secara spesifik dengan unit di bawah direktorat
                           ->orWhereHas('unitTerkait', function (Builder $relQ) use ($userDirektorat) {
                               $relQ->where('id_direktorat', $userDirektorat);
                           });
                       });
                });
            })
            ->withoutGlobalScopes();
    }

    // Form kosong karena hanya View Only
    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    // --- 3. TAMPILAN VIEW (POP UP / HALAMAN) ---
    // --- INFOLIST (POP-UP DETAIL & PREVIEW SOP) ---
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Header: Judul Besar & Status
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('judul_sop')
                            ->label('Judul Dokumen')
                            ->weight('bold')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->columnSpanFull(),

                        // Gunakan Grid 2 Kolom untuk detail
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('nomor_sk')
                                    ->label('Nomor SK')
                                    ->placeholder('-'),

                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'DALAM REVIEW' => 'warning',
                                        'REVISI' => 'danger',
                                        'AKTIF' => 'success',
                                        default => 'gray',
                                    }),

                                Infolists\Components\TextEntry::make('unitTerkait.nama_unit')
                                    ->label('Unit Terkait')
                                    ->badge()
                                    ->color('info')
                                    ->placeholder('Internal Unit')
                                    ->formatStateUsing(function ($state, DokumenSop $record) {
                                        if ($record->is_all_units) {
                                            return 'SELURUH UNIT / INSTALASI';
                                        }
                                        return $state;
                                    })
                                    ->color(fn (DokumenSop $record) => $record->is_all_units ? 'success' : 'info'),
                            ]),
                    ]),

                // Section Validitas (3 TANGGAL PENTING)
                Infolists\Components\Section::make('Validitas Dokumen')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                // 1. Tgl Disahkan (TTD)
                                Infolists\Components\TextEntry::make('tgl_pengesahan')
                                    ->label('Disahkan (TTD)')
                                    ->date('d F Y')
                                    ->icon('heroicon-m-pencil-square')
                                    ->placeholder('-'),

                                // 2. Review Date
                                Infolists\Components\TextEntry::make('tgl_review_berikutnya')
                                    ->label('Review Date')
                                    ->date('d F Y')
                                    ->icon('heroicon-m-clock')
                                    ->color('warning')
                                    ->placeholder('-'),

                                // 3. Expired Date
                                Infolists\Components\TextEntry::make('tgl_kadaluarsa')
                                    ->label('Expired Date')
                                    ->date('d F Y')
                                    ->icon('heroicon-m-calendar-days')
                                    ->color('danger')
                                    ->placeholder('-'),
                            ]),
                    ]),

                // PREVIEW PDF (DITENGAHKAN)
                Infolists\Components\Section::make('Preview Dokumen')
                    ->schema([
                        Infolists\Components\TextEntry::make('file_path')
                            ->label('') // Label kosong agar bersih
                            ->view('filament.infolists.pdf-viewer')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(), // Bisa dilipat jika ingin ringkas
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

                // Tampilkan Nama Unit (Agar Direksi tau ini SOP punya unit mana)
                Tables\Columns\TextColumn::make('unitPemilik.nama_unit')
                    ->label('Unit Pemilik')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('kategori_sop')
                    ->badge()
                    ->colors(['info' => 'SOP', 'warning' => 'SOP_AP']),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'AKTIF' => 'success',
                        'KADALUARSA' => 'danger',
                    }),

                // 5. Tanggal Berlaku
                Tables\Columns\TextColumn::make('tgl_berlaku')
                    ->label('Tgl Berlaku')
                    ->date('d M Y')
                    ->sortable(),

                // 6. Tanggal Kadaluarsa
                Tables\Columns\TextColumn::make('tgl_kadaluarsa')
                    ->label('Kadaluarsa')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($state) => $state && Carbon::parse($state)->isPast() ? 'danger' : 'success')
                    ->description(function (DokumenSop $record) {
                        if ($record->tgl_kadaluarsa && now()->diffInDays($record->tgl_kadaluarsa, false) <= 30) {
                            return '🚨 Segera Habis';
                        }
                        return null;
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // Filter Unit Kerja (Memudahkan Direksi filter per anak buah)
                Tables\Filters\SelectFilter::make('id_unit_pemilik')
                    ->label('Filter per Unit')
                    ->relationship('unitPemilik', 'nama_unit', function(Builder $query) {
                        // Hanya munculkan unit di bawah direktorat dia
                        return $query->where('id_direktorat', Auth::user()->id_direktorat);
                    })
                    ->searchable()
                    ->preload(),

                // Filter Kategori
                Tables\Filters\SelectFilter::make('kategori_sop')
                    ->options([
                        'SOP' => 'SOP Internal',
                        'SOP_AP' => 'SOP Antar Profesi',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'AKTIF' => 'Aktif',
                        'KADALUARSA' => 'Kadaluarsa',
                    ]),
            ])
            ->actions([
                // Action Group: Lihat & Download
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
            'index' => Pages\ListDokumenSops::route('/'),
            // 'view' => Pages\ViewDokumenSop::route('/{record}'), // Halaman view detail full page
        ];
    }
}
