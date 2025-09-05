<?php

namespace App\Filament\Student\Resources\BookResource\Pages;

use App\Filament\Student\Resources\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBook extends CreateRecord
{
    protected static string $resource = BookResource::class;

    public function getBreadcrumb(): string
    {
        return 'اضافة';
    }

    public function getTitle(): string
    {
        return 'كتاب دراسي';
    }
}
