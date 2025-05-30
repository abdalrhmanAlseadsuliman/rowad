<?php

namespace App\Filament\Student\Resources\FavoriteResource\Pages;

use App\Filament\Student\Resources\FavoriteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFavorite extends CreateRecord
{
    protected static string $resource = FavoriteResource::class;
}
