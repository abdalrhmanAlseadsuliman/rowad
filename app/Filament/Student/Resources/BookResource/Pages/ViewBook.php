<?php

namespace App\Filament\Student\Resources\BookResource\Pages;

use Filament\Actions;
use App\Models\RecentlyReadBook;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Student\Resources\BookResource;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;

class ViewBook extends ViewRecord
{
    protected static string $resource = BookResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = Auth::user();
        $bookId = $this->record->id;

        if ($user) {
            RecentlyReadBook::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'book_id' => $bookId,
                ],
                [
                    'current_page' => 1,
                    'last_read_at' => now(),
                ]
            );

            $recentBooks = RecentlyReadBook::where('user_id', $user->id)
                ->orderByDesc('last_read_at')
                ->pluck('id');

            if ($recentBooks->count() > 5) {
                $idsToDelete = $recentBooks->slice(5);
                RecentlyReadBook::whereIn('id', $idsToDelete)->delete();
            }
        }

        return $data;
    }



    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
