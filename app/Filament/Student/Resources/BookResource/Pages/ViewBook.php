<?php

namespace App\Filament\Student\Resources\BookResource\Pages;

use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use App\Models\RecentlyReadBook;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Contracts\HasInfolists;
use App\Filament\Student\Resources\BookResource;
use Filament\Infolists\Concerns\InteractsWithInfolists;

class ViewBook extends ViewRecord
{
    protected static string $resource = BookResource::class;
    protected static string $view = 'filament.student.resources.book-resource.pages.view-book';

    // بيانات الملاحظة الجديدة
    public $newNote = '';

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


            // إضافة زر سريع لإضافة ملاحظة
            Actions\Action::make('add_note')
                ->label('إضافة ملاحظة سريعة')
                ->icon('heroicon-o-pencil-square')
                ->color('info')
                ->form([
                    Forms\Components\RichEditor::make('note')
                        ->label('الملاحظة')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->record->notes()->create([
                        'note' => $data['note'],
                        'user_id' => auth()->id(),
                    ]);

                    \Filament\Notifications\Notification::make()
                        ->title('تم إضافة الملاحظة بنجاح')
                        ->success()
                        ->send();

                    // إعادة تحميل الصفحة لعرض الملاحظة الجديدة
                    $this->redirect(request()->header('Referer'));
                }),

            Actions\EditAction::make(),
        ];
    }

    // حفظ الملاحظة الجديدة
    public function saveNote(): void
    {
        // التحقق من صحة البيانات
        if (empty(trim($this->newNote))) {
            \Filament\Notifications\Notification::make()
                ->title('يرجى كتابة ملاحظة!')
                ->warning()
                ->send();
            return;
        }

        // إنشاء الملاحظة
        $this->record->notes()->create([
            'note' => $this->newNote,
            'user_id' => auth()->id(),
        ]);

        // إعادة تعيين الحقل
        $this->newNote = '';

        // إشعار نجاح
        \Filament\Notifications\Notification::make()
            ->title('تم إضافة الملاحظة بنجاح!')
            ->success()
            ->send();
    }

    // حذف ملاحظة
    public function deleteNote($noteId): void
    {
        $note = $this->record->notes()
            ->where('user_id', auth()->id())
            ->findOrFail($noteId);

        $note->delete();

        \Filament\Notifications\Notification::make()
            ->title('تم حذف الملاحظة')
            ->success()
            ->send();
    }

    // جلب الملاحظات
    public function getNotes()
    {
        return $this->record->notes()
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // إضافة method لتمرير البيانات للـ view
    protected function getViewData(): array
    {
        return [
            'record' => $this->record,
            'bookUrl' => route('student.book.read', $this->record),
            'notes' => $this->getNotes(),
        ];
    }
}
