<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\StudyPlan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;


use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Indicator;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

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
                    ->label('الصورة الشخصية')
                    ->disk('public_direct')
                    ->directory('user-img'),


                TextInput::make('name')->required()->label('الاسم الكامل'),
                TextInput::make('father')->label('اسم الأب'),
                // DatePicker::make('birthdate')->label('تاريخ الميلاد'),

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
                    ->preload()
                    ->nullable(),

                DatePicker::make('birthdate')
                    ->label('تاريخ الميلاد')
                    ->reactive() // مهم لتحديث كلمة المرور عند تغيير التاريخ
                    ->afterStateUpdated(function ($state, Set $set) {
                        if ($state) {
                            $year = date('Y', strtotime($state));
                            $set('password', $year);
                        }
                    }),

                DatePicker::make('registration_date')
                    ->label('تاريخ التسجيل')
                    ->default(now()) // القيمة الافتراضية هي التاريخ الحالي
                    ->required(),

                DatePicker::make('subscription_end_date')
                    ->label('تاريخ انتهاء الاشتراك')
                    ->nullable()
                    ->after('registration_date'), // التأكد من أن تاريخ الانتهاء بعد تاريخ التسجيل

                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->label('كلمة المرور')
                    ->dehydrateStateUsing(fn($state) => !empty($state) ? bcrypt($state) : null)
                    ->required(fn(string $context) => $context === 'create')
                    ->default(function (Get $get) {
                        $birthdate = $get('birthdate');
                        if ($birthdate) {
                            return date('Y', strtotime($birthdate));
                        }
                        return null;
                    })
                    ->reactive()
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('img')->label('الصورة')->disk('public_direct')->circular(),
                TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('father')
                    ->label('الأب')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('البريد')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('تم نسخ البريد الإلكتروني'),
                TextColumn::make('phone')
                    ->label('الهاتف')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('تم نسخ رقم الهاتف'),

                TextColumn::make('registration_date')
                    ->label('تاريخ التسجيل')
                    ->date('Y-m-d')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('subscription_end_date')
                    ->label('انتهاء الاشتراك')
                    ->date('Y-m-d')
                    ->placeholder('غير محدد')
                    ->badge()
                    ->color(function ($state) {
                        if (!$state) return 'gray';

                        $endDate = \Carbon\Carbon::parse($state);
                        $now = \Carbon\Carbon::now();

                        if ($endDate->isPast()) {
                            return 'danger'; // أحمر للمنتهي
                        } elseif ($endDate->diffInDays($now) <= 30) {
                            return 'warning'; // أصفر للمنتهي خلال 30 يوم
                        }

                        return 'success'; // أخضر للفعال
                    })
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('plan.name')
                    ->label('الخطة الدراسية')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // فلتر حالة الاشتراك
                SelectFilter::make('subscription_status')
                    ->label('حالة الاشتراك')
                    ->options([
                        'active' => 'فعال',
                        'expiring_soon' => 'ينتهي قريباً',
                        'expired' => 'منتهي',
                        'no_subscription' => 'بدون اشتراك',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!$data['value']) return $query;

                        $now = now();

                        switch ($data['value']) {
                            case 'active':
                                return $query->where('subscription_end_date', '>', $now);
                            case 'expiring_soon':
                                return $query->whereBetween('subscription_end_date', [$now, $now->copy()->addDays(30)]);
                            case 'expired':
                                return $query->where('subscription_end_date', '<', $now);
                            case 'no_subscription':
                                return $query->whereNull('subscription_end_date');
                        }
                    }),

                // فلتر الخطة الدراسية
                SelectFilter::make('plan_id')
                    ->label('الخطة الدراسية')
                    ->relationship('plan', 'name')
                    ->searchable()
                    ->preload(),

                // فلتر تاريخ التسجيل
                Filter::make('registration_date')
                    ->form([
                        DatePicker::make('registered_from')
                            ->label('مسجل من تاريخ')
                            ->placeholder('اختر التاريخ'),
                        DatePicker::make('registered_until')
                            ->label('مسجل حتى تاريخ')
                            ->placeholder('اختر التاريخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['registered_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('registration_date', '>=', $date),
                            )
                            ->when(
                                $data['registered_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('registration_date', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['registered_from'] ?? null) {
                            $indicators[] = Indicator::make('مسجل من ' . Carbon::parse($data['registered_from'])->toFormattedDateString())
                                ->removeField('registered_from');
                        }
                        if ($data['registered_until'] ?? null) {
                            $indicators[] = Indicator::make('مسجل حتى ' . Carbon::parse($data['registered_until'])->toFormattedDateString())
                                ->removeField('registered_until');
                        }
                        return $indicators;
                    }),

                // فلتر انتهاء الاشتراك
                Filter::make('subscription_expiry')
                    ->form([
                        DatePicker::make('expires_from')
                            ->label('ينتهي من تاريخ')
                            ->placeholder('اختر التاريخ'),
                        DatePicker::make('expires_until')
                            ->label('ينتهي حتى تاريخ')
                            ->placeholder('اختر التاريخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['expires_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('subscription_end_date', '>=', $date),
                            )
                            ->when(
                                $data['expires_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('subscription_end_date', '<=', $date),
                            );
                    }),

                // فلتر الطلاب بدون رقم هاتف
                TernaryFilter::make('has_phone')
                    ->label('لديه رقم هاتف')
                    ->trueLabel('لديه رقم هاتف')
                    ->falseLabel('بدون رقم هاتف')
                    ->queries(
                        true: fn(Builder $query) => $query->whereNotNull('phone')->where('phone', '!=', ''),
                        false: fn(Builder $query) => $query->whereNull('phone')->orWhere('phone', ''),
                    ),

                // فلتر سريع للاشتراكات المنتهية اليوم
                Filter::make('expires_today')
                    ->toggle()
                    ->label('ينتهي اليوم')
                    ->query(fn(Builder $query): Builder => $query->whereDate('subscription_end_date', today())),

                // فلتر الطلاب الجدد (آخر 7 أيام)
                Filter::make('new_students')
                    ->toggle()
                    ->label('طلاب جدد (آخر 7 أيام)')
                    ->query(fn(Builder $query): Builder => $query->where('registration_date', '>=', now()->subDays(7))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),

                // إضافة إجراء سريع لتجديد الاشتراك
                Tables\Actions\Action::make('extend_subscription')
                    ->label('تجديد الاشتراك')
                    ->icon('heroicon-o-clock')
                    ->color('success')
                    ->form([
                        DatePicker::make('new_expiry_date')
                            ->label('تاريخ انتهاء جديد')
                            ->required()
                            ->minDate(today())
                            ->default(function ($record) {
                                return $record->subscription_end_date
                                    ? $record->subscription_end_date->addYear()
                                    : now()->addYear();
                            }),
                    ])
                    ->action(function (array $data, $record) {
                        $record->update([
                            'subscription_end_date' => $data['new_expiry_date']
                        ]);

                        Notification::make()
                            ->title('تم تجديد الاشتراك بنجاح')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف جماعي'),

                // إضافة إجراء جماعي لتجديد الاشتراكات
                Tables\Actions\BulkAction::make('bulk_extend_subscription')
                    ->label('تجديد الاشتراكات')
                    ->icon('heroicon-o-clock')
                    ->color('success')
                    ->form([
                        DatePicker::make('new_expiry_date')
                            ->label('تاريخ انتهاء جديد للجميع')
                            ->required()
                            ->minDate(today()),
                    ])
                    ->action(function (Collection $records, array $data) {
                        $records->each(function ($record) use ($data) {
                            $record->update([
                                'subscription_end_date' => $data['new_expiry_date']
                            ]);
                        });

                        Notification::make()
                            ->title('تم تجديد ' . $records->count() . ' اشتراك بنجاح')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->persistSortInSession()
            ->persistSearchInSession()
            ->persistFiltersInSession();
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
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
