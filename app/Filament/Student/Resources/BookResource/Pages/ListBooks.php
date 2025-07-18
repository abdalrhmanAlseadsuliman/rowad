<?php

namespace App\Filament\Student\Resources\BookResource\Pages;

use App\Filament\Student\Resources\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBooks extends ListRecords
{
    protected static string $resource = BookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // #[On('refreshTable')]
    // public function refreshTable(): void
    // {
    //     $this->resetTable();
    // }
}