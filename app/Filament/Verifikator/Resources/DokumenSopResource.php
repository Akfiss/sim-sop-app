<?php

namespace App\Filament\Verifikator\Resources;

use App\Filament\Verifikator\Resources\DokumenSopResource\Pages;
use App\Models\DokumenSop;
use App\Models\Notifikasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Components\Tab;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification as FilamentNotification;
use Carbon\Carbon;
use Illuminate\Support\HtmlString; // Import ini penting

class DokumenSopResource extends Resource
{
    protected static ?string $model = DokumenSop::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Verifikasi SOP';
    protected static ?string $pluralModelLabel = 'Verifikasi SOP';

    public static function canCreate(): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul_sop')
                    ->label('Dokumen')
                    ->searchable()
                    ->description(fn (DokumenSop $record) => $record->nomor_sk ?? 'Draft SK')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->judul_sop)
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('creator.nama_lengkap')
                    ->label('Pengusul')
                    ->icon('heroicon-m-user')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('unitPemilik.nama_unit')
                    ->label('Unit Pemilik')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    // 1. Ubah Teks Tampilan (Masking)
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'DALAM REVIEW' => 'BUTUH PERSETUJUAN', // Ubah teks khusus Verifikator
                        default => $state,
                    })
                    // 2. Warna Badge
                    ->color(fn (string $state): string => match ($state) {
                        'DALAM REVIEW' => 'warning', // Kuning
                        'REVISI' => 'danger',        // Merah
                        'AKTIF' => 'success',        // Hijau
                        'KADALUARSA' => 'gray',
                        default => 'gray',
                    })
                    // 3. Penanda Review Tahunan (Description)
                    ->description(function (DokumenSop $record) {
                        if ($record->status !== 'AKTIF') return null;

                        $now = now();

                        // Cek Mau Kadaluarsa (Prioritas Utama)
                        if ($record->tgl_kadaluarsa && $now->diffInDays($record->tgl_kadaluarsa, false) <= 30) {
                            return '🚨 Segera Kadaluarsa';
                        }

                        // Cek Review Tahunan
                        if ($record->tgl_review_berikutnya && $now->diffInDays($record->tgl_review_berikutnya, false) <= 30) {
                            return '⚠️ Perlu Review Tahunan';
                        }

                        return null;
                    }),

                Tables\Columns\TextColumn::make('tgl_kadaluarsa')
                    ->label('Exp. Date')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($state) => $state && Carbon::parse($state)->isPast() ? 'danger' : 'success')
                    ->toggleable(),
            ])
            ->defaultSort('updated_at', 'desc')

            // --- 3. FITUR FILTER STATUS ---
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'DALAM REVIEW' => 'Butuh Persetujuan', // Label disamakan
                        'REVISI'       => 'Revisi',
                        'AKTIF'        => 'Aktif',
                        'KADALUARSA'   => 'Kadaluarsa',
                    ]),

                Tables\Filters\SelectFilter::make('id_unit_pemilik')
                    ->label('Filter Unit')
                    ->relationship('unitPemilik', 'nama_unit')
                    ->searchable()
                    ->preload(),
            ])
            // -----------------------------

            ->actions([
                // --- 1. PREVIEW DOKUMEN (REVISI: Modal Content Custom) ---
                Tables\Actions\Action::make('preview')
                    ->label(false)
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->tooltip('Lihat Dokumen')
                    ->modalHeading(fn ($record) => "Preview: {$record->judul_sop}")
                    ->modalContent(fn ($record) => new HtmlString(
                        '<div style="width: 100%; height: 600px; background-color: #f3f4f6; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb;">
                            <iframe
                                src="'.asset('storage/' . $record->file_path).'"
                                style="width: 100%; height: 100%; border: none;"
                            ></iframe>
                        </div>
                        <div style="margin-top: 10px; font-size: 0.875rem; color: #4b5563;">
                            <strong>Unit Terkait:</strong> ' .
                            ($record->unitTerkait->count() > 0
                                ? $record->unitTerkait->pluck('nama_unit')->join(', ')
                                : 'Internal Unit') .
                        '</div>'
                    ))
                    ->modalSubmitAction(false) // Hilangkan tombol submit default
                    ->modalCancelActionLabel('Tutup'), // Ganti label cancel jadi Tutup

                // --- 2. APPROVE ---
                Tables\Actions\Action::make('approve')
                    ->label(false)
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->tooltip('Setujui SOP')
                    ->visible(fn (DokumenSop $record) => $record->status === 'DALAM REVIEW')
                    ->requiresConfirmation()

                    // Detail Konfirmasi
                    ->modalHeading('Setujui Dokumen SOP?')
                    ->modalDescription(fn ($record) => "Anda akan menyetujui dokumen '{$record->judul_sop}'. Status akan berubah menjadi AKTIF dan tanggal berlaku (TMT) akan diset hari ini.")
                    ->modalSubmitActionLabel('Ya, Setujui')
                    ->modalIcon('heroicon-o-check-circle')

                    ->action(function (DokumenSop $record) {
                        $now = now();   // waktu saat tombol diklik
                        $record->update([
                            'status' => 'AKTIF',
                            'tgl_pengesahan' => $record->tgl_pengesahan ?? $now,
                            'tgl_berlaku' => $now,
                            // Review berikutnya = 1 tahun dari sekarang
                            'tgl_review_berikutnya' => $now->copy()->addYear(),
                            // Kadaluarsa = 3 tahun dari sekarang
                            'tgl_kadaluarsa' => $now->copy()->addYears(3),
                        ]);

                        Notifikasi::create([
                            'id_user' => $record->created_by,
                            'judul' => 'SOP Disetujui',
                            'pesan' => "SOP '{$record->judul_sop}' telah disetujui dan kini AKTIF.",
                            'is_read' => false,
                            'created_at' => now(),
                            'id_sop' => $record->id_sop
                        ]);

                        FilamentNotification::make()->title('SOP Disetujui & Jadwal Review Dibuat')->success()->send();
                    }),

                // ACTION REJECT / REVISI
                Tables\Actions\Action::make('revisi')
                    ->label(false)
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->tooltip('Minta Revisi')
                    ->visible(fn (DokumenSop $record) => $record->status === 'DALAM REVIEW')
                    ->modalHeading('Kembalikan untuk Revisi')
                    ->modalDescription('Silakan berikan catatan perbaikan untuk pengusul.')
                    ->modalSubmitActionLabel('Kirim Revisi')
                    ->form([
                        Forms\Components\Textarea::make('catatan')
                            ->label('Catatan Revisi')
                            ->placeholder('Contoh: Format header salah, mohon diperbaiki sesuai template.')
                            ->required()
                            ->rows(4)
                    ])
                    ->action(function (DokumenSop $record, array $data) {
                        $record->update(['status' => 'REVISI']);

                        Notifikasi::create([
                            'id_user' => $record->created_by,
                            'judul' => 'Revisi Diperlukan',
                            'pesan' => "Verifikator meminta revisi pada SOP '{$record->judul_sop}': " . $data['catatan'],
                            'is_read' => false,
                            'created_at' => now(),
                            'id_sop' => $record->id_sop
                        ]);

                        FilamentNotification::make()->title('Status diubah ke Revisi')->success()->send();
                    }),

                // ACTION MANUAL ALERT
                Tables\Actions\Action::make('send_alert')
                    ->label(false)
                    ->icon('heroicon-o-bell-alert')
                    ->color('warning')
                    ->tooltip('Kirim Alert Review Tahunan')
                    ->visible(fn (DokumenSop $record) => $record->status === 'AKTIF' && $record->tgl_kadaluarsa)
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Peringatan Review?')
                    ->modalDescription('Pengusul akan menerima notifikasi lonceng untuk segera melakukan review SOP ini.')
                    ->modalSubmitActionLabel('Kirim Alert')
                    ->action(function (DokumenSop $record) {
                        Notifikasi::create([
                            'id_user' => $record->created_by,
                            'judul' => 'Peringatan Review SOP',
                            'pesan' => "PERINGATAN MANUAL: SOP '{$record->judul_sop}' akan segera kadaluarsa pada " . Carbon::parse($record->tgl_kadaluarsa)->format('d M Y') . ". Mohon segera lakukan review.",
                            'is_read' => false,
                            'created_at' => now(),
                            'id_sop' => $record->id_sop
                        ]);

                        FilamentNotification::make()->title('Alert terkirim ke Pengusul')->success()->send();
                    }),
            ]);
    }

    public static function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Data'),

            'verifikasi' => Tab::make('Butuh Persetujuan')
                ->icon('heroicon-m-inbox-arrow-down')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'DALAM REVIEW'))
                ->badge(DokumenSop::where('status', 'DALAM REVIEW')->count())
                ->badgeColor('warning'),

            'review' => Tab::make('Review Tahunan')
                ->icon('heroicon-m-clock')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->where('status', 'AKTIF')
                    ->whereDate('tgl_kadaluarsa', '<=', now()->addDays(30))
                )
                ->badgeColor('danger'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDokumenSops::route('/'),
        ];
    }
}
