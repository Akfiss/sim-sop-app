<?php

namespace App\Filament\Pengusul\Resources;

use App\Filament\Pengusul\Resources\SopAktifResource\Pages;
use App\Models\DokumenSop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SopAktifResource extends Resource
{
    protected static ?string $model = DokumenSop::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    // --- 1. UBAH NAMA MENU ---
    protected static ?string $navigationLabel = 'Semua Dokumen SOP';
    protected static ?string $pluralModelLabel = 'Semua Dokumen SOP';

    protected static ?string $navigationGroup = 'Daftar Lengkap SOP';
    protected static ?int $navigationSort = 1;

    // --- LOGIC FILTER DATA ---
    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        $userUnitId = $user->units->first()?->id_unit;

        return parent::getEloquentQuery()
            // REVISI: Tampilkan AKTIF dan KADALUARSA (agar history terlihat)
            ->whereIn('status', ['AKTIF', 'KADALUARSA'])

            ->where(function (Builder $query) use ($userUnitId) {
                // A. Milik Unit Sendiri
                $query->where('id_unit_pemilik', $userUnitId)

                // B. SOP Lintas Unit (SOP AP) yang melibatkan unit ini
                ->orWhere(function (Builder $q) use ($userUnitId) {
                    $q->where('kategori_sop', 'SOP_AP')
                      ->where(function ($subQ) use ($userUnitId) {
                          $subQ->whereHas('unitTerkait', function ($relasi) use ($userUnitId) {
                              $relasi->where('tb_unit_kerja.id_unit', $userUnitId);
                          })
                          ->orWhere('is_all_units', true);
                      });
                });
            });
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul_sop')
                    ->label('Judul SOP')
                    ->searchable()
                    ->weight('bold')
                    ->limit(50)
                    ->description(fn (DokumenSop $record) => $record->nomor_sk ?? '-'),

                // --- 2. TAMBAH KOLOM STATUS ---
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'AKTIF' => 'success',      // Hijau
                        'KADALUARSA' => 'danger',  // Merah
                        default => 'gray',
                    }),
                // -----------------------------

                Tables\Columns\TextColumn::make('unitPemilik.nama_unit')
                    ->label('Unit Pemilik')
                    ->badge()
                    ->color('gray')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kategori_sop')
                    ->label('Kategori')
                    ->badge()
                    ->colors([
                        'info' => 'SOP',
                        'warning' => 'SOP_AP',
                    ]),

                Tables\Columns\TextColumn::make('tgl_berlaku')
                    ->label('Tgl Berlaku')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('tgl_kadaluarsa')
                    ->label('Kadaluarsa')
                    ->date('d M Y')
                    ->color(fn ($record) => $record->status === 'KADALUARSA' ? 'danger' : 'success')
                    ->sortable(),
            ])
            ->filters([
                // --- 3. TAMBAH FILTER STATUS ---
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'AKTIF' => 'Aktif',
                        'KADALUARSA' => 'Kadaluarsa',
                    ]),
                // ------------------------------

                Tables\Filters\SelectFilter::make('id_unit_pemilik')
                    ->relationship('unitPemilik', 'nama_unit')
                    ->label('Filter Unit')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Lihat Detail')
                        ->modalHeading('Detail SOP')
                        ->icon('heroicon-o-eye')
                        ->color('info'),

                    Tables\Actions\Action::make('download')
                        ->label('Unduh PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->url(fn (DokumenSop $record) => asset('storage/' . $record->file_path))
                        ->openUrlInNewTab(),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->tooltip('Menu')
            ])
            ->paginated([10, 25, 50])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSopAktifs::route('/'),
        ];
    }
}
