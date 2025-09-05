<?php

namespace App\Filament\Student\Resources;

use App\Models\Book;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Student\Resources\FavoriteResource\Pages;
use App\Filament\Student\Resources\BookResource;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;

class FavoriteResource extends Resource
{
    // ✅ بدل الموديل ليكون Book وليس Favorite
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'المفضلة';

    protected static ?string $pluralModelLabel = 'المفضلة';


    // ✅ فقط الكتب التي أضافها المستخدم للمفضلة
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('favoritedByUsers', function ($query) {
                $query->where('user_id', auth()->id());
            });
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            \Filament\Forms\Components\Group::make([
                \Filament\Forms\Components\TextInput::make('namebook')
                    ->label('اسم الكتاب')
                    ->disabled(),

                \Filament\Forms\Components\TextInput::make('subject')
                    ->label('المادة')
                    ->disabled(),

                \Filament\Forms\Components\TextInput::make('educational_year')
                    ->label('الصف الدراسي')
                    ->disabled(),

                \Filament\Forms\Components\TextInput::make('author')
                    ->label('المؤلف')
                    ->disabled(),

                \Filament\Forms\Components\TextInput::make('publisher')
                    ->label('الناشر')
                    ->disabled(),

                \Filament\Forms\Components\FileUpload::make('cover_image')
                    ->label('غلاف الكتاب')
                    ->disk('public_direct')
                    ->image()
                    ->disabled(),
            ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    Tables\Columns\TextColumn::make('namebook')->label('اسم الكتاب')->searchable(),
                    Tables\Columns\TextColumn::make('subject')->label('المادة')->searchable(),
                    Tables\Columns\TextColumn::make('educational_year')->label('الصف الدراسي'),
                    Tables\Columns\ImageColumn::make('cover_image')->label('الغلاف')->disk('public_direct'),
                    Tables\Columns\TextColumn::make('author')->label('المؤلف'),
                    Tables\Columns\TextColumn::make('publisher')->label('الناشر'),
                ])
            ])
            ->contentGrid([
                'sm' => 1,   // موبايل
                'md' => 2,   // تابلت
                'xl' => 3,   // شاشات أكبر
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('عرض')
                    ->icon('heroicon-o-book-open')
                    ->color('success')
                    ->url(function ($record) {
                        // الآن $record هو كتاب، فنستخدم id مباشرة
                        return BookResource::getUrl('view', ['record' => $record->id]);
                    })
                    ->openUrlInNewTab(false),

                // يمكنك إضافة زر للقراءة أيضاً


                // زر لإزالة من المفضلة
                Tables\Actions\Action::make('remove_favorite')
                    ->label('إزالة من المفضلة')
                    ->icon('heroicon-o-heart')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->favoritedByUsers()->detach(auth()->id());

                        // إشعار للمستخدم
                        \Filament\Notifications\Notification::make()
                            ->title('تم إزالة الكتاب من المفضلة')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('remove_all_favorites')
                        ->label('إزالة الكل من المفضلة')
                        ->icon('heroicon-o-heart')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->favoritedByUsers()->detach(auth()->id());
                            }

                            \Filament\Notifications\Notification::make()
                                ->title('تم إزالة الكتب المحددة من المفضلة')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFavorites::route('/'),
        ];
    }
}
