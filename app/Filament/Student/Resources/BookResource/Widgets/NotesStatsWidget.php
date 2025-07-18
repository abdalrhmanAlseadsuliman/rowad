<?php

namespace App\Filament\Student\Resources\BookResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class NotesStatsWidget extends BaseWidget
{
    public ?Model $record = null;

    protected function getStats(): array
    {
        $notesCount = $this->record->notes()
            ->where('user_id', auth()->id())
            ->count();

        $latestNote = $this->record->notes()
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        return [
            Stat::make('عدد الملاحظات', $notesCount)
                ->description('إجمالي ملاحظاتك على هذا الكتاب')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('آخر ملاحظة', $latestNote ? $latestNote->created_at->diffForHumans() : 'لا توجد ملاحظات')
                ->description('تاريخ آخر ملاحظة تم إضافتها')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
        ];
    }
}
