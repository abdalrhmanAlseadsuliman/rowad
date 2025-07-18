<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Models\Book;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Actions\Action;

use Filament\Tables\Columns\Layout\Group;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;



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
                    // الصورة مع تحسينات
                    ImageColumn::make('cover_image')
                        ->label('غلاف الكتاب')
                        ->disk('public_direct')
                        ->height(300)
                        ->width(300)
                        ->circular(false)
                        ->defaultImageUrl('/images/default-book-cover.png')
                        ->extraImgAttributes(['loading' => 'lazy']),

                    // المعلومات الأساسية
                    Stack::make([
                        TextColumn::make('namebook')
                            ->label('اسم الكتاب')
                            ->weight(FontWeight::Bold)
                            ->size(TextColumn\TextColumnSize::Large)
                            ->color('primary')
                            ->searchable()
                            ->limit(50)
                            ->tooltip(function (TextColumn $column): ?string {
                                $state = $column->getState();
                                return strlen($state) > 50 ? $state : null;
                            }),

                        // معلومات فرعية
                        TextColumn::make('subject_year')
                            ->label('المادة والسنة')
                            ->getStateUsing(
                                fn($record) => ($record->subject ? $record->subject . ' - ' : '') .
                                    ($record->educational_year ?? 'غير محدد')
                            )
                            ->badge()
                            ->color('info')
                            ->icon('heroicon-m-academic-cap'),

                        TextColumn::make('author')
                            ->label('المؤلف')
                            ->icon('heroicon-m-user')
                            ->color('gray')
                            ->searchable()
                            ->limit(30),

                        TextColumn::make('publisher')
                            ->label('الناشر')
                            ->icon('heroicon-m-building-office')
                            ->color('gray')
                            ->searchable()
                            ->limit(25),

                        // ISBN وحجم الملف
                        Stack::make([
                            TextColumn::make('isbn')
                                ->label('ISBN')
                                ->copyable()
                                ->copyMessage('تم نسخ ISBN')
                                ->placeholder('غير متوفر')
                                ->size(TextColumn\TextColumnSize::Small),

                            TextColumn::make('file_size')
                                ->label('حجم الملف')
                                ->getStateUsing(function ($record) {
                                    if ($record->pdf_path && file_exists(public_path('storage/' . $record->pdf_path))) {
                                        $bytes = filesize(public_path('storage/' . $record->pdf_path));
                                        return self::formatBytes($bytes);
                                    }
                                    return 'غير متوفر';
                                })
                                ->badge()
                                ->color('secondary')
                                ->size(TextColumn\TextColumnSize::Small),
                        ])->space(1),

                        // إحصائيات
                        Stack::make([
                            TextColumn::make('plans_count')
                                ->label('الخطط')
                                ->counts('studyPlans')
                                ->badge()
                                ->color('success')
                                ->icon('heroicon-m-academic-cap'),

                            TextColumn::make('reads_count')
                                ->label('القراءات')
                                ->getStateUsing(function ($record) {
                                    return $record->recentlyReadBooks()->count() ?? 0;
                                })
                                ->badge()
                                ->color('warning')
                                ->icon('heroicon-m-eye'),
                        ])->space(1),

                        // حالة الكتاب
                        TextColumn::make('status')
                            ->label('الحالة')
                            ->getStateUsing(function ($record) {
                                if (!$record->pdf_path) return 'بدون ملف';
                                if (!file_exists(public_path('storage/' . $record->pdf_path))) return 'ملف مفقود';
                                return 'متوفر';
                            })
                            ->badge()
                            ->color(function ($state) {
                                return match ($state) {
                                    'متوفر' => 'success',
                                    'بدون ملف' => 'danger',
                                    'ملف مفقود' => 'warning',
                                    default => 'gray'
                                };
                            }),

                        TextColumn::make('created_at')
                            ->label('تاريخ الإضافة')
                            ->since()
                            ->color('gray')
                            ->size(TextColumn\TextColumnSize::Small),
                    ])->space(1),
                ])->space(2),
            ])
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
                'lg' => 3,

            ])
            ->filters([
                // فلتر المادة
                SelectFilter::make('subject')
                    ->label('المادة')
                    ->options(function () {
                        return \App\Models\Book::whereNotNull('subject')
                            ->distinct()
                            ->pluck('subject', 'subject')
                            ->toArray();
                    })
                    ->searchable(),

                // فلتر السنة الدراسية
                SelectFilter::make('educational_year')
                    ->label('السنة الدراسية')
                    ->options(function () {
                        return \App\Models\Book::whereNotNull('educational_year')
                            ->distinct()
                            ->pluck('educational_year', 'educational_year')
                            ->toArray();
                    })
                    ->searchable(),

                // فلتر الناشر
                SelectFilter::make('publisher')
                    ->label('الناشر')
                    ->options(function () {
                        return \App\Models\Book::whereNotNull('publisher')
                            ->distinct()
                            ->pluck('publisher', 'publisher')
                            ->toArray();
                    })
                    ->searchable(),

                // فلتر بدون خطط دراسية
                Filter::make('without_plans')
                    ->toggle()
                    ->label('بدون خطط دراسية')
                    ->query(fn(Builder $query): Builder => $query->whereDoesntHave('studyPlans')),

                // فلتر الكتب الجديدة
                Filter::make('recent_books')
                    ->toggle()
                    ->label('كتب جديدة (آخر 30 يوم)')
                    ->query(fn(Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(30))),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل')
                    ->color('warning'),

                Tables\Actions\ViewAction::make()
                    ->label('عرض')
                    ->color('info'),

                // إجراء لقراءة الكتاب
                Tables\Actions\Action::make('read_book')
                    ->label('قراءة')
                    ->icon('heroicon-o-book-open')
                    ->color('success')
                    ->url(fn($record) => $record->pdf_path ?
                        route('student.book.read', $record) : null)
                    ->openUrlInNewTab()
                    ->visible(fn($record) => $record->pdf_path !== null),

                // إضافة/إزالة من الخطط
                Tables\Actions\Action::make('manage_plans')
                    ->label('إدارة الخطط')
                    ->icon('heroicon-o-academic-cap')
                    ->form([
                        Select::make('study_plans')
                            ->label('الخطط الدراسية')
                            ->multiple()
                            ->relationship('studyPlans', 'name')
                            ->preload()
                            ->default(fn($record) => $record->studyPlans->pluck('id')->toArray()),
                    ])
                    ->action(function (array $data, $record) {
                        $record->studyPlans()->sync($data['study_plans']);

                        Notification::make()
                            ->title('تم تحديث الخطط الدراسية')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('حذف جماعي'),

                // إضافة للخطة الدراسية
                Tables\Actions\BulkAction::make('add_to_plan')
                    ->label('إضافة لخطة دراسية')
                    ->icon('heroicon-o-academic-cap')
                    ->form([
                        Select::make('study_plan_id')
                            ->label('الخطة الدراسية')
                            ->options(\App\Models\StudyPlan::pluck('name', 'id')->toArray())
                            ->required()
                            ->searchable(),
                    ])
                    ->action(function (Collection $records, array $data) {
                        $studyPlan = \App\Models\StudyPlan::find($data['study_plan_id']);

                        foreach ($records as $record) {
                            $studyPlan->books()->syncWithoutDetaching($record->id);
                        }

                        Notification::make()
                            ->title('تم إضافة ' . $records->count() . ' كتاب للخطة')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([12, 24, 48])
            ->persistFiltersInSession()
            ->persistSortInSession();
    }


    // إضافة method مساعدة لحساب حجم الملف
    private static function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }



    public static function getRelations(): array
    {
        return [
            \App\Filament\Student\Resources\BookResource\RelationManagers\NotesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'view' => Pages\ViewBook::route('/{record}'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
