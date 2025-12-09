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

    // --- KONFIGURASI MENU ---
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'SOP Aktif';
    protected static ?string $pluralModelLabel = 'SOP Aktif';
    protected static ?string $navigationGroup = 'Daftar Lengkap SOP'; // Satu grup dengan yang tadi
    protected static ?int $navigationSort = 1; // Urutan ke-1 (di atas Pengajuan SOP)

    // --- LOGIC FILTER DATA (INTI PERMINTAAN ANDA) ---
    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        // Asumsi user punya relasi 'units' dan kita ambil unit pertamanya
        $userUnitId = $user->units->first()?->id_unit;

        return parent::getEloquentQuery()
            // 1. Pastikan HANYA status AKTIF
            ->where('status', 'AKTIF')

            ->where(function (Builder $query) use ($userUnitId) {
                // KONDISI A: SOP milik unit saya sendiri (baik saya yg buat atau teman se-unit)
                // Kita cek berdasarkan 'id_unit_pemilik' di tabel SOP
                $query->where('id_unit_pemilik', $userUnitId)

                // KONDISI B: SOP dari unit LAIN (Lintas Unit)
                ->orWhere(function (Builder $q) use ($userUnitId) {
                    $q->where('kategori_sop', 'SOP_AP') // Harus kategori SOP AP
                      ->where(function ($subQ) use ($userUnitId) {
                          // Opsi 1: Lintas unit yang spesifik memilih unit saya
                          $subQ->whereHas('unitTerkait', function ($relasi) use ($userUnitId) {
                              $relasi->where('tb_unit_kerja.id_unit', $userUnitId);
                          })
                          // Opsi 2: Atau SOP AP yang berlaku untuk ALL UNITS
                          ->orWhere('is_all_units', true);
                      });
                });
            });
    }

    // --- FORM (READ ONLY / VIEW SAJA) ---
    // Kita gunakan form yang sama dengan DokumenSopResource tapi didisable semua atau minimal view
    // Agar lebih cepat, kita return form kosong atau copy schema view action
    public static function form(Form $form): Form
    {
        return $form->schema([]); // Tidak butuh form edit karena ini view only
    }

    // --- TABEL ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul_sop')
                    ->label('Judul SOP')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (DokumenSop $record) => $record->nomor_sk ?? '-'),

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
                    ->sortable(),

                Tables\Columns\TextColumn::make('tgl_kadaluarsa')
                    ->label('Kadaluarsa')
                    ->date('d M Y')
                    ->color('danger')
                    ->sortable(),
            ])
            ->actions([
                // HANYA TOMBOL LIHAT & DOWNLOAD
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Lihat Detail')
                        ->modalHeading('Detail SOP')
                        // Gunakan Infolist dari Resource utama jika ingin tampilan sama,
                        // atau biarkan default filament view
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
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSopAktifs::route('/'),
        ];
    }
}
