<?php

namespace App\Filament\Resources\AdminResource\Pages;

use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\AdminResource;
use Filament\Infolists;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Split;
class ViewAdmin extends ViewRecord
{
    protected static string $resource = AdminResource::class;
     public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('المعلومات الشخصية')
                    ->schema([
                        Infolists\Components\ImageEntry::make('img')
                            ->label('الصورة الشخصية')
                            ->disk('public_direct')
                            ->circular()
                            ->height(100)
                            ->width(100),
                        Infolists\Components\TextEntry::make('name')
                            ->label('الاسم')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('email')
                            ->label('البريد الإلكتروني')
                            ->icon('heroicon-m-envelope')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('phone')
                            ->label('رقم الهاتف')
                            ->icon('heroicon-m-phone')
                            ->placeholder('غير محدد'),
                        Infolists\Components\TextEntry::make('role')
                            ->label('الدور')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state->getLabel()),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('معلومات النظام')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('تاريخ الإنشاء')
                            ->dateTime()
                            ->icon('heroicon-m-calendar'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('آخر تحديث')
                            ->dateTime()
                            ->icon('heroicon-m-clock'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }
}
