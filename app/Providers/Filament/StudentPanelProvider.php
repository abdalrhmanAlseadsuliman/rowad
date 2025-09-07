<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Filament\Support\Facades\FilamentAsset;
use App\Filament\Widgets\CustomAccountWidget;
use Illuminate\Session\Middleware\StartSession;
use App\Http\Middleware\CheckSubscriptionStatus;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use SolutionForest\FilamentSimpleLightBox\SimpleLightBoxPlugin;
use App\Filament\Student\Pages\Dashboard; // إضافة الصفحة المخصصة

class StudentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('student')
            ->path('student')
            ->colors([
                'primary' => '#1f3a8c',
            ])
            ->brandName('رواد التعليم')
            ->brandLogo(asset('images/logo.jpeg'))
            ->brandLogoHeight('7rem')
            ->favicon(asset('images/logo.jpeg'))
            ->darkModeBrandLogo(asset('images/logoDark.webp'))
            ->login()
            ->discoverResources(in: app_path('Filament/Student/Resources'), for: 'App\\Filament\\Student\\Resources')
            ->discoverPages(in: app_path('Filament/Student/Pages'), for: 'App\\Filament\\Student\\Pages')
            ->pages([
                Dashboard::class, //
            ])
            ->renderHook(
                'panels::head.end',
                fn() => view('filament.hooks.header-styles')
            )
            ->discoverWidgets(in: app_path('Filament/Student/Widgets'), for: 'App\\Filament\\Student\\Widgets')
            ->widgets([
                // \Filament\Widgets\AccountWidget::class,
                // تم حذف FilamentInfoWidget::class
            ])
            ->widgets([
                // StatsOverview::class,
                // UsersChart::class,
                // SubscriptionChart::class,
                // RecentActivity::class,
                // ContentStats::class,
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
                CheckSubscriptionStatus::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugin(SimpleLightBoxPlugin::make());
    }

    public function boot(): void
    {
        FilamentAsset::register([
            Css::make('custom-filament', asset('css/custom-filament.css')),
        ]);
    }
}
