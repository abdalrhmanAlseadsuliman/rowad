<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBooks extends ListRecords
{
    protected static string $resource = BookResource::class;

    public function getBreadcrumb(): string
    {
        return 'القائمة';
    }

    public function getTitle(): string
    {
        return 'الكتب';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('إضافة كتاب'),
        ];
    }
}
