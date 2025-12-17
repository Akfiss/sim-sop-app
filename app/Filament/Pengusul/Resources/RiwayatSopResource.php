<?php

namespace App\Filament\Pengusul\Resources;

use App\Filament\Pengusul\Resources\RiwayatSopResource\Pages;
use App\Models\DokumenSop; // Note: The Resource model is DokumenSop, but we show History actions
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RiwayatSopResource extends Resource
{
    protected static ?string $model = DokumenSop::class; // We list SOPs, then show history in modal

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Riwayat SOP';
    protected static ?string $pluralModelLabel = 'Riwayat SOP';
    protected static ?string $navigationGroup = 'Daftar Lengkap SOP';
    protected static ?int $navigationSort = 2;

    // --- LOGIC UTAMA: FILTER DATA (HANYA UNIT TERKAIT/PEMILIK) ---
    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

        // 1. Ambil ID Unit milik User Pengusul (via tabel pivot tb_unit_user)
        $unitIds = DB::table('tb_unit_user')
            ->where('id_user', $user->id_user)
            ->pluck('id_unit')
            ->toArray();

        // 2. Filter SOP: Milik Unit Sendiri ATAU Unit Terkait ATAU All Units
        // DAN hanya SOP yang punya history (riwayat)
        return parent::getEloquentQuery()
            ->whereHas('riwayat') // Hanya tampilkan jika ada history
            ->where(function (Builder $query) use ($unitIds) {
                // A. Milik Unit Sendiri
                $query->whereIn('id_unit_pemilik', $unitIds)

                // B. ATAU SOP AP (Lintas Unit) / All Units
                ->orWhere(function (Builder $q) use ($unitIds) {
                    $q->where('kategori_sop', 'SOP_AP')
                      ->where(function ($subQ) use ($unitIds) {
                          $subQ->whereHas('unitTerkait', function ($relasi) use ($unitIds) {
                              $relasi->whereIn('tb_unit_kerja.id_unit', $unitIds);
                          })
                          ->orWhere('is_all_units', true);
                      });
                });
            })
            ->withoutGlobalScopes();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

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
                    ->description(fn (DokumenSop $record) => $record->nomor_sk ?? '-'),

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
                        'AKTIF' => 'success',
                        'KADALUARSA' => 'danger',
                        default => 'gray',
                    }),

                // 4. Jumlah Riwayat
                Tables\Columns\TextColumn::make('riwayat_count')
                    ->label('Total Riwayat')
                    ->counts('riwayat')
                    ->badge()
                    ->color('primary')
                    ->suffix(' perubahan'),

                // 5. Terakhir Diubah
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
            ])
            ->actions([
                // --- ACTION LIHAT RIWAYAT (MODAL) ---
                Tables\Actions\Action::make('lihat_riwayat')
                    ->label('Detail Riwayat')
                    ->icon('heroicon-o-clock')
                    ->color('info')
                    ->modalHeading(fn (DokumenSop $record) => 'Riwayat: ' . $record->judul_sop)
                    ->modalWidth('5xl')
                    ->modalSubmitAction(false) // Tombol submit disembunyikan (view only)
                    ->modalCancelActionLabel('Tutup')
                    // Memanggil View Blade yang sama (Re-use code)
                    ->modalContent(fn (DokumenSop $record) => view('filament.pengusul.modals.riwayat-timeline', [
                        'record' => $record,
                        'riwayatList' => $record->riwayat()
                            ->with('user')
                            ->orderBy('created_at', 'desc')
                            ->get(),
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
