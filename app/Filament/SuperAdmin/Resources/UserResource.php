<?php

namespace App\Filament\SuperAdmin\Resources;

use App\Filament\SuperAdmin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Notifications\Notification;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users'; // Ikon User
    protected static ?string $navigationLabel = 'User';
    protected static ?string $navigationGroup = 'Master Data'; // Kelompokkan Menu
    protected static ?int $navigationSort = 3; // Urutan ke-3

    protected static ?string $modelLabel = 'User';
    protected static ?string $pluralModelLabel = 'User';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Section Informasi Akun
                Forms\Components\Section::make('Informasi Akun')
                    ->schema([
                        Forms\Components\TextInput::make('username')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100),

                        // Input Password Canggih
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->revealable() // Tombol lihat password
                            // Hanya wajib saat create (create = operation 'create')
                            ->required(fn (string $operation): bool => $operation === 'create')
                            // Hanya simpan jika diisi (agar saat edit password lama tidak tertimpa null)
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->maxLength(255),
                    ])->columns(2),

                // Section Profil & Hak Akses
                Forms\Components\Section::make('Profil & Hak Akses')
                    ->schema([
                        Forms\Components\TextInput::make('nama_lengkap')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('role')
                            ->options([
                                'PENGUSUL' => 'Pengusul',
                                'VERIFIKATOR' => 'Verifikator',
                                'DIREKSI' => 'Direksi',
                                'SUPER ADMIN' => 'Super Admin',
                            ])
                            ->required(),

                        Forms\Components\Select::make('id_direktorat')
                            ->label('Direktorat')
                            ->relationship('direktorat', 'nama_direktorat')
                            ->searchable()
                            ->preload(),

                        // Relasi Many-to-Many ke Unit Kerja
                        Forms\Components\Select::make('units')
                            ->label('Unit Kerja')
                            ->relationship('units', 'nama_unit')
                            ->multiple() // Bisa pilih lebih dari satu
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. ID User
                Tables\Columns\TextColumn::make('id_user')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // 2. Nama Lengkap
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable(),

                // 3. Username
                Tables\Columns\TextColumn::make('username')
                    ->searchable()
                    ->icon('heroicon-m-user')
                    ->color('gray'),

                // 4. Email
                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-m-envelope')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true), // Opsional: disembunyikan default agar tidak penuh

                // 5. Role (Badge Warna)
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'SUPER ADMIN' => 'danger',
                        'DIREKSI' => 'warning',
                        'VERIFIKATOR' => 'info',
                        'PENGUSUL' => 'success',
                        default => 'gray',
                    }),

                // 6. Direktorat (Relasi)
                Tables\Columns\TextColumn::make('direktorat.nama_direktorat')
                    ->label('Direktorat')
                    ->searchable()
                    ->wrap() // Text wrapping jika kepanjangan
                    ->toggleable(isToggledHiddenByDefault: true),

                // 7. Unit Kerja (Relasi Many-to-Many)
                Tables\Columns\TextColumn::make('units.nama_unit')
                    ->label('Unit Kerja')
                    ->badge()
                    ->separator(',') // Jika user punya banyak unit, dipisah koma
                    ->limitList(2)   // Tampilkan max 2, sisanya "+1 more"
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                // 8. Status Aktif
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'PENGUSUL' => 'Pengusul',
                        'VERIFIKATOR' => 'Verifikator',
                        'DIREKSI' => 'Direksi',
                        'SUPER ADMIN' => 'Super Admin',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            // --- BAGIAN ACTION ICON ONLY ---
            ->actions([
                Tables\Actions\ActionGroup::make([

                    Tables\Actions\EditAction::make()
                        ->label('Edit')
                        ->color('warning'),

                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus')   // Hapus Teks
                        ->successNotification(
                            Notification::make()
                        ->success()
                        ->title('Berhasil dihapus.')
                        ->body('Data akun telah dihapus dari sistem.')
                        )
                    ])
                    ->icon('heroicon-m-ellipsis-vertical') // Ikon titik tiga
                    ->color('gray') // Warna ikon utama
                    ->tooltip('Menu Aksi') // Tooltip saat hover ikon grup
                    ->extraAttributes(['class' => 'w-auto min-w-[150px]']), // Lebar minimal agar tidak terlalu kecil
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
