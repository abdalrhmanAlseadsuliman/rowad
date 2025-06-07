<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Enums\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class AdminResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'المديرين';
    protected static ?string $pluralModelLabel = 'المديرين';
    protected static ?string $modelLabel = 'مدير';
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    // عرض المديرين فقط
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', Role::Admin);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('كلمة المرور')
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => Hash::make($state)),
                Forms\Components\Hidden::make('role')
                    ->default(Role::Admin->value),
                FileUpload::make('img')
                    ->image()
                    ->imageEditor()
                    ->circleCropper()
                    ->disk('public_direct')
                    ->label('الصورة الشخصية'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('img')
                    ->label('الصورة')
                    ->disk('public_direct')
                    ->circular(),
                TextColumn::make('name')->label('الاسم')->searchable(),
                TextColumn::make('email')->label('البريد')->searchable(),
                TextColumn::make('phone')->label('الهاتف'),
                TextColumn::make('created_at')->label('تاريخ الإنشاء')->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف جماعي'),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'view' => Pages\ViewAdmin::route('/{record}'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
