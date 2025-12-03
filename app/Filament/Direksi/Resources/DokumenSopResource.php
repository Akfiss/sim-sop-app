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
            ->whereHas('unitPemilik', function (Builder $query) use ($userDirektorat) {
                $query->where('id_direktorat', $userDirektorat);
            });
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
                    ->label('Unit Kerja')
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
                        'DALAM REVIEW' => 'warning',
                        'REVISI' => 'danger',
                        'AKTIF' => 'success',
                        'KADALUARSA' => 'gray',
                    }),

                Tables\Columns\TextColumn::make('tgl_kadaluarsa')
                    ->label('Masa Berlaku')
                    ->date('d M Y')
                    ->sortable(),
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

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'AKTIF' => 'Aktif',
                        'DALAM REVIEW' => 'Sedang Proses',
                        'KADALUARSA' => 'Kadaluarsa',
                    ]),
            ])
            ->actions([
                // Hanya Action View (Mata)
                Tables\Actions\ViewAction::make()
                    ->label(false)
                    ->tooltip('Lihat Detail')
                    ->modalWidth('4xl')
                    ->modalHeading(fn ($record) => $record->judul_sop),
            ]);
    }

    // --- 3. TAMPILAN VIEW (POP UP / HALAMAN) ---
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Detail Dokumen')
                    ->schema([
                        Infolists\Components\Grid::make(3)->schema([
                            Infolists\Components\TextEntry::make('nomor_sk')->label('No. SK'),
                            Infolists\Components\TextEntry::make('unitPemilik.nama_unit')->label('Unit Pemilik')->badge(),
                            Infolists\Components\TextEntry::make('status')->badge(),
                        ]),
                        Infolists\Components\TextEntry::make('tgl_berlaku')->label('Tanggal Berlaku')->date('d F Y'),
                    ]),

                Infolists\Components\Section::make('Preview File')
                    ->schema([
                        Infolists\Components\TextEntry::make('file_path')
                            ->label('')
                            ->formatStateUsing(fn ($state) => new HtmlString(
                                '<iframe src="'.asset('storage/'.$state).'" style="width:100%; height:500px; border:none; border-radius:8px;"></iframe>'
                            ))
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDokumenSops::route('/'),
            'view' => Pages\ViewDokumenSop::route('/{record}'), // Halaman view detail full page
        ];
    }
}
