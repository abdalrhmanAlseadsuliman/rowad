<?php

namespace App\Filament\Student\Resources;

use App\Models\Book;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Student\Resources\BookResource\Pages;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'كتبي الدراسية';
    protected static ?string $pluralModelLabel = 'كتبي الدراسية';


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('studyPlans', function ($query) {
                $query->where('study_plans.id', auth()->user()->plan_id);
            });
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    Tables\Columns\TextColumn::make('namebook')->label('اسم الكتاب')->searchable(),
                    Tables\Columns\TextColumn::make('subject')->label('المادة')->searchable(),
                    Tables\Columns\TextColumn::make('educational_year')->label('الصف الدراسي'),
                    ImageColumn::make('cover_image')
                        ->label('غلاف الكتاب')
                        ->disk('public_direct')
                        ->height(120)
                        ->width(90)
                        ->circular(false),
                    Tables\Columns\TextColumn::make('author')->label('المؤلف'),
                    Tables\Columns\TextColumn::make('publisher')->label('الناشر'),
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),

                Action::make('toggleFavorite')
                    ->label(
                        fn($record) =>
                        auth()->user()->favoriteBooks->contains($record->id)
                            ? 'إزالة من المفضلة'
                            : 'إضافة إلى المفضلة'
                    )
                    ->icon(
                        fn($record) =>
                        auth()->user()->favoriteBooks->contains($record->id)
                            ? 'heroicon-o-bookmark-slash'
                            : 'heroicon-o-bookmark'
                    )
                    ->color(
                        fn($record) =>
                        auth()->user()->favoriteBooks->contains($record->id)
                            ? 'danger'
                            : 'primary'
                    )
                    ->action(function ($record) {
                        $user = auth()->user();
                        if ($user->favoriteBooks->contains($record->id)) {
                            $user->favoriteBooks()->detach($record->id);
                        } else {
                            $user->favoriteBooks()->attach($record->id);
                        }
                        $user->load('favoriteBooks');
                    })
                    ->after(function ($record, $action) {
                        $action->getLivewire()->dispatch('$refresh');
                    }),
            ])
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
                'xl' => 3,
            ])
            ->bulkActions([]);
    }


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'view' => Pages\ViewBook::route('/{record}'),
        ];
    }
}
