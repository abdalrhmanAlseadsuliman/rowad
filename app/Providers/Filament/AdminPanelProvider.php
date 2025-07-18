<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\Pages;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Widgets\UsersChart;
use App\Filament\Widgets\ContentStats;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\RecentActivity;
use App\Filament\Widgets\SubscriptionChart;
use App\Filament\Widgets\CustomAccountWidget;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Providers\Filament\SimpleLightBoxPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('رواد التعليم')
            ->brandLogo(asset('images/logo.jpeg'))
            ->brandLogoHeight('7rem')
            ->favicon(asset('images/logo.jpeg'))
            ->darkModeBrandLogo(asset('images/logoDark.webp'))
            ->colors([
                'primary' => '#1f3a8c',
                'secondary' => Color::Gray,
                'success' => Color::Green,
                'warning' => Color::Orange,
                'danger' => Color::Red,
                'info' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->renderHook(
                'panels::head.end',
                fn() => view('filament.hooks.header-styles')
            )
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                StatsOverview::class,
                UsersChart::class,
                SubscriptionChart::class,
                RecentActivity::class,
                ContentStats::class,
                CustomAccountWidget::class,
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
                EnsureUserIsAdmin::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
        ;
    }

    public function boot(): void
    {
        FilamentAsset::register([
            Css::make('custom-filament', asset('css/custom-filament.css')),
        ]);
    }
}
