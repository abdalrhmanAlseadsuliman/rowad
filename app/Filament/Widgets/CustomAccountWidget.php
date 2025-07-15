<?php
// app/Filament/Widgets/CustomAccountWidget.php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CustomAccountWidget extends Widget
{
    protected static string $view = 'filament.widgets.simple-account';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 0;

    protected function getViewData(): array
    {
        return [
            'user' => auth()->user(),
        ];
    }
}


// app/Filament/Widgets/CustomAccountWidget.php

// namespace App\Filament\Widgets;

// use Filament\Widgets\Widget;

// class CustomAccountWidget extends Widget
// {
//     protected static string $view = 'filament.widgets.custom-account';
//     protected int | string | array $columnSpan = 'full';
//     protected static ?int $sort = 0;

//     protected function getViewData(): array
//     {
//         $user = auth()->user();

//         return [
//             'user' => $user,
//             'totalStudents' => \App\Models\User::where('role', \App\Enums\Role::Student)->count(),
//             'activeStudents' => \App\Models\User::where('role', \App\Enums\Role::Student)
//                 ->where('subscription_end_date', '>', now())->count(),
//             'todayRegistrations' => \App\Models\User::whereDate('created_at', today())->count(),
//             'currentTime' => now()->format('H:i'),
//             'currentDate' => now()->format('Y-m-d'),
//         ];
//     }
// }