<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBook extends EditRecord
{
    protected static string $resource = BookResource::class;


    public function getBreadcrumb(): string
    {
        return 'تعديل';
    }

    public function getTitle(): string
    {
        return 'تعديل بيانات الكتاب';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('حذف'),
        ];
    }
}
