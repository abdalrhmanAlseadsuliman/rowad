<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\StudyPlan;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use App\Enums\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'الطلاب';
    protected static ?string $pluralModelLabel = 'الطلاب';
    protected static ?string $modelLabel = 'طالب';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', Role::Student);
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('img')
                    ->image()
                    ->imageEditor()
                    ->circleCropper()
                    ->label('الصورة الشخصية'),

                TextInput::make('name')->required()->label('الاسم الكامل'),
                TextInput::make('father')->label('اسم الأب'),
                DatePicker::make('birthdate')->label('تاريخ الميلاد'),

                TextInput::make('email')->email()->required()->unique(ignoreRecord: true)->label('البريد الإلكتروني'),
                TextInput::make('phone')->label('رقم الهاتف'),

                Select::make('role')
                    ->label('الدور')
                    ->options(Role::class)
                    ->required(),

                Select::make('plan_id')
                    ->label('الخطة الدراسية')
                    ->relationship('plan', 'name')
                    ->searchable()
                    ->nullable(),

                TextInput::make('password')
                    ->password()
                    ->label('كلمة المرور')
                    ->dehydrateStateUsing(fn ($state) => !empty($state) ? bcrypt($state) : null)
                    ->required(fn (string $context) => $context === 'create')
                    ->label('كلمة المرور'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('img')->label('الصورة')->disk('public_direct')->circular(),
                TextColumn::make('name')->label('الاسم'),
                TextColumn::make('father')->label('الأب'),
                TextColumn::make('email')->label('البريد'),
                TextColumn::make('phone')->label('الهاتف'),
                // TextColumn::make('role')->label('الدور')->badge(),
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
            // يمكن إضافة علاقات هنا لاحقًا مثل الملاحظات أو الكتب المقروءة
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
