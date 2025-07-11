<?php

namespace App\Filament\Student\Resources\RecentlyReadBookResource\Pages;

use App\Filament\Student\Resources\RecentlyReadBookResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRecentlyReadBook extends ViewRecord
{
    protected static string $resource = RecentlyReadBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
