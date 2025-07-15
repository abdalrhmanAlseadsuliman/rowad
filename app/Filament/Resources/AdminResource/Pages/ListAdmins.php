<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdmins extends ListRecords
{
    protected static string $resource = AdminResource::class;




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
            Actions\CreateAction::make()->label('إضافة مدير'),
        ];
    }
}
