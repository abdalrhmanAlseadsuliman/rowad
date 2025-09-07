<?php

namespace App\Filament\Student\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome-widget';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -3; // لجعله يظهر في الأعلى

    public function getViewData(): array
    {
        $user = Auth::user();

        return [
            'userName' => $user->name,
            'userImage' => $user->img ? asset('storage/' . $user->img) : null,
            'greeting' => $this->getGreeting(),
            'studyPlan' => $user->plan?->name,
        ];
    }

    private function getGreeting(): string
    {
        $hour = now()->hour;

        if ($hour < 12) {
            return 'صباح الخير';
        } elseif ($hour < 17) {
            return 'مساء الخير';
        } else {
            return 'مساء الخير';
        }
    }
}
