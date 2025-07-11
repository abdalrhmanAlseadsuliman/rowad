<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\BookResource\Pages;
use App\Models\Book;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'كتبي الدراسية';

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
            Tables\Columns\TextColumn::make('namebook')->label('اسم الكتاب')->searchable(),
            Tables\Columns\TextColumn::make('subject')->label('المادة')->searchable(),
            Tables\Columns\TextColumn::make('educational_year')->label('الصف الدراسي'),
            Tables\Columns\ImageColumn::make('cover_image')->label('الغلاف'),
            Tables\Columns\TextColumn::make('author')->label('المؤلف'),
            Tables\Columns\TextColumn::make('publisher')->label('الناشر'),
        ])
        ->actions([
            Tables\Actions\ViewAction::make()->label('عرض'),

            // ✅ هذا هو زر المفضلة، تم نقله لمكانه الصحيح داخل ->actions()
      Action::make('toggleFavorite')
    ->label(fn ($record) =>
        auth()->user()->favoriteBooks->contains($record->id)
            ? 'إزالة من المفضلة'
            : 'إضافة إلى المفضلة'
    )
    ->icon(fn ($record) =>
        auth()->user()->favoriteBooks->contains($record->id)
            ? 'heroicon-o-bookmark-slash'
            : 'heroicon-o-bookmark'
    )
    ->color(fn ($record) =>
        auth()->user()->favoriteBooks->contains($record->id)
            ? 'danger'
            : 'primary'
    )
    ->action(function ($record) {
        $user = auth()->user();

        // ✅ تبديل المفضلة
        if ($user->favoriteBooks->contains($record->id)) {
            $user->favoriteBooks()->detach($record->id);
        } else {
            $user->favoriteBooks()->attach($record->id);
        }

        // ✅ إعادة تحميل العلاقة حتى تُحدّث الأيقونة واللون فورًا
        $user->load('favoriteBooks');
    })
    ->after(function ($record, $action) {
        
        $action->getLivewire()->dispatch('$refresh');
    })
                   
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
