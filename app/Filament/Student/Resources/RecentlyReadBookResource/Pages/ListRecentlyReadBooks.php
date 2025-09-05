<?php

namespace App\Filament\Student\Resources\RecentlyReadBookResource\Pages;

use App\Filament\Student\Resources\RecentlyReadBookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecentlyReadBooks extends ListRecords
{
    protected static string $resource = RecentlyReadBookResource::class;




    public function getBreadcrumb(): string
    {
        return 'قائمة';
    }

    public function getTitle(): string
    {
        return 'الكتب المقروءة';
    }

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make()->label('اضافة'),
    //     ];
    // }
}