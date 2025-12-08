<?php

namespace App\Providers\Filament;

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
use App\Filament\SuperAdmin\Pages\Dashboard;
use App\Filament\Auth\CustomLogin;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;
use Swis\Filament\Backgrounds\ImageProviders\CuratedBySwis;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset;



class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(CustomLogin::class)
            ->passwordReset(RequestPasswordReset::class)
            ->emailVerification()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->brandLogo(asset('images/logo-rs.png'))
            ->brandLogoHeight('2.5rem')
            ->brandName('SIMSOP RSUP Prof. dr. I.G.N.G. Ngoerah')
            ->favicon(asset('images/faviconlogo-rs.svg'))
            ->plugins([
                FilamentBackgroundsPlugin::make()
                    // ->imageProvider(
                    //     CuratedBySwis::make() // Menggunakan gambar pemandangan default yang cantik
                    // )
                    ->imageProvider(
                        MyImages::make()
                            ->directory('images/swisnl/filament-backgrounds/curated-by-swis') // Pastikan path ini benar ada di folder 'public'
                    )
                    ->showAttribution(false), // Opsional: Sembunyikan teks atribusi fotografer
            ])
            ->discoverResources(in: app_path('Filament/SuperAdmin/Resources'), for: 'App\\Filament\\SuperAdmin\\Resources')
            ->discoverPages(in: app_path('Filament/SuperAdmin/Pages'), for: 'App\\Filament\\SuperAdmin\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/SuperAdmin/Widgets'), for: 'App\\Filament\\SuperAdmin\\Widgets')
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
