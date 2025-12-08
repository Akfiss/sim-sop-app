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

class DokumenSopResource extends Resource
{
    protected static ?string $model = DokumenSop::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Daftar SOP';
    protected static ?string $pluralModelLabel = 'Daftar SOP';

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

    // --- 1. METODE BARU: TAMPILAN POP-UP (EYE ICON) ---
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

                // Section Validitas (UPDATE DISINI: 3 TANGGAL)
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
                            ->formatStateUsing(fn ($state) => new HtmlString(
                                // Wrapper DIV agar iframe ke tengah
                                '<div style="display: flex; justify-content: center; align-items: center; width: 100%; background-color: #f9fafb; padding: 10px; border-radius: 8px;">
                                    <iframe
                                        src="'.asset('storage/'.$state).'"
                                        style="width: 100%; height: 500px; border: none; border-radius: 6px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);"
                                    ></iframe>
                                </div>'
                            ))
                            ->columnSpanFull(),
                    ])
                    ->collapsible(), // Bisa dilipat jika ingin ringkas
            ]);
    }

    // --- 2. UPDATE TABEL (CLEAN & ICON ONLY) ---
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
                // Action View
                Tables\Actions\ViewAction::make()
                    ->label(false)
                    ->tooltip('Lihat Detail & Preview')
                    ->modalHeading('Detail Dokumen SOP')
                    ->modalWidth('4xl')
                    ->icon('heroicon-o-eye'),

                // Action Download
                Tables\Actions\Action::make('download')
                    ->label(false)
                    ->tooltip('Unduh PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (DokumenSop $record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),

                // --- 3. ACTION KHUSUS: REVIEW TAHUNAN (WARNING ICON) ---
                Tables\Actions\Action::make('review_tahunan')
                    ->label('Perlu Review')
                    ->icon('heroicon-s-exclamation-triangle') // Icon Warning Solid
                    ->color('warning')
                    ->button() // Tampil sebagai button agar mencolok
                    ->tooltip('SOP ini mendekati jadwal review tahunan. Klik untuk proses.')

                    ->visible(function (DokumenSop $record) {
                        if ($record->status !== 'AKTIF' || !$record->tgl_review_berikutnya) return false;

                        $now = now();
                        // Muncul jika H-30 Review DAN H-30 Expired BELUM tercapai (biar gak bentrok)
                        $isReviewTime = $now->diffInDays($record->tgl_review_berikutnya, false) <= 30;
                        $isNearExpired = $record->tgl_kadaluarsa && $now->diffInDays($record->tgl_kadaluarsa, false) <= 30;

                        return $isReviewTime && !$isNearExpired;
                    })
                    ->modalHeading('Konfirmasi Review Tahunan')
                    ->modalDescription('Apakah ada perubahan pada isi dokumen SOP ini?')
                    ->modalSubmitActionLabel('Tidak Ada Perubahan (Perpanjang)')
                    ->modalCancelActionLabel('Ada Perubahan (Revisi)')

                    // LOGIKA TOMBOL "TIDAK ADA PERUBAHAN" (Submit Modal)
                    ->action(function (DokumenSop $record) {
                        // Perpanjang 1 tahun lagi jadwal reviewnya
                        $record->update([
                            'tgl_review_berikutnya' => Carbon::parse($record->tgl_review_berikutnya)->addYear(),
                            'updated_at' => now(), // Menandakan baru diupdate
                        ]);

                        Notification::make()->title('Review Selesai (Tidak Ada Perubahan)')->success()->send();
                    })
                    // LOGIKA TOMBOL "ADA PERUBAHAN" (Cancel Modal -> Dialihkan ke Edit)
                    ->extraModalFooterActions([
                        Tables\Actions\Action::make('edit_changes')
                            ->label('Ada Perubahan (Edit Dokumen)')
                            ->color('primary')
                            ->url(fn (DokumenSop $record) => DokumenSopResource::getUrl('edit', ['record' => $record])),
                    ]),

                // Action Edit
                Tables\Actions\EditAction::make()
                    ->label(false)
                    ->tooltip('Edit Data')
                    ->disabled(function (DokumenSop $record) {
                        if (in_array($record->status, ['DALAM REVIEW', 'REVISI'])) return false;
                        if ($record->status === 'AKTIF') {
                            $now = now();
                            $isReview = $record->tgl_review_berikutnya && $now->diffInDays($record->tgl_review_berikutnya, false) <= 30;

                            // JIKA KADALUARSA DEKAT -> FITUR EDIT DIBUKA
                            $isExpired = $record->tgl_kadaluarsa && $now->diffInDays($record->tgl_kadaluarsa, false) <= 30;

                            if ($isReview || $isExpired) return false;
                        }
                        return true;
                    }),

                // Action Delete
                Tables\Actions\DeleteAction::make()
                    ->label(false)
                    ->tooltip('Hapus Data')
                    ->visible(fn (DokumenSop $record) => in_array($record->status, ['DALAM REVIEW', 'REVISI'])),
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
