<?php

namespace App\Filament\Resources\StudyPlanResource\Pages;

use App\Filament\Resources\StudyPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudyPlans extends ListRecords
{
    protected static string $resource = StudyPlanResource::class;


    public function getBreadcrumb(): string
    {
        return 'القائمة';
    }

    public function getTitle(): string
    {
        return 'المدراء';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إضافة خطة دراسية'),
        ];
    }
}
