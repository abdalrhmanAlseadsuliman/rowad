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

    protected static ?string $navigationLabel = 'المدراء';
    protected static ?string $pluralModelLabel = 'المدراء';
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
                    ->maxLength(255)
                    ->autocomplete('off'),
                Forms\Components\TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->label('كلمة المرور')
                    ->revealable()
                    ->password()
                    ->required()
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->autocomplete('new-password')
                    ->visible(function (string $operation, $record) {
                        // إظهار الحقل في صفحة الإنشاء
                        if ($operation === 'create') {
                            return true;
                        }

                        // إظهار الحقل في صفحة التعديل فقط للمستخدم نفسه
                        if ($operation === 'edit') {
                            return auth()->id() === $record->id;
                        }

                        return false;
                    }),

                Forms\Components\Hidden::make('role')
                    ->default(Role::Admin->value),
                FileUpload::make('img')
                    ->label('الصورة الشخصية')
                    ->image()
                    ->imageEditor()
                    ->circleCropper()
                    ->disk('public_direct')
                    ->directory('admin-img'),
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
