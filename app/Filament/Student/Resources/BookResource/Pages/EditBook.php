<?php

namespace App\Filament\Student\Resources\BookResource\Pages;

use App\Filament\Student\Resources\BookResource;
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
        return 'تعديل كتاب';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
