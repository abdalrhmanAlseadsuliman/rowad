<?php

namespace App\Filament\Student\Resources\UserResource\Pages;

use App\Filament\Student\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function getBreadcrumb(): string
    {
        return 'إضافة';
    }

    public function getTitle(): string
    {
        return 'إضافة طالب';
    }
}
