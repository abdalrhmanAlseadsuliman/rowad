<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    public function expired()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // التعامل مع المدراء - التحويل لمسار الأدمن
        if ($user->role === Role::Admin) {
            return redirect('/admin') // أو المسار الصحيح للأدمن
                ->with('info', 'مرحباً بك في لوحة التحكم');
        }

        // التعامل مع الطلاب - التحويل لمسار الطلاب
        if ($user->role === Role::Student && $user->isSubscriptionActive()) {
            return redirect('/student') // المسار المطلوب
                ->with('success', 'اشتراكك فعال! مرحباً بك.');
        }

        // إظهار صفحة انتهاء الاشتراك للطلاب منتهيي الاشتراك
        return view('subscription_expired');
    }
}
