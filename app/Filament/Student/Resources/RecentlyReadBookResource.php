<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\RecentlyReadBookResource\Pages;
use App\Models\RecentlyReadBook;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RecentlyReadBookResource extends Resource
{
    protected static ?string $model = RecentlyReadBook::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'الكتب المقروءة';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            // لا حاجة لإدخال user_id لأنه يحدد تلقائياً
            // ولا حاجة لإدخال book_id من هنا غالبًا
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('book.namebook')
                    ->label('اسم الكتاب')
                    ->searchable(),
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف'),
                ]),
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
