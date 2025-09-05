<?php

namespace App\Filament\Student\Resources\NoteResource\Pages;

use App\Filament\Student\Resources\NoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNote extends EditRecord
{
    protected static string $resource = NoteResource::class;


    public function getBreadcrumb(): string
    {
        return 'تعديل ملاحظة';
    }

    public function getTitle(): string
    {
        return 'تعديل ملاحظة';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->label('عرض الملاحظة'),
            Actions\DeleteAction::make()->label('حذف ملاحظة'),
        ];
    }
}
