<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\Layout\Stack;



class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationLabel = 'الكتب';
    protected static ?string $pluralModelLabel = 'الكتب';
    protected static ?string $modelLabel = 'كتاب';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('cover_image')
                    ->label('صورة الغلاف')
                    ->image()
                    ->imageEditor()
                    ->disk('public_direct')
                    ->directory('book-covers'),

                TextInput::make('namebook')
                    ->label('اسم الكتاب')
                    ->required(),

                TextInput::make('subject')
                    ->label('المادة'),

                TextInput::make('educational_year')
                    ->label('السنة الدراسية'),

                TextInput::make('author')
                    ->label('المؤلف'),

                TextInput::make('publisher')
                    ->label('الناشر'),

                TextInput::make('isbn')
                    ->label('ISBN'),

                FileUpload::make('pdf_path')
                    ->label('ملف PDF')
                    ->required()
                     ->disk('public_direct')
                    ->acceptedFileTypes(['application/pdf'])
                    ->directory('books-pdf')
                      ->directory('books'),
            ]);
    }

  public static function table(Table $table): Table
{
    return $table
        ->columns([
            Stack::make([
                ImageColumn::make('cover_image')
                    ->label('غلاف الكتاب')
                    ->disk('public_direct')
                    ->height(120)
                    ->width(90)
                    ->circular(false),

                TextColumn::make('namebook')
                    ->label('اسم الكتاب')
                    ->weight('bold'),

                TextColumn::make('subject')
                    ->label('المادة'),

                TextColumn::make('educational_year')
                    ->label('السنة الدراسية'),

                TextColumn::make('author')
                    ->label('المؤلف'),

                TextColumn::make('publisher')
                    ->label('الناشر')
                    ->wrap(),

                TextColumn::make('isbn')
                    ->label('ISBN'),
            ]),
        ])
        ->contentGrid([
            'sm' => 1,   // موبايل
            'md' => 2,   // تابلت
            'xl' => 3,   // شاشات أكبر
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
