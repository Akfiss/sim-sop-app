<?php

namespace App\Filament\Verifikator\Resources;

use App\Filament\Verifikator\Resources\DokumenSopResource\Pages;
use App\Models\DokumenSop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use App\Models\Notifikasi; // Pastikan Model Notifikasi ada
use App\Models\RiwayatSop; // Pastikan Model Riwayat ada (jika mau catat log)
use Carbon\Carbon;

class DokumenSopResource extends Resource
{
    protected static ?string $model = DokumenSop::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Verifikasi SOP';
    protected static ?string $pluralModelLabel = 'Verifikasi SOP';
    protected static ?string $navigationGroup = 'Manajemen SOP';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dokumen')
                    ->description('Isi detail identitas SOP di sini.')
                    ->schema([
                        // 1. Pilih Unit Pemilik (PENTING BAGI VERIFIKATOR)
                        Forms\Components\Select::make('id_unit_pemilik')
                            ->label('Unit Pemilik SOP')
                            ->relationship('unitPemilik', 'nama_unit')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                        // 2. Judul SOP
                        Forms\Components\TextInput::make('judul_sop')
                            ->label('Judul SOP')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        // 3. Nomor SK
                        Forms\Components\TextInput::make('nomor_sk')
                            ->label('Nomor SK')
                            ->required()
                            ->placeholder('Contoh: 001/SK/DIR/2025')
                            ->maxLength(50),
                        // 4. Kategori SOP
                        Forms\Components\Select::make('kategori_sop')
                            ->options([
                                'SOP' => 'SOP Internal',
                                'SOP_AP' => 'SOP AP Unit Terkait',
                            ])
                            ->placeholder('Pilih Kategori...')
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('unitTerkait', [])),

                        // 5. Toggle All Units (Hanya jika SOP AP)
                        Forms\Components\Toggle::make('is_all_units')
                            ->label('Berlaku untuk SELURUH Unit?')
                            ->visible(fn (Forms\Get $get) => $get('kategori_sop') === 'SOP_AP')
                            ->live()
                            ->onColor('success')
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                if ($state) {
                                    $set('unitTerkait', []);
                                }
                            }),

                        // 6. Field Unit Terkait (Muncul jika SOP AP & Tidak All Units)
                        Forms\Components\Select::make('unitTerkait')
                            ->label('Pilih Unit Terkait')
                            ->relationship('unitTerkait', 'nama_unit')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->key(fn (Forms\Get $get) => 'unit_input_' . ($get('is_all_units') ? 'locked' : 'active'))
                            // VISIBLE: Tetap muncul selama kategori SOP AP (meskipun All Units aktif)
                            ->visible(fn (Forms\Get $get) => $get('kategori_sop') === 'SOP_AP')

                            // DISABLED: Mati jika All Units dicentang
                            ->disabled(fn (Forms\Get $get) => $get('is_all_units'))

                            // REQUIRED: Hanya wajib jika SOP AP dan All Units MATI
                            ->required(fn (Forms\Get $get) =>
                                $get('kategori_sop') === 'SOP_AP' &&
                                !$get('is_all_units')
                            )

                            // PLACEHOLDER DINAMIS: Memberi info saat disabled
                            ->placeholder(fn (Forms\Get $get) =>
                                $get('is_all_units')
                                    ? 'Otomatis berlaku untuk semua unit (Disabled)'
                                    : 'Pilih unit...'
                            )
                            ->columnSpanFull(),
                    ])->columns(2),

                // SECTION 2: TANGGAL PENTING
                Forms\Components\Section::make('Validitas Dokumen')
                    ->schema([
                        Forms\Components\DatePicker::make('tgl_pengesahan')
                            ->label('Tgl Pengesahan')
                            ->required()
                            ->displayFormat('d/m/Y'),

                        // LOGIC OTOMATIS TANGGAL
                        Forms\Components\DatePicker::make('tgl_berlaku')
                            ->label('Tgl Berlaku')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                if ($state) {
                                    $tglBerlaku = Carbon::parse($state);
                                    $tglKadaluarsa = $tglBerlaku->copy()->addYears(3);

                                    // LOGIC BARU: Cek apakah hasil perhitungan sudah lewat hari ini?
                                    if ($tglKadaluarsa->isPast()) {
                                        // Jika sudah kadaluarsa, Review Date dikosongkan (null)
                                        $set('tgl_review_berikutnya', null);
                                    } else {
                                        // Jika belum, set Review Date (+1 Tahun)
                                        $set('tgl_review_berikutnya', $tglBerlaku->copy()->addYear()->format('Y-m-d'));
                                    }

                                    // Set Tanggal Kadaluarsa
                                    $set('tgl_kadaluarsa', $tglKadaluarsa->format('Y-m-d'));
                                }
                            }),

                        // Field Readonly (Otomatis terisi)
                        Forms\Components\DatePicker::make('tgl_review_berikutnya')
                            ->label('Review Tahunan')
                            ->readOnly() // User tidak perlu edit manual
                            ->hint('Otomatis (+1 Thn)'),

                        Forms\Components\DatePicker::make('tgl_kadaluarsa')
                            ->label('Tgl Kadaluarsa')
                            ->readOnly() // User tidak perlu edit manual
                            ->hint('Otomatis (+3 Thn)'),
                    ])->columns(3),

                // SECTION 3: UPLOAD FILE
                Forms\Components\Section::make('File Dokumen')
                    ->schema([
                        // 7. File Upload
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Unggah Dokumen PDF')
                            ->placeholder('Klik atau seret file ke sini untuk mengunggah')
                            ->disk('public') // Simpan di storage public
                            ->directory('dokumen-sop')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(1024) // Maksimal 1MB
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

        // --- 1. Buat Method Baru untuk menyimpan Schema (Agar bisa dipanggil dari luar) ---
    public static function getInfolistSchema(): array
    {
        return [
            // Header: Judul Besar & Status
            Infolists\Components\Section::make()
                ->schema([
                    Infolists\Components\TextEntry::make('judul_sop')
                        ->label('Judul Dokumen')
                        ->weight('bold')
                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                        ->columnSpanFull(),

                    // Grid 2 Kolom
                    Infolists\Components\Grid::make(2)
                        ->schema([
                            Infolists\Components\TextEntry::make('nomor_sk')
                                ->label('Nomor SK')
                                ->placeholder('-'),

                            Infolists\Components\TextEntry::make('status')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'KADALUARSA' => 'danger',
                                    'AKTIF' => 'success',
                                    default => 'success',
                                }),

                            Infolists\Components\TextEntry::make('unitTerkait.nama_unit')
                                ->label('Unit Terkait')
                                ->badge()
                                ->color(fn ($record) => $record->is_all_units ? 'success' : 'info')
                                ->getStateUsing(function ($record) {
                                    if ($record->is_all_units) return 'SELURUH UNIT / INSTALASI';
                                    $units = $record->unitTerkait->pluck('nama_unit');
                                    return $units->count() > 0 ? $units : 'Internal Unit';
                                }),
                        ]),
                ]),

            // Section Validitas
            Infolists\Components\Section::make('Validitas Dokumen')
                ->schema([
                    Infolists\Components\Grid::make(3)
                        ->schema([
                            Infolists\Components\TextEntry::make('tgl_pengesahan')->label('Disahkan (TTD)')->date('d F Y')->icon('heroicon-m-pencil-square')->placeholder('-'),
                            Infolists\Components\TextEntry::make('tgl_review_berikutnya')->label('Review Date')->date('d F Y')->icon('heroicon-m-clock')->color('warning')->placeholder('-'),
                            Infolists\Components\TextEntry::make('tgl_kadaluarsa')->label('Expired Date')->date('d F Y')->icon('heroicon-m-calendar-days')->color('danger')->placeholder('-'),
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
        ];
    }

     // --- 2. Update Method infolist() bawaan Resource agar mengambil dari fungsi di atas ---
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(self::getInfolistSchema());
    }

    // --- 3. UPDATE TABEL  ---
    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                // Judul SOP + Tanggal Upload
                Tables\Columns\TextColumn::make('judul_sop')
                    ->label('Judul Dokumen')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->weight('bold')
                    ->tooltip(fn (DokumenSop $record) => $record->judul_sop)
                    ->description(fn (DokumenSop $record) =>
                        'Diupload: ' . $record->created_at->translatedFormat('d F Y H:i')
                    ),

                // Kategori
                Tables\Columns\TextColumn::make('kategori_sop')
                    ->label('Kategori')
                    ->badge()
                    ->colors([
                        'info' => 'SOP',
                        'warning' => 'SOP_AP',
                    ]),

                Tables\Columns\TextColumn::make('unitPemilik.nama_unit')
                    ->label('Unit')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                // Unit Terkait (LOGIC VISUAL ALL UNITS)
                // Kita gunakan TextColumn untuk menampilkan unit, tapi jika is_all_units=true,
                // kita paksa tampilkan teks "SELURUH UNIT"
                Tables\Columns\TextColumn::make('unitTerkait.nama_unit')
                    ->label('Unit Terkait')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(2)
                    ->toggleable(isToggledHiddenByDefault: true) // Tersembunyi by default
                    // Logic placeholder: Jika relasi kosong tapi is_all_units nyala, tampilkan teks khusus
                    ->placeholder(fn (DokumenSop $record) =>
                        $record->is_all_units ? 'SELURUH UNIT / INSTALASI' : '-'
                    )
                    // Beri warna hijau jika All Units
                    ->color(fn (DokumenSop $record) => $record->is_all_units ? 'success' : null)
                    ->weight(fn (DokumenSop $record) => $record->is_all_units ? 'bold' : null),

                // Penanda All Units (Opsional: Icon Column)
                Tables\Columns\IconColumn::make('is_all_units')
                    ->label('All Units?')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->toggleable(isToggledHiddenByDefault: true),

                // Status
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'AKTIF' => 'success',
                        'KADALUARSA' => 'danger',
                        default => 'success',
                    }),
                // Review Date
                Tables\Columns\TextColumn::make('tgl_review_berikutnya')
                    ->label('Review Date')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable()
                    // --- REVISI LOGIKA PENANDA ---
                    ->description(function (DokumenSop $record) {
                        // Cek hanya jika tanggal ada dan status AKTIF
                        if (!$record->tgl_review_berikutnya || $record->status !== 'AKTIF') return null;

                        $reviewDate = Carbon::parse($record->tgl_review_berikutnya);
                        $diff = now()->diffInDays($reviewDate, false); // false agar return negatif jika lewat

                        // HANYA TAMPILKAN JIKA H-30 SAMPAI HARI H (Positive 0 - 30)
                        // Jika sudah lewat (negatif), return null (tidak ada deskripsi)
                        if ($diff >= 0 && $diff <= 30) {
                            return '⏰ Review dalam ' . intval($diff) . ' hari';
                        }

                        return null;
                    })
                    ->color(function (DokumenSop $record) {
                        if (!$record->tgl_review_berikutnya || $record->status !== 'AKTIF') return null;

                        $reviewDate = Carbon::parse($record->tgl_review_berikutnya);
                        $diff = now()->diffInDays($reviewDate, false);

                        // HANYA WARNA ORANYE JIKA H-30
                        // Jika lewat, kembali ke warna default (null)
                        if ($diff >= 0 && $diff <= 30) return 'warning';

                        return null;
                    })
                    ->weight(function (DokumenSop $record) {
                        if (!$record->tgl_review_berikutnya || $record->status !== 'AKTIF') return null;

                        $diff = now()->diffInDays($record->tgl_review_berikutnya, false);

                        // Tebal hanya jika H-30
                        return ($diff >= 0 && $diff <= 30) ? 'bold' : null;
                    }),

                // Expired Date
                Tables\Columns\TextColumn::make('tgl_kadaluarsa')
                    ->label('Expired Date')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable()
                    // DESKRIPSI BARU: Tampil jika H-30 (Habis dalam X hari)
                    ->description(function (DokumenSop $record) {
                        if (!$record->tgl_kadaluarsa || $record->status !== 'AKTIF') return null;

                        $diff = now()->diffInDays($record->tgl_kadaluarsa, false);

                        // Jika H-30 sampai Hari H
                        if ($diff >= 0 && $diff <= 30) {
                            return '⚠️ Kadaluarsa dalam ' . intval($diff) . ' hari';
                        }
                        return null;
                    })
                    // WARNA BARU: Danger jika H-30
                    ->color(function (DokumenSop $record) {
                        if (!$record->tgl_kadaluarsa || $record->status !== 'AKTIF') return null;

                        $diff = now()->diffInDays($record->tgl_kadaluarsa, false);

                        if ($diff >= 0 && $diff <= 30) return 'danger'; // Merah
                        return null;
                    })
                    // BOLD: Jika H-30
                    ->weight(function (DokumenSop $record) {
                        if (!$record->tgl_kadaluarsa || $record->status !== 'AKTIF') return null;

                        $diff = now()->diffInDays($record->tgl_kadaluarsa, false);
                        return ($diff >= 0 && $diff <= 30) ? 'bold' : null;
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'AKTIF' => 'Aktif',
                        'KADALUARSA' => 'Kadaluarsa',
                    ]),
                Tables\Filters\SelectFilter::make('kategori_sop')
                    ->options([
                        'SOP' => 'SOP',
                        'SOP_AP' => 'SOP AP',
                    ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    // 1. View Detail
                    Tables\Actions\ViewAction::make()
                        ->label('Detail')
                        ->tooltip('Lihat Detail & Preview')
                        ->modalHeading('Detail Dokumen SOP')
                        ->modalWidth('4xl')
                        ->icon('heroicon-o-eye')
                        ->color('info'), // Warna biru muda

                    // 2. Download PDF
                    Tables\Actions\Action::make('download')
                        ->label('Unduh')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success') // Warna hijau
                        ->url(fn (DokumenSop $record) => asset('storage/' . $record->file_path))
                        ->openUrlInNewTab(),

                    // 3. Edit
                    Tables\Actions\EditAction::make()
                        ->label('Ubah')
                        ->tooltip('Ubah Data')
                        ->modalHeading('Ubah Dokumen SOP')
                        ->modalWidth('4xl')
                        ->icon('heroicon-o-pencil-square')
                        ->color('warning'), // Warna oranye

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
                                'catatan' => 'Dokumen dipindahkan ke sampah (Soft Delete) oleh Verifikator.',
                                'dokumen_path' => $record->file_path,
                            ]);
                        }),

                    // 5. Restore (Pulihkan dari Sampah)
                    Tables\Actions\RestoreAction::make()
                    ->after(function (DokumenSop $record) {
                        // Logika Manual: Catat Log Setelah Berhasil Restore
                        RiwayatSop::create([
                            'id_sop' => $record->id_sop,
                            'id_user' => Auth::user()->id_user,
                            'status_sop' => $record->status,
                            'catatan' => 'Dokumen dipulihkan dari sampah (Restore) oleh Verifikator.',
                            'dokumen_path' => $record->file_path,
                        ]);
                    }),
                ])
                ->icon('heroicon-m-ellipsis-vertical') // Ikon titik tiga
                ->color('primary') // Warna ikon utama
                ->tooltip('Menu Aksi') // Tooltip saat hover ikon grup

              ->extraAttributes(['class' => 'w-auto min-w-[150px]']),
            ])
            ->defaultSort('created_at', 'desc')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListDokumenSops::route('/'),
            'create' => Pages\CreateDokumenSop::route('/create'),
            'edit' => Pages\EditDokumenSop::route('/{record}/edit'),
        ];
    }
}
