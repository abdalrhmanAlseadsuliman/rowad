<?php

namespace App\Filament\Resources\StudyPlanResource\Pages;

use App\Filament\Resources\StudyPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudyPlan extends EditRecord
{
    protected static string $resource = StudyPlanResource::class;

    public function getBreadcrumb(): string
    {
        return 'تعديل';
    }

    public function getTitle(): string
    {
        return 'تعديل الخطة الدراسية';
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('حذف'),
        ];
    }
}
