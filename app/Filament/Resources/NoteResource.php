<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Note;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\NoteResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\NoteResource\RelationManagers;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'ملاحظات الطلاب';
    protected static ?string $pluralModelLabel = 'الملاحظات';
    protected static ?string $modelLabel = 'ملاحظة';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

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

                Textarea::make('content')
                    ->label('محتوى الملاحظة')
                    ->rows(6)
                    ->required()
                    ->maxLength(1000),
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

                TextColumn::make('content')
                    ->label('الملاحظة')
                    ->limit(50),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->since(),
            ])
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
            'index' => Pages\ListNotes::route('/'),
            'create' => Pages\CreateNote::route('/create'),
            'edit' => Pages\EditNote::route('/{record}/edit'),
        ];
    }
}
