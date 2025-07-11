<?php

namespace App\Filament\Student\Resources;

use App\Models\Book;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Student\Resources\FavoriteResource\Pages;

class FavoriteResource extends Resource
{
    // ✅ بدل الموديل ليكون Book وليس Favorite
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'المفضلة';

    // ✅ فقط الكتب التي أضافها المستخدم للمفضلة
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('favoritedByUsers', function ($query) {
                $query->where('user_id', auth()->id());
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
            'index' => Pages\ListFavorites::route('/'),
        ];
    }
}
