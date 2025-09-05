<?php

namespace App\Filament\Student\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RecentlyReadBook;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Student\Resources\RecentlyReadBookResource\Pages;

class RecentlyReadBookResource extends Resource
{
    protected static ?string $model = RecentlyReadBook::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'الكتب المقروءة';
    protected static ?string $pluralModelLabel = 'الكتب المقروءة';


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tables\Columns\TextColumn::make('book.namebook')->label('اسم الكتاب'),
            Tables\Columns\TextColumn::make('book.subject')->label('المادة'),
            Tables\Columns\TextColumn::make('current_page')->label('آخر صفحة'),
            Tables\Columns\TextColumn::make('last_read_at')->label('آخر قراءة'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    Tables\Columns\TextColumn::make('book.namebook')
                        ->label('اسم الكتاب')
                        ->searchable(),
                    ImageColumn::make('book.cover_image')
                        ->label('غلاف الكتاب')
                        ->disk('public_direct')
                        ->height(120)
                        ->width(90)
                        ->circular(false),
                    Tables\Columns\TextColumn::make('book.subject')
                        ->label('المادة'),
                    Tables\Columns\TextColumn::make('current_page')
                        ->label('آخر صفحة')
                        ->numeric()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('last_read_at')
                        ->label('آخر قراءة')
                        ->dateTime()
                        ->sortable(),
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض')
                    ->icon('heroicon-o-book-open')
                    ->color('success')
                    ->url(function ($record) {
                        // التحويل إلى صفحة عرض الكتاب من resource الكتب
                        return route('filament.student.resources.books.view', [
                            'record' => $record->book_id
                        ]);
                    })
                    ->openUrlInNewTab(false), // أو true إذا كنت تريد فتحه في تبويب جديد

                // يمكنك إضافة زر آخر للذهاب مباشرة لقراءة الكتاب

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف'),
                ]),
            ])
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
                'xl' => 3,
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecentlyReadBooks::route('/'),
            'view' => Pages\ViewRecentlyReadBook::route('/{record}'),
        ];
    }
}
