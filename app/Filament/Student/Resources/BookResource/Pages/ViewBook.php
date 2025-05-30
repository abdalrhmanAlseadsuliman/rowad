<?php

namespace App\Filament\Student\Resources\BookResource\Pages;

use App\Filament\Student\Resources\BookResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\RecentlyReadBook;
use Illuminate\Support\Facades\Auth;

class ViewBook extends ViewRecord
{
    protected static string $resource = BookResource::class;
protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = Auth::user();
        $bookId = $this->record->id;

        if ($user) {
            // 1. تحديث أو إنشاء السجل
            $recent = RecentlyReadBook::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'book_id' => $bookId,
                ],
                [
                    'current_page' => 1,
                    'last_read_at' => now(),
                ]
            );

            // 2. جلب آخر 5 كتب فقط (مرتبة من الأحدث)
            $recentBooks = RecentlyReadBook::where('user_id', $user->id)
                ->orderByDesc('last_read_at')
                ->pluck('id');

            // 3. حذف أي سجلات أقدم من آخر 5 فقط
            if ($recentBooks->count() > 5) {
                $idsToDelete = $recentBooks->slice(5); // الباقي بعد أول 5
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
