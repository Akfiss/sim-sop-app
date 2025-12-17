<?php

namespace App\Filament\Pengusul\Widgets;

use App\Models\DokumenSop;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Filament\Pengusul\Resources\SopAktifResource;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class SopPengusulStats extends BaseWidget
{
    // Supaya widget refresh otomatis jika ada perubahan data
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $user = Auth::user();

        // 1. AMBIL ID UNIT MILIK USER
        $unitIds = DB::table('tb_unit_user')
            ->where('id_user', $user->id_user)
            ->pluck('id_unit')
            ->toArray(); // Pastikan jadi array

        // -----------------------------------------------------------
        // HITUNGAN 1: HANYA MILIK UNIT SENDIRI
        // -----------------------------------------------------------
        $countMilikSendiri = DokumenSop::whereIn('id_unit_pemilik', $unitIds)
            ->whereIn('status', ['AKTIF', 'KADALUARSA']) // Sesuaikan dengan menu (Aktif + Kadaluarsa)
            ->count();

        // -----------------------------------------------------------
        // HITUNGAN 2: TOTAL AKSES (Milik Sendiri + Terkait + All Units)
        // Logic ini SAMA PERSIS dengan di SopAktifResource
        // -----------------------------------------------------------
        $countTotalAkses = DokumenSop::whereIn('status', ['AKTIF', 'KADALUARSA'])
            ->where(function (Builder $query) use ($unitIds) {
                // A. Milik Unit Sendiri
                $query->whereIn('id_unit_pemilik', $unitIds)

                // B. ATAU SOP AP (Lintas Unit)
                ->orWhere(function (Builder $q) use ($unitIds) {
                    $q->where('kategori_sop', 'SOP_AP')
                      ->where(function ($subQ) use ($unitIds) {
                          // Yang unit ini ditunjuk sebagai unit terkait
                          $subQ->whereHas('unitTerkait', function ($relasi) use ($unitIds) {
                              $relasi->whereIn('tb_unit_kerja.id_unit', $unitIds);
                          })
                          // ATAU yang berlaku untuk seluruh unit
                          ->orWhere('is_all_units', true);
                      });
                });
            })
            ->count();

        // -----------------------------------------------------------
        // HITUNGAN LAINNYA
        // -----------------------------------------------------------
        // Menggunakan logic $countTotalAkses sebagai base query agar konsisten
        // Kita hitung yang aktif & akan kadaluarsa dari total yang bisa diakses user

        // Base Query untuk Total Akses (dipakai ulang untuk filter Aktif/Expired)
        $baseQuery = DokumenSop::where(function (Builder $query) use ($unitIds) {
                $query->whereIn('id_unit_pemilik', $unitIds)
                ->orWhere(function ($q) use ($unitIds) {
                    $q->where('kategori_sop', 'SOP_AP')
                      ->where(function ($subQ) use ($unitIds) {
                          $subQ->whereHas('unitTerkait', function ($rel) use ($unitIds) {
                              $rel->whereIn('tb_unit_kerja.id_unit', $unitIds);
                          })
                          ->orWhere('is_all_units', true);
                      });
                });
            });

        $sopAktif = (clone $baseQuery)->where('status', 'AKTIF')->count();

        $approachingExpiredCount = (clone $baseQuery)
            ->where('status', 'AKTIF')
            ->whereDate('tgl_kadaluarsa', '<=', now()->addDays(30))
            ->whereDate('tgl_kadaluarsa', '>=', now())
            ->count();

        return [
            // KARTU 1: TOTAL DOKUMEN (DUA DATA)
            Stat::make('Total Dokumen', $countTotalAkses) // Nilai Besar: Total Akses
                ->label('Semua Dokumen SOP')
                // Menampilkan rincian di deskripsi
                ->description("Milik Unit: {$countMilikSendiri} | Terkait: " . ($countTotalAkses - $countMilikSendiri))
                ->descriptionIcon('heroicon-m-archive-box')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info')
                ->url(SopAktifResource::getUrl('index')),

            // KARTU 2: SOP AKTIF
            Stat::make('SOP Aktif', $sopAktif)
                ->description('Total dokumen aktif yang bisa diakses')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->url(SopAktifResource::getUrl('index', ['tableFilters[status][value]' => 'AKTIF'])),

            // KARTU 3: AKAN KADALUARSA
            Stat::make('Akan Kadaluarsa', $approachingExpiredCount)
                ->description('Mendekati expired (H-30)')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
