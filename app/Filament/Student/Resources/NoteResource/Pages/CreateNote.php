<?php

namespace App\Filament\Student\Resources\NoteResource\Pages;

use App\Filament\Student\Resources\NoteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNote extends CreateRecord
{
    protected static string $resource = NoteResource::class;

    public function getBreadcrumb(): string
    {
        return 'ملاحظة جديدة';
    }

    public function getTitle(): string
    {
        return 'إضافة ملاحظة جديدة';
    }
}
