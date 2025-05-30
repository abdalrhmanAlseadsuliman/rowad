<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use App\Enums\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;


    protected static ?string $navigationLabel = 'الطلاب';
    protected static ?string $pluralModelLabel = 'الطلاب';
    protected static ?string $modelLabel = 'طالب';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('father')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\DatePicker::make('birthdate'),
               FileUpload::make('img')
                    ->image()
                    ->imageEditor()
                    ->circleCropper()
                     ->disk('public_direct')
                    ->label('الصورة الشخصية'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                   Select::make('role')
                    ->label('الدور')
                    ->options(Role::class)
                    ->required(),
                //  Select::make('plan_id')
                //     ->label('الخطة الدراسية')
                //     ->relationship('plan', 'name')
                //     ->searchable()
                //     ->nullable(),
                Select::make('plan_id')
                    ->label(label: 'الخطة الدراسية')
                    ->options(\App\Models\StudyPlan::pluck('name', 'id'))
                    ->searchable()
                    ->nullable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('img')->label('الصورة')->circular(),
                TextColumn::make('name')->label('الاسم'),
                TextColumn::make('email')->label('البريد'),
                TextColumn::make('phone')->label('الهاتف'),
                TextColumn::make('role')->label('الدور')->badge(),
                TextColumn::make('plan.name')->label('الخطة الدراسية'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
