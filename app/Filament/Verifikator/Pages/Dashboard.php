<?php

namespace App\Filament\Verifikator\Pages;

use App\Filament\Verifikator\Widgets\VerifikatorStats;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;

class Dashboard extends BaseDashboard
{
    // Jika nanti Anda ingin mengubah judul dashboard, bisa uncomment baris ini:
    protected static ?string $title = 'Dashboard Verifikator';

    // Menambahkan Widget Stats ke Dashboard ini
    public function getWidgets(): array
    {
        return [
            AccountWidget::class,
            VerifikatorStats::class,
        ];
    }
}
