<?php
// app/Filament/Pages/Dashboard.php (إنشاء هذا الملف)

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'لوحة التحكم';
    protected static ?string $navigationLabel = 'لوحة التحكم';

    public function getTitle(): string
    {
        return 'لوحة التحكم';
    }

    public function getHeading(): string
    {
        return 'لوحة التحكم';
    }
}
