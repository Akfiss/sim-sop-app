<?php

namespace App\Filament\Pengusul\Resources;

use App\Filament\Pengusul\Resources\DokumenSopResource\Pages;
use App\Models\DokumenSop;
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
                            ->label('Judul SOP')
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
                                'SOP_AP' => 'SOP AP (Administrasi Pemerintahan / Lintas Unit)',
                            ])
                            ->required()
                            ->live() // Agar form reaktif (munculkan unit terkait)
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('unitTerkait', [])),

                        // 4. Unit Terkait (Khusus SOP AP)
                        Forms\Components\Select::make('unitTerkait')
                            ->label('Unit Terkait (Khusus SOP AP)')
                            ->relationship('unitTerkait', 'nama_unit')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->visible(fn (Forms\Get $get) => $get('kategori_sop') === 'SOP_AP')
                            ->required(fn (Forms\Get $get) => $get('kategori_sop') === 'SOP_AP')
                            ->columnSpanFull(),
                    ])->columns(2),

                // SECTION 2: TANGGAL PENTING
                Forms\Components\Section::make('Validitas Dokumen')
                    ->schema([
                        // 5. Tanggal Pengesahan
                        Forms\Components\DatePicker::make('tgl_pengesahan')
                            ->label('Tanggal Pengesahan (TTD)')
                            ->native(false) // Pakai widget datepicker JS yang bagus
                            ->displayFormat('d/m/Y'),

                        // 6. Tanggal Berlaku (TMT)
                        Forms\Components\DatePicker::make('tgl_berlaku')
                            ->label('Tanggal Berlaku (TMT)')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ])->columns(2),

                // SECTION 3: UPLOAD FILE
                Forms\Components\Section::make('File Dokumen')
                    ->schema([
                        // 7. File Upload
                        Forms\Components\FileUpload::make('file_path')
                            ->label('Upload File PDF')
                            ->disk('public') // Simpan di storage public
                            ->directory('dokumen-sop')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240) // Maksimal 10MB
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

                Forms\Components\Section::make('Informasi Utama')->schema([
                    Forms\Components\TextInput::make('judul_sop')->required()->maxLength(255)->columnSpanFull(),
                    Forms\Components\TextInput::make('nomor_sk')->maxLength(50),
                    Forms\Components\Select::make('kategori_sop')
                        ->options(['SOP' => 'SOP', 'SOP_AP' => 'SOP AP'])
                        ->live()->afterStateUpdated(fn (Forms\Set $set) => $set('unitTerkait', [])),
                    Forms\Components\Select::make('unitTerkait')
                        ->relationship('unitTerkait', 'nama_unit')->multiple()->preload()
                        ->visible(fn (Forms\Get $get) => $get('kategori_sop') === 'SOP_AP')
                        ->columnSpanFull(),
                ])->columns(2),
                Forms\Components\Section::make('File')->schema([
                    Forms\Components\FileUpload::make('file_path')->disk('public')->directory('dokumen-sop')->acceptedFileTypes(['application/pdf'])->required()->columnSpanFull(),
                ]),
                Forms\Components\Hidden::make('created_by')->default(fn()=>Auth::user()->id_user),
                Forms\Components\Hidden::make('id_unit_pemilik')->default(fn()=>Auth::user()->units->first()?->id_unit),
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
                                    ->placeholder('Internal Unit'),

                                Infolists\Components\TextEntry::make('tgl_pengesahan')
                                    ->label('Disahkan Tanggal')
                                    ->date('d F Y')
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
                // 1. Judul SOP + Tanggal Upload (Description)
                Tables\Columns\TextColumn::make('judul_sop')
                    ->label('Judul Dokumen')
                    ->searchable()
                    ->sortable()
                    ->limit(40) // Batasi 40 karakter
                    ->weight('bold') // Tebalkan judul
                    ->tooltip(fn (DokumenSop $record) => $record->judul_sop) // Hover untuk lihat judul penuh
                    ->description(fn (DokumenSop $record) =>
                        // Tampilkan Tgl Upload di bawah judul (Italic & Kecil otomatis dari Filament)
                        'Diupload: ' . $record->created_at->translatedFormat('d F Y H:i')
                    ),

                // // 2. Nomor SK
                // Tables\Columns\TextColumn::make('nomor_sk')
                //     ->label('Nomor SK')
                //     ->searchable()
                //     ->placeholder('-') // Jika kosong tampilkan strip
                //     ->copyable() // Bisa diklik copy
                //     ->toggleable(),

                // 3. Kategori
                Tables\Columns\TextColumn::make('kategori_sop')
                    ->label('Kategori')
                    ->badge()
                    ->colors([
                        'info' => 'SOP',
                        'warning' => 'SOP_AP',
                    ]),

                // Unit Terkait (HIDDEN BY DEFAULT)
                Tables\Columns\TextColumn::make('unitTerkait.nama_unit')
                    ->label('Unit Terkait')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->limitList(2)
                    ->toggleable(isToggledHiddenByDefault: true), // <--- Ini kuncinya (Hilang di awal)

                // 4. Status (Warna-warni)
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'DALAM REVIEW' => 'warning',
                        'REVISI' => 'danger',
                        'AKTIF' => 'success',
                        'KADALUARSA' => 'gray',
                        default => 'gray',
                    }),

                // // 5. Unit Terkait (Untuk SOP AP)
                // Tables\Columns\TextColumn::make('unitTerkait.nama_unit')
                //     ->label('Unit Terkait')
                //     ->listWithLineBreaks() // Tampil berderet ke bawah
                //     ->bulleted() // Pakai bullet point
                //     ->limitList(3) // Maksimal tampil 3, sisanya "+2 more"
                //     ->expandableLimitedList() // Bisa diklik untuk lihat semua
                //     ->placeholder('Internal Unit'),

                // 6. Tanggal Pengesahan
                Tables\Columns\TextColumn::make('tgl_pengesahan')
                    ->label('Tgl Pengesahan')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable()
                    ->placeholder('Belum Disahkan'),
            ])
            ->defaultSort('created_at', 'desc') // Urutkan dari yang terbaru
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
                // GROUP BUTTONS (Edit & Delete digabung biar rapi, atau dipisah icon only)

                // 1. View (Mata)
                Tables\Actions\ViewAction::make()
                    ->label(false) // Hapus teks
                    ->tooltip('Lihat Detail & Preview')
                    ->modalHeading('Detail Dokumen SOP')
                    ->modalWidth('4xl') // Lebar Modal
                    ->icon('heroicon-o-eye'), // Icon Mata

                // 2. Download
                Tables\Actions\Action::make('download')
                    ->label(false)
                    ->tooltip('Unduh PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (DokumenSop $record) => asset('storage/' . $record->file_path))
                    ->openUrlInNewTab(),

                // 3. Edit
                Tables\Actions\EditAction::make()
                    ->label(false)
                    ->tooltip('Edit Data'),

                // 4. Delete
                Tables\Actions\DeleteAction::make()
                    ->label(false)
                    ->tooltip('Hapus Data'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            // --- SCROLLABLE SETTINGS ---
            // Secara default Filament v3 tabelnya sudah responsif (scrollable).
            // Tapi kita bisa paksa agar kolom tidak terlalu sempit.
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
