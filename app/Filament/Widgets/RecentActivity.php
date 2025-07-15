<?php
// app/Filament/Widgets/RecentActivity.php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Enums\Role;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivity extends BaseWidget
{

    protected static ?int $sort = 5;


    protected static ?string $heading = 'الطلاب الجدد (آخر 10)';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::where('role', Role::Student)
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('img')
                    ->label('الصورة')
                    ->disk('public_direct')
                    ->circular()
                    ->defaultImageUrl('/images/default-avatar.png'),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('البريد')
                    ->searchable(),

                Tables\Columns\TextColumn::make('plan.name')
                    ->label('الخطة')
                    ->placeholder('غير محدد'),

                Tables\Columns\TextColumn::make('subscription_end_date')
                    ->label('انتهاء الاشتراك')
                    ->date('Y-m-d')
                    ->badge()
                    ->color(function ($state) {
                        if (!$state) return 'gray';
                        return $state > now() ? 'success' : 'danger';
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('عرض')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => route('filament.admin.resources.users.view', $record)),
            ]);
    }
}
