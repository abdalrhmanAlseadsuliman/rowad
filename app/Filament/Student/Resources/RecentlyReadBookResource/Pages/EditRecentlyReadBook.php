<?php

namespace App\Filament\Student\Resources\RecentlyReadBookResource\Pages;

use App\Filament\Student\Resources\RecentlyReadBookResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecentlyReadBook extends EditRecord
{
    protected static string $resource = RecentlyReadBookResource::class;


    public function getBreadcrumb(): string
    {
        return 'تعديل';
    }

    public function getTitle(): string
    {
        return 'تعديل كتاب';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->label('عرض'),
            Actions\DeleteAction::make()->label('حذف'),
        ];
    }
}
