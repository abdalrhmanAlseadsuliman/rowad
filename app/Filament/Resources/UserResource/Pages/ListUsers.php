<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;


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
            Actions\CreateAction::make()->label('إضافة طالب'),
        ];
    }
}
