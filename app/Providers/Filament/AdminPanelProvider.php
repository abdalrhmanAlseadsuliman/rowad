<?php

namespace App\Providers\Filament;
<<<<<<< HEAD

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Widgets\UsersChart;
use App\Filament\Widgets\ContentStats;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\RecentActivity;
use Filament\Navigation\NavigationGroup;
use App\Http\Middleware\EnsureUserIsAdmin;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Widgets\SubscriptionChart;
use App\Filament\Widgets\CustomAccountWidget;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
=======
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentAsset;
use Filament\Http\Middleware\Authenticate;
>>>>>>> abdulhameed
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\EnsureUserIsAdmin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('رواد التعليم') // اسم الموقع
            ->brandLogo(asset('images/logo.jpeg')) // شعار الموقع
            ->brandLogoHeight('7rem') // ارتفاع الشعار
            ->favicon(asset('images/logo.jpeg')) // أيقونة التبويبة
            ->darkModeBrandLogo(asset('images/logoDark.webp')) // شعار للوضع المظلم
            ->colors([
                // 'primary' => Color::Amber,
                'primary' => '#1f3a8c', // اللون الأساسي المطلوب
                'secondary' => Color::Gray,
                'success' => Color::Green,
                'warning' => Color::Orange,
                'danger' => Color::Red,
                'info' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            // ->navigationGroups([
            //     NavigationGroup::make()
            //         ->label('لوحة التحكم')
            //         ->icon('heroicon-o-home')
            // ])
            // ->pages([
            //     Pages\Dashboard::class,
            // ])
            ->renderHook(
                'panels::head.end',
                fn() => view('filament.hooks.header-styles')
            )
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
<<<<<<< HEAD
                EnsureUserIsAdmin::class,
=======
                // EnsureUserIsAdmin::class,
>>>>>>> abdulhameed
            ])
          
            ->authMiddleware([
                Authenticate::class,
<<<<<<< HEAD
            ])
            ->plugin(SimpleLightBoxPlugin::make())
        ;
=======
            ]);
>>>>>>> abdulhameed
    }
//     public function boot(): void
// {
//     Filament::registerRenderHook(
//         'styles.end', // مكان إدراج الـ CSS قبل نهاية <head>
//         fn (): string => '<link rel="stylesheet" href="' . asset('css/filament-custom.css') . '"/>'
//     );
// }
public function boot(): void
{
    FilamentAsset::register([
        Css::make('custom-filament', asset('css/custom-filament.css')),
    ]);
}
}
