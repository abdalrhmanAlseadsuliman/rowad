<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudyPlanResource\Pages;
use App\Filament\Resources\StudyPlanResource\RelationManagers;
use App\Models\StudyPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudyPlanResource extends Resource
{
    protected static ?string $model = StudyPlan::class;

    protected static ?string $navigationLabel = 'الخطط الدراسية';
    protected static ?string $pluralModelLabel = 'الخطط الدراسية';
    protected static ?string $modelLabel = 'خطة دراسية';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم الخطة الدراسية')
                    ->required(),

                Select::make('books')
                    ->label('الكتب المرتبطة')
                    ->multiple()
                    ->relationship('books', 'namebook')
                    ->preload()
                    ->searchable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم الخطة'),

                TextColumn::make('books_count')
                    ->label('عدد الكتب')
                    ->counts('books'),
            ])
            ->filters([
                //
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudyPlans::route('/'),
            'create' => Pages\CreateStudyPlan::route('/create'),
            'view' => Pages\ViewStudyPlan::route('/{record}'),
            'edit' => Pages\EditStudyPlan::route('/{record}/edit'),
        ];
    }
}
