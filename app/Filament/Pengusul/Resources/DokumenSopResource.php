<?php

namespace App\Filament\Pengusul\Resources;

use App\Filament\Pengusul\Resources\DokumenSopResource\Pages;
use App\Models\DokumenSop;
use App\Models\Notifikasi; // Pastikan Model Notifikasi ada
use App\Models\RiwayatSop; // Pastikan Model Riwayat ada (jika mau catat log)
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\MaxWidth;


class DokumenSopResource extends Resource
{
    protected static ?string $model = DokumenSop::class;

    protected static ?string $navigationLabel = 'Pengajuan SOP'; 
    protected static ?string $pluralModelLabel = 'Pengajuan SOP';
    protected static ?string $navigationGroup = 'Daftar Lengkap SOP';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-document-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // SECTION 1: INFORMASI UTAMA
                Forms\Components\Section::make('Informasi Utama')
                    ->description('Isi detail identitas SOP di sini.')
                    ->schema([
                        // 1. Judul SOP
                        Forms\Components\TextInput::make('judul_sop')
                            ->label('Judul Dokumen SOP')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(), // Lebar penuh

                        // 2. Nomor SK (Boleh kosong jika belum ada SK / masih draft)
                        Forms\Components\TextInput::make('nomor_sk')
                            ->label('Nomor SK')
                            ->required()
                            ->placeholder('Contoh: 001/SK/DIR/2025')
                            ->maxLength(50),

                        // 3. Kategori
                        Forms\Components\Select::make('kategori_sop')
                            ->label('Kategori SOP')
                            ->options([
                                'SOP' => 'SOP (Internal Unit)',
                                'SOP_AP' => 'SOP AP (Administrasi Lintas Unit)',
                            ])
                            ->placeholder('Pilih Kategori...')
                            ->required()
                            ->live() // Agar form reaktif (munculkan unit terkait)
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('unitTerkait', [])),

                        // 4. TOGGLE ALL UNITS
                        // Hanya muncul jika kategori = SOP_AP
                        Forms\Components\Toggle::make('is_all_units')
                            ->label('Berlaku untuk SELURUH Unit/Instalasi?')
                            ->visible(fn (Forms\Get $get) => $get('kategori_sop') === 'SOP_AP')
                            ->live() // Agar langsung menyembunyikan input unitTerkait di bawahnya
                            ->columnSpanFull()
                            ->onColor('success')
                            ->offColor('gray')
                            ->afterStateUpdated(function (Forms\Set $set, $state) {
                                // Jika dicentang, kosongkan pilihan unit manual
                                if ($state) {
                                    $set('unitTerkait', []);
                                }
                            }),

                        // 5. Unit Terkait (Khusus SOP AP Pilihan Manual)
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
                        // Tanggal Pengesahan
                        Forms\Components\DatePicker::make('tgl_pengesahan')
                            ->label('Tanggal Pengesahan (TTD)')
                            ->required()
                            ->native(false) // Pakai widget datepicker JS yang bagus
                            ->displayFormat('d/m/Y'),
                    ])->columns(1),

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

                // HIDDEN FIELDS (Otomatis)
                Forms\Components\Hidden::make('created_by')
                    ->default(fn () => Auth::user()->id_user),

                Forms\Components\Hidden::make('id_unit_pemilik')
                    ->default(fn () => Auth::user()->units->first()?->id_unit)
                    ->required() // Tambahkan ini agar divalidasi oleh Laravel dulu
                    ->validationMessages([
                        'required' => 'Akun Anda belum terdaftar di Unit Kerja manapun. Hubungi Admin.',
                    ]),

                Forms\Components\Hidden::make('status')
                    ->default('DALAM REVIEW'),
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
                                    'DALAM REVIEW' => 'warning',
                                    'REVISI' => 'danger',
                                    'AKTIF' => 'success',
                                    default => 'gray',
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

    // --- 3. UPDATE TABEL (CLEAN & ICON ONLY) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Judul SOP + Tanggal Upload
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

                // 2. Kategori
                Tables\Columns\TextColumn::make('kategori_sop')
                    ->label('Kategori')
                    ->badge()
                    ->colors([
                        'info' => 'SOP',
                        'warning' => 'SOP_AP',
                    ]),

                // 3. Unit Terkait (LOGIC VISUAL ALL UNITS)
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

                // 4. Penanda All Units (Opsional: Icon Column)
                Tables\Columns\IconColumn::make('is_all_units')
                    ->label('All Units?')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->toggleable(isToggledHiddenByDefault: true), // Bisa dimunculkan lewat toggle column

                // 5. Status
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'DRAFT' => 'gray',
                        'DALAM REVIEW' => 'warning',
                        'REVISI' => 'danger',
                        'AKTIF' => 'success',
                        'KADALUARSA' => 'gray',
                        default => 'gray',
                    })
                    // PENANDA DI BAWAH STATUS (Description)
                    ->description(function (DokumenSop $record) {
                        if ($record->status !== 'AKTIF') return null;
                        $now = now();

                        // Cek Segera Kadaluarsa (H-30)
                        if ($record->tgl_kadaluarsa && $now->diffInDays($record->tgl_kadaluarsa, false) <= 30) {
                            return '🚨 Segera Kadaluarsa';
                        }
                        return null;
                    })
                    // TOOLTIP SAAT HOVER
                    ->tooltip(function (DokumenSop $record) {
                        if ($record->status === 'AKTIF' && $record->tgl_kadaluarsa && now()->diffInDays($record->tgl_kadaluarsa, false) <= 30) {
                            return 'Masa berlaku 3 tahun hampir habis. Wajib perbarui dokumen lewat tombol Edit!';
                        }
                        return null;
                    }),

                // 6. Review Date
                Tables\Columns\TextColumn::make('tgl_review_berikutnya')
                    ->label('Review Date')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(),

                // 7. Expired Date
                Tables\Columns\TextColumn::make('tgl_kadaluarsa')
                    ->label('Expired Date')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-')
                    ->color(fn ($state) => $state && Carbon::parse($state)->isPast() ? 'danger' : 'success')
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'DALAM REVIEW' => 'Dalam Review',
                        'REVISI' => 'Revisi',
                        'AKTIF' => 'Aktif',
                    ]),
                Tables\Filters\SelectFilter::make('kategori_sop')
                    ->options([
                        'SOP' => 'SOP',
                        'SOP_AP' => 'SOP AP',
                    ]),
            ])
            ->actions([
                // KITA BUNGKUS SEMUA ACTION DALAM SATU GRUP
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

                    // 3. Review Tahunan
                    Tables\Actions\Action::make('review_tahunan')
                        ->label('Perlu Review')
                        ->icon('heroicon-s-exclamation-triangle')
                        ->color('warning')
                        // Hapus ->button() agar tampil sebagai menu item biasa dalam dropdown
                        ->tooltip('SOP ini mendekati jadwal review tahunan. Klik untuk proses.')
                        ->visible(function (DokumenSop $record) {
                            if ($record->status !== 'AKTIF' || !$record->tgl_review_berikutnya) return false;
                            $now = now();
                            $isReviewTime = $now->diffInDays($record->tgl_review_berikutnya, false) <= 30;
                            $isNearExpired = $record->tgl_kadaluarsa && $now->diffInDays($record->tgl_kadaluarsa, false) <= 30;
                            return $isReviewTime && !$isNearExpired;
                        })
                        ->modalHeading('Konfirmasi Review Tahunan')
                        ->modalDescription('Apakah ada perubahan pada isi dokumen SOP ini?')
                        ->modalSubmitActionLabel('Tidak Ada Perubahan (Perpanjang)')
                        ->modalCancelActionLabel('Ada Perubahan (Revisi)')
                        ->action(function (DokumenSop $record) {
                            $record->update([
                                'tgl_review_berikutnya' => Carbon::parse($record->tgl_review_berikutnya)->addYear(),
                                'updated_at' => now(),
                            ]);
                            Notification::make()->title('Review Selesai (Tidak Ada Perubahan)')->success()->send();
                        })
                        ->extraModalFooterActions([
                            Tables\Actions\Action::make('edit_changes')
                                ->label('Ada Perubahan (Edit Dokumen)')
                                ->color('primary')
                                ->url(fn (DokumenSop $record) => DokumenSopResource::getUrl('edit', ['record' => $record])),
                        ]),

                    // 4. Edit Data
                    Tables\Actions\EditAction::make()
                        ->label('Edit')
                        ->icon('heroicon-o-pencil-square')
                        ->color('warning') // Warna biru
                        ->tooltip('Edit Data')
                        ->visible(fn (DokumenSop $record) => in_array($record->status, ['REVISI', 'DRAFT']))
                        ->disabled(function (DokumenSop $record) {
                            if (in_array($record->status, ['DRAFT', 'REVISI'])) return false;
                            if ($record->status === 'AKTIF') {
                                $now = now();
                                $isReview = $record->tgl_review_berikutnya && $now->diffInDays($record->tgl_review_berikutnya, false) <= 30;
                                $isExpired = $record->tgl_kadaluarsa && $now->diffInDays($record->tgl_kadaluarsa, false) <= 30;
                                if ($isReview || $isExpired) return false;
                            }
                            return true;
                        }),

                    // 5. Hapus Data
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->icon('heroicon-o-trash')
                        ->color('danger') // Warna merah
                        ->visible(fn (DokumenSop $record) => in_array($record->status, ['REVISI', 'DRAFT']))
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Berhasil dihapus.')
                                ->body('Data dokumen SOP telah dihapus dari sistem.')
                        )

                ])
                    ->icon('heroicon-m-ellipsis-vertical') // Ikon titik tiga
                    ->color('primary') // Warna ikon utama
                    ->tooltip('Menu Aksi') // Tooltip saat hover ikon grup
                    ->extraAttributes(['class' => 'w-auto min-w-[150px]']), // Lebar minimal agar tidak terlalu kecil
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([10, 25, 50]);
    }

    // --- PENTING: FILTER AGAR PENGUSUL HANYA LIHAT SOP MILIKNYA ---
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('created_by', Auth::user()->id_user) // Filter by User ID
            ->withoutGlobalScopes([
               // SoftDeletingScope::class, // Agar bisa lihat tong sampah jika perlu (opsional)
            ]);
    }

    // ... getRelations & getPages biarkan default
    public static function getRelations(): array { return []; }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDokumenSops::route('/'),
            'create' => Pages\CreateDokumenSop::route('/create'),
            'edit' => Pages\EditDokumenSop::route('/{record}/edit'),
        ];
    }
}
