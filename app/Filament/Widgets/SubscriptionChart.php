<?php
// app/Filament/Widgets/SubscriptionChart.php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Enums\Role;
use Filament\Widgets\ChartWidget;

class SubscriptionChart extends ChartWidget
{

    protected static ?int $sort = 3;

    protected static ?string $heading = 'حالة الاشتراكات';
    protected static string $color = 'warning';



    protected function getData(): array
    {
        $active = User::where('role', Role::Student)
            ->where('subscription_end_date', '>', now())
            ->count();

        $expired = User::where('role', Role::Student)
            ->where('subscription_end_date', '<', now())
            ->count();

        $noSubscription = User::where('role', Role::Student)
            ->whereNull('subscription_end_date')
            ->count();

        $expiringSoon = User::where('role', Role::Student)
            ->whereBetween('subscription_end_date', [now(), now()->addDays(30)])
            ->count();

        return [
            'datasets' => [
                [
                    'data' => [$active, $expired, $noSubscription, $expiringSoon],
                    'backgroundColor' => [
                        '#10b981', // أخضر - فعال
                        '#ef4444', // أحمر - منتهي
                        '#6b7280', // رمادي - بدون اشتراك
                        '#f59e0b', // أصفر - ينتهي قريباً
                    ],
                ],
            ],
            'labels' => ['فعال', 'منتهي', 'بدون اشتراك', 'ينتهي قريباً'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
