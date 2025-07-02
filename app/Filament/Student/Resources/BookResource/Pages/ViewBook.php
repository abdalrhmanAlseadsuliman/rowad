<?php

// namespace App\Filament\Student\Resources\BookResource\Pages;

// use Filament\Actions;
// use App\Models\RecentlyReadBook;
// use Illuminate\Support\Facades\Auth;
// use Filament\Resources\Pages\ViewRecord;
// use Filament\Infolists\Components\TextEntry;
// use App\Filament\Student\Resources\BookResource;
// use Filament\Infolists\Concerns\InteractsWithInfolists;
// use Filament\Infolists\Contracts\HasInfolists;

// class ViewBook extends ViewRecord
// {
//     protected static string $resource = BookResource::class;

//     protected function mutateFormDataBeforeFill(array $data): array
//     {
//         $user = Auth::user();
//         $bookId = $this->record->id;

//         if ($user) {
//             RecentlyReadBook::updateOrCreate(
//                 [
//                     'user_id' => $user->id,
//                     'book_id' => $bookId,
//                 ],
//                 [
//                     'current_page' => 1,
//                     'last_read_at' => now(),
//                 ]
//             );

//             $recentBooks = RecentlyReadBook::where('user_id', $user->id)
//                 ->orderByDesc('last_read_at')
//                 ->pluck('id');

//             if ($recentBooks->count() > 5) {
//                 $idsToDelete = $recentBooks->slice(5);
//                 RecentlyReadBook::whereIn('id', $idsToDelete)->delete();
//             }
//         }

//         return $data;
//     }



//     protected function getHeaderActions(): array
//     {
//         return [
//             Actions\EditAction::make(),
//         ];
//     }
// }



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
    protected static string $view = 'filament.student.resources.book-resource.pages.view-book'; // إضافة view مخصص

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
            Actions\Action::make('read_book')
                ->label('قراءة الكتاب')
                ->icon('heroicon-o-book-open')
                ->color('success')
                ->url(fn () => route('student.book.read', $this->record))
                ->openUrlInNewTab(),
            Actions\EditAction::make(),
        ];
    }

    // إضافة method لتمرير البيانات للـ view
    protected function getViewData(): array
    {
        return [
            'record' => $this->record,
            'bookUrl' => route('student.book.read', $this->record),
        ];
    }
}
