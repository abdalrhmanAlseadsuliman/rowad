<?php

namespace App\Filament\Resources\RecentlyReadBookResource\Pages;

use App\Filament\Resources\RecentlyReadBookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecentlyReadBooks extends ListRecords
{
    protected static string $resource = RecentlyReadBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
