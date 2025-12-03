<?php

namespace App\Providers\Filament;

use App\Filament\Auth\CustomLogin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\CuratedBySwis;

class PengusulPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('pengusul')
            ->path('pengusul')
            ->login(CustomLogin::class)
            ->colors([
                'primary' => Color::Green,
            ])
            ->plugins([
                FilamentBackgroundsPlugin::make()
                    ->imageProvider(CuratedBySwis::make())
                    ->showAttribution(false),
            ])
            ->discoverResources(in: app_path('Filament/Pengusul/Resources'), for: 'App\\Filament\\Pengusul\\Resources')
            ->discoverPages(in: app_path('Filament/Pengusul/Pages'), for: 'App\\Filament\\Pengusul\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Pengusul/Widgets'), for: 'App\\Filament\\Pengusul\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE, // Posisi: Sebelum menu user (pojok kanan)
                fn (): string => Blade::render('@livewire(\'lonceng-notifikasi\')')
            )
            //->viteTheme(theme: 'resources/css/app.css')
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
