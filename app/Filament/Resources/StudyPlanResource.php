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

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Indicator;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Filament\Support\Enums\FontWeight;
use Carbon\Carbon;


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

                Forms\Components\Textarea::make('description')
                    ->label('الوصف')
                    ->rows(3)
                    ->maxLength(1000),

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
                    ->label('اسم الخطة')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),

                TextColumn::make('description')
                    ->label('الوصف')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    })
                    ->toggleable(),

                TextColumn::make('books_count')
                    ->label('عدد الكتب')
                    ->counts('books')
                    ->sortable()
                    ->badge()
                    ->color(function ($state) {
                        if ($state == 0) return 'danger';
                        if ($state <= 5) return 'warning';
                        if ($state <= 15) return 'info';
                        return 'success';
                    }),

                TextColumn::make('users_count')
                    ->label('عدد الطلاب المشتركين')
                    ->counts('users')
                    ->sortable()
                    ->badge()
                    ->color('primary')
                    ->toggleable(),

                TextColumn::make('active_subscriptions_count')
                    ->label('الاشتراكات الفعالة')
                    ->getStateUsing(function ($record) {
                        return $record->users()
                            ->where('subscription_end_date', '>', now())
                            ->count();
                    })
                    ->badge()
                    ->color('success')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // فلتر عدد الكتب
                SelectFilter::make('books_count_range')
                    ->label('عدد الكتب')
                    ->options([
                        'empty' => 'بدون كتب (0)',
                        'few' => 'قليلة (1-5)',
                        'medium' => 'متوسطة (6-15)',
                        'many' => 'كثيرة (16+)',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!$data['value']) return $query;

                        switch ($data['value']) {
                            case 'empty':
                                return $query->whereDoesntHave('books');
                            case 'few':
                                return $query->withCount('books')
                                    ->having('books_count', '>=', 1)
                                    ->having('books_count', '<=', 5);
                            case 'medium':
                                return $query->withCount('books')
                                    ->having('books_count', '>=', 6)
                                    ->having('books_count', '<=', 15);
                            case 'many':
                                return $query->withCount('books')
                                    ->having('books_count', '>=', 16);
                        }
                    }),

                // فلتر عدد الطلاب
                SelectFilter::make('students_count_range')
                    ->label('عدد الطلاب')
                    ->options([
                        'none' => 'بدون طلاب (0)',
                        'few' => 'قليل (1-10)',
                        'medium' => 'متوسط (11-50)',
                        'many' => 'كثير (51+)',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!$data['value']) return $query;

                        switch ($data['value']) {
                            case 'none':
                                return $query->whereDoesntHave('users');
                            case 'few':
                                return $query->withCount('users')
                                    ->having('users_count', '>=', 1)
                                    ->having('users_count', '<=', 10);
                            case 'medium':
                                return $query->withCount('users')
                                    ->having('users_count', '>=', 11)
                                    ->having('users_count', '<=', 50);
                            case 'many':
                                return $query->withCount('users')
                                    ->having('users_count', '>=', 51);
                        }
                    }),

                // فلتر تاريخ الإنشاء
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('أنشئت من تاريخ')
                            ->placeholder('اختر التاريخ'),
                        DatePicker::make('created_until')
                            ->label('أنشئت حتى تاريخ')
                            ->placeholder('اختر التاريخ'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators[] = Indicator::make('أنشئت من ' . Carbon::parse($data['created_from'])->toFormattedDateString())
                                ->removeField('created_from');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators[] = Indicator::make('أنشئت حتى ' . Carbon::parse($data['created_until'])->toFormattedDateString())
                                ->removeField('created_until');
                        }
                        return $indicators;
                    }),

                // فلتر سريع للخطط الفارغة
                Filter::make('empty_plans')
                    ->toggle()
                    ->label('خطط بدون كتب')
                    ->query(fn(Builder $query): Builder => $query->whereDoesntHave('books')),

                // فلتر سريع للخطط غير المستخدمة
                Filter::make('unused_plans')
                    ->toggle()
                    ->label('خطط غير مستخدمة')
                    ->query(fn(Builder $query): Builder => $query->whereDoesntHave('users')),

                // فلتر للخطط الجديدة (آخر 30 يوم)
                Filter::make('recent_plans')
                    ->toggle()
                    ->label('خطط جديدة (آخر 30 يوم)')
                    ->query(fn(Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(30))),

                // فلتر الخطط النشطة
                Filter::make('active_plans')
                    ->toggle()
                    ->label('خطط بها طلاب فعالين')
                    ->query(function (Builder $query): Builder {
                        return $query->whereHas('users', function ($q) {
                            $q->where('subscription_end_date', '>', now());
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),

                // إضافة إجراء لعرض الكتب
                Tables\Actions\Action::make('view_books')
                    ->label('عرض الكتب')
                    ->icon('heroicon-o-book-open')
                    ->color('info')
                    ->url(fn($record) => route('filament.admin.resources.books.index', ['tableFilters[plan_id][value]' => $record->id]))
                    ->openUrlInNewTab(),

                // إضافة إجراء لعرض الطلاب
                Tables\Actions\Action::make('view_students')
                    ->label('عرض الطلاب')
                    ->icon('heroicon-o-users')
                    ->color('warning')
                    ->url(fn($record) => route('filament.admin.resources.users.index', ['tableFilters[plan_id][value]' => $record->id]))
                    ->openUrlInNewTab(),

                // إضافة إجراء لنسخ الخطة
                Tables\Actions\Action::make('duplicate')
                    ->label('نسخ الخطة')
                    ->icon('heroicon-o-document-duplicate')
                    // ->color('secondary')
                    ->form([
                        TextInput::make('name')
                            ->label('اسم الخطة الجديدة')
                            ->required()
                            ->default(fn($record) => $record->name . ' - نسخة'),
                    ])
                    ->action(function (array $data, $record) {
                        // نسخ السجل مع استثناء الأعمدة المحسوبة
                        $newPlan = $record->replicate([
                            'books_count',
                            'users_count',
                            'active_subscriptions_count'
                        ]);
                        $newPlan->name = $data['name'];
                        $newPlan->save();

                        // نسخ العلاقات
                        $record->books()->each(function ($book) use ($newPlan) {
                            $newPlan->books()->attach($book->id);
                        });

                        Notification::make()
                            ->title('تم نسخ الخطة بنجاح')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف جماعي'),

                // إضافة إجراء جماعي لتصدير قائمة الخطط
                Tables\Actions\BulkAction::make('export_plans')
                    ->label('تصدير البيانات')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (Collection $records) {
                        // يمكنك إضافة منطق التصدير هنا
                        Notification::make()
                            ->title('سيتم تصدير ' . $records->count() . ' خطة')
                            ->info()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->persistSortInSession()
            ->persistSearchInSession()
            ->persistFiltersInSession()
            ->striped()
            ->paginated([10, 25, 50, 100]);
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
