<?php

namespace App\Filament\Resources\FavoriteResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\FavoriteResource;

class ViewFavorite extends ViewRecord
{
    protected static string $resource = FavoriteResource::class;

    public function getBreadcrumb(): string
    {
        return 'عرض';
    }

    public function getTitle(): string
    {
        return 'عرض الكتاب المفضل';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('تعديل'),
            Actions\DeleteAction::make()->label('حذف'),
        ];
    }
}
