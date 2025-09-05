<?php

namespace App\Filament\Student\Resources\UserResource\Pages;

use App\Filament\Student\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;


    public function getBreadcrumb(): string
    {
        return 'عرض';
    }

    public function getTitle(): string
    {
        return 'عرض طالب';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('تعديل'),
        ];
    }
}
