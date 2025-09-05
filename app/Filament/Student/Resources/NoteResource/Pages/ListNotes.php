<?php

namespace App\Filament\Student\Resources\NoteResource\Pages;

use App\Filament\Student\Resources\NoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNotes extends ListRecords
{
    protected static string $resource = NoteResource::class;

    public function getBreadcrumb(): string
    {
        return 'عرض الملاحظات';
    }

    public function getTitle(): string
    {
        return 'الملاحظات';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('اضافة ملاحظة'),
        ];
    }
}
