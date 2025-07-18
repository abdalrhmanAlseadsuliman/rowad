<?php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\NoteResource\Pages;
use App\Models\Note;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;

class NoteResource extends Resource
{
    protected static ?string $model = Note::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'ملاحظاتي';
    protected static ?string $pluralModelLabel = 'الملاحظات';
    protected static ?string $modelLabel = 'ملاحظة';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ربط الملاحظة بالكتاب
                Forms\Components\Select::make('book_id')
                    ->label('اسم الكتاب')
                    ->relationship('book', 'namebook') // يفترض وجود حقل title في Model Book
                    ->required()
                    ->searchable()->preload(),

                // حقل الملاحظة النصية
                \Filament\Forms\Components\RichEditor::make('note')
                    ->label('الملاحظة')
                    ->required(),

                // تعيين user_id تلقائيًا دون إظهاره
                Forms\Components\Hidden::make('user_id')
                    ->default(fn() => auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    Tables\Columns\TextColumn::make('book.namebook')
                        ->label('الكتاب')
                        ->sortable()
                        ->searchable(),

                    Tables\Columns\TextColumn::make('note')
                        ->label('الملاحظة')
                        ->limit(50)
                        ->wrap(),

                    Tables\Columns\TextColumn::make('created_at')
                        ->label('تاريخ الإضافة')
                        ->dateTime()
                        ->sortable(),
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل'),
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

    // فلترة السجلات لعرض فقط ملاحظات الطالب الحالي
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotes::route('/'),
            'create' => Pages\CreateNote::route('/create'),
            'view' => Pages\ViewNote::route('/{record}'),
            'edit' => Pages\EditNote::route('/{record}/edit'),
        ];
    }
}
