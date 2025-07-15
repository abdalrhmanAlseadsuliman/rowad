<?php
// app/Filament/Widgets/UsersChart.php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Enums\Role;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class UsersChart extends ChartWidget
{


    protected static ?int $sort = 2;

    protected static ?string $heading = 'تسجيل الطلاب (آخر 12 شهر)';
    protected static string $color = 'info';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $months = collect();
        $data = collect();

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months->push($month->format('M Y'));

            $count = User::where('role', Role::Student)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $data->push($count);
        }

        return [
            'datasets' => [
                [
                    'label' => 'طلاب جدد',
                    'data' => $data->toArray(),
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#1d4ed8',
                ],
            ],
            'labels' => $months->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
