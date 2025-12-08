<?php

namespace App\Providers\Filament;

use App\Filament\Auth\CustomLogin;
use App\Filament\Verifikator\Pages\Dashboard;
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
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;
use Swis\Filament\Backgrounds\ImageProviders\CuratedBySwis;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset;

class VerifikatorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('verifikator')
            ->path('verifikator')
            ->login(CustomLogin::class)
            ->passwordReset(RequestPasswordReset::class)
            ->emailVerification()
            ->colors([
                'primary' => Color::Cyan,
            ])
            ->brandLogo(asset('images/logo-rs.png'))
            ->brandLogoHeight('2.5rem')
            ->brandName('SIMSOP RSUP Prof. dr. I.G.N.G. Ngoerah')
            ->favicon(asset('images/faviconlogo-rs.svg'))
            ->plugins([
                FilamentBackgroundsPlugin::make()
                    // ->imageProvider(CuratedBySwis::make())
                    // ->showAttribution(false),

                    ->imageProvider(
                        MyImages::make()
                            ->directory('images/swisnl/filament-backgrounds/curated-by-swis') // Pastikan path ini benar ada di folder 'public'
                    )
                    ->showAttribution(false), // Opsional: Sembunyikan teks atribusi fotografer
            ])
            ->discoverResources(in: app_path('Filament/Verifikator/Resources'), for: 'App\\Filament\\Verifikator\\Resources')
            ->discoverPages(in: app_path('Filament/Verifikator/Pages'), for: 'App\\Filament\\Verifikator\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Verifikator/Widgets'), for: 'App\\Filament\\Verifikator\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
               // Widgets\FilamentInfoWidget::class,
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
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
