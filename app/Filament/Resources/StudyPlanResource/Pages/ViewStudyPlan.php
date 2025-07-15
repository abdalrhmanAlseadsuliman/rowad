<?php

namespace App\Filament\Resources\StudyPlanResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\StudyPlanResource;


class ViewStudyPlan extends ViewRecord
{
    protected static string $resource = StudyPlanResource::class;

    public function getBreadcrumb(): string
    {
        return 'عرض';
    }

    public function getTitle(): string
    {
        return 'عرض الخطة الدراسية';
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('تعديل'),
            Actions\DeleteAction::make()->label('حذف'),
        ];
    }
}
