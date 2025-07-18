<?php

namespace App\Filament\Student\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Student\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'معلوماتي';
    protected static ?string $modelLabel = 'معلومات الطالب';
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('الاسم')->required(),
            Forms\Components\TextInput::make('father')->label('اسم الأب'),
            Forms\Components\DatePicker::make('birthdate')->label('تاريخ الميلاد'),
            Forms\Components\TextInput::make('email')->label('البريد الإلكتروني')->email()->required(),
            Forms\Components\TextInput::make('phone')->label('رقم الهاتف'),
             FileUpload::make('img')
                    ->image()
                    ->imageEditor()
                    ->circleCropper()
                      ->disk('public_direct')  
                    ->directory('student_images')
                    ->label('الصورة الشخصية'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('الاسم'),
                ImageColumn::make('img')->label('الصورة')->circular(),
                Tables\Columns\TextColumn::make('email')->label('البريد الإلكتروني'),
                Tables\Columns\TextColumn::make('phone')->label('الهاتف'),
                Tables\Columns\TextColumn::make('birthdate')->label('تاريخ الميلاد')->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
            ])
            ->bulkActions([]); // حذف أي عمليات مجمعة
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
