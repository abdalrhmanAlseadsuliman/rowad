<?php

namespace App\Filament\Resources\StudyPlanResource\Pages;

use App\Filament\Resources\StudyPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStudyPlan extends CreateRecord
{
    protected static string $resource = StudyPlanResource::class;

    public function getBreadcrumb(): string
    {
        return 'إضافة';
    }

    public function getTitle(): string
    {
        return 'إضافة خطة دراسية';
    }
}
