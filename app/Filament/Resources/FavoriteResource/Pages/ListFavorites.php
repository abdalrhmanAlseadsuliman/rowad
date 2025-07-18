<?php

namespace App\Filament\Resources\FavoriteResource\Pages;

use App\Filament\Resources\FavoriteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFavorites extends ListRecords
{
    protected static string $resource = FavoriteResource::class;
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
            // Actions\CreateAction::make()->label(''),
        ];
    }
}
