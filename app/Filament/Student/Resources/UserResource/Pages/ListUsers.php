<?php

namespace App\Filament\Student\Resources\UserResource\Pages;

use App\Filament\Student\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getBreadcrumb(): string
    {
        return 'قائمة';
    }

    public function getTitle(): string
    {
        return 'معلوماتي';
    }

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make()->,
    //     ];
    // }
}
