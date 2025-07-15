<?php
// app/Filament/Widgets/ContentStats.php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\StudyPlan;
use Filament\Widgets\ChartWidget;

class ContentStats extends ChartWidget
{
    protected static ?int $sort = 4;


    protected static ?string $heading = 'توزيع المحتوى';
    protected static string $color = 'info';

    protected function getData(): array
    {
        $booksWithoutPlans = Book::whereDoesntHave('studyPlans')->count();
        $booksWithPlans = Book::whereHas('studyPlans')->count();
        $emptyPlans = StudyPlan::whereDoesntHave('books')->count();
        $activePlans = StudyPlan::whereHas('books')->count();

        return [
            'datasets' => [
                [
                    'label' => 'إحصائيات المحتوى',
                    'data' => [$booksWithPlans, $booksWithoutPlans, $activePlans, $emptyPlans],
                    'backgroundColor' => [
                        '#3b82f6',
                        '#ef4444',
                        '#10b981',
                        '#f59e0b',
                    ],
                ],
            ],
            'labels' => ['كتب مع خطط', 'كتب بدون خطط', 'خطط فعالة', 'خطط فارغة'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
