<?php

namespace App\Filament\Resources\BookResource\Pages;

use Filament\Actions;
use App\Filament\Resources\BookResource;
use Filament\Resources\Pages\ViewRecord;

class ViewBook extends ViewRecord
{
    protected static string $resource = BookResource::class;

    public function getBreadcrumb(): string
    {
        return 'عرض';
    }

    public function getTitle(): string
    {
        return 'عرض الكتاب';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('تعديل'),
            Actions\DeleteAction::make()->label('حذف'),
        ];
    }
}
