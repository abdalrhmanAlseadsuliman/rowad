<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RecentlyReadBook;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RecentlyReadBookResource\Pages;
use App\Filament\Resources\RecentlyReadBookResource\RelationManagers;

class RecentlyReadBookResource extends Resource
{
    protected static ?string $model = RecentlyReadBook::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'الكتب المُتصفحة';
    protected static ?string $pluralModelLabel = 'الكتب المُتصفحة';
    protected static ?string $modelLabel = 'كتاب تم تصفحه';
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('الطالب')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Select::make('book_id')
                    ->label('الكتاب')
                    ->relationship('book', 'namebook')
                    ->searchable()
                    ->required(),

                TextInput::make('current_page')
                    ->label('آخر صفحة تم الوصول إليها')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                Forms\Components\DateTimePicker::make('last_read_at')
                    ->label('آخر قراءة')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('الطالب')
                    ->searchable(),

                TextColumn::make('book.namebook')
                    ->label('الكتاب')
                    ->searchable(),

                TextColumn::make('current_page')
                    ->label('الصفحة الحالية'),

                TextColumn::make('last_read_at')
                    ->label('آخر قراءة')
                    ->since(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإدخال')
                    ->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف جماعي'),
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
            'create' => Pages\CreateRecentlyReadBook::route('/create'),
            'edit' => Pages\EditRecentlyReadBook::route('/{record}/edit'),
        ];
    }
}
