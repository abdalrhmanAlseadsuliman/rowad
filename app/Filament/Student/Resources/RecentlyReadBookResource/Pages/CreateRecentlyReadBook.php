<?php

namespace App\Filament\Student\Resources\RecentlyReadBookResource\Pages;

use App\Filament\Student\Resources\RecentlyReadBookResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRecentlyReadBook extends CreateRecord
{

    public function getBreadcrumb(): string
    {
        return 'إضافة';
    }

    public function getTitle(): string
    {
        return 'إضافة كتاب مقروء';
    }
    protected static string $resource = RecentlyReadBookResource::class;
}
