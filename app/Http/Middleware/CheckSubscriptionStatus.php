<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionStatus
{
    /**
     * المسارات المستثناة من التحقق
     */
    protected $except = [
        'subscription/expired',
        'login',
        'logout',
        'register',
        'password/*',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // التحقق من المسارات المستثناة
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return $next($request);
            }
        }

        // التحقق من تسجيل الدخول
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // التحقق فقط للطلاب
        if ($user->role === Role::Student) {
            // التحقق من حالة الاشتراك
            if (!$user->subscription_end_date || $user->subscription_end_date->isPast()) {
                return redirect()->route('subscription.expired');
            }
        }

        return $next($request);
    }
}
