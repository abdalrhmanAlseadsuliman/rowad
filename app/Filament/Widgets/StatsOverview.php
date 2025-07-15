<?php
// app/Filament/Widgets/StatsOverview.php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Book;
use App\Models\StudyPlan;
use App\Enums\Role;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // حساب الإحصائيات
        $totalStudents = User::where('role', Role::Student)->count();
        $activeStudents = User::where('role', Role::Student)
            ->where('subscription_end_date', '>', now())
            ->count();
        $expiredStudents = User::where('role', Role::Student)
            ->where('subscription_end_date', '<', now())
            ->count();
        $expiringSoon = User::where('role', Role::Student)
            ->whereBetween('subscription_end_date', [now(), now()->addDays(30)])
            ->count();

        $totalBooks = Book::count();
        $totalPlans = StudyPlan::count();

        $newStudentsThisMonth = User::where('role', Role::Student)
            ->whereMonth('created_at', now()->month)
            ->count();

        $lastMonthStudents = User::where('role', Role::Student)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->count();

        $studentGrowth = $lastMonthStudents > 0
            ? (($newStudentsThisMonth - $lastMonthStudents) / $lastMonthStudents) * 100
            : 0;

        return [
            Stat::make('إجمالي الطلاب', $totalStudents)
                ->description('العدد الكلي للطلاب المسجلين')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([7, 12, 15, 18, 22, 25, $totalStudents]),

            Stat::make('الطلاب الفعالين', $activeStudents)
                ->description('طلاب بـ اشتراك صالح')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([5, 8, 12, 15, 18, 20, $activeStudents]),

            Stat::make('اشتراكات منتهية', $expiredStudents)
                ->description('تحتاج لتجديد')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('ينتهي قريباً', $expiringSoon)
                ->description('خلال 30 يوم')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('إجمالي الكتب', $totalBooks)
                ->description('الكتب المتاحة')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('info'),

            Stat::make('الخطط الدراسية', $totalPlans)
                ->description('خطط متاحة')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('secondary'),

            Stat::make('طلاب جدد هذا الشهر', $newStudentsThisMonth)
                ->description($studentGrowth >= 0 ? "+{$studentGrowth}% عن الشهر الماضي" : "{$studentGrowth}% عن الشهر الماضي")
                ->descriptionIcon($studentGrowth >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($studentGrowth >= 0 ? 'success' : 'danger')
                ->chart([3, 5, 8, 12, 15, 18, $newStudentsThisMonth]),

            Stat::make('معدل التفعيل', number_format(($activeStudents / max($totalStudents, 1)) * 100, 1) . '%')
                ->description('نسبة الطلاب الفعالين')
                ->descriptionIcon('heroicon-m-chart-pie')
                ->color('primary'),
        ];
    }
}
