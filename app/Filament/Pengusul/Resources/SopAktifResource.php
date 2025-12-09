<?php

namespace App\Filament\Pengusul\Resources;

use App\Filament\Pengusul\Resources\SopAktifResource\Pages;
use App\Models\DokumenSop;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
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
