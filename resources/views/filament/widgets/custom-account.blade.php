{{-- resources/views/filament/widgets/custom-account.blade.php --}}
{{-- resources/views/filament/widgets/custom-account.blade.php --}}
<div class="filament-widget">
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Header مع تدرج -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 dark:from-blue-600 dark:to-purple-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <!-- صورة المستخدم -->
                    <div class="relative">
                        @if($user->img)
                        <img class="h-16 w-16 rounded-full object-cover border-4 border-white dark:border-gray-200 shadow-lg"
                            src="{{ Storage::disk('public_direct')->url($user->img) }}" alt="{{ $user->name }}">
                        @else
                        <div
                            class="h-16 w-16 rounded-full bg-white dark:bg-gray-200 border-4 border-white dark:border-gray-200 shadow-lg flex items-center justify-center">
                            <svg class="h-8 w-8 text-gray-600 dark:text-gray-700" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        @endif
                        <!-- نقطة الحالة النشطة -->
                        <div
                            class="absolute bottom-0 right-0 h-5 w-5 bg-green-400 border-2 border-white dark:border-gray-200 rounded-full">
                        </div>
                    </div>

                    <!-- معلومات المستخدم -->
                    <div class="text-white">
                        <h2 class="text-xl font-bold mb-1">
                            مرحباً، {{ $user->name }}
                        </h2>
                        <p class="text-blue-100 dark:text-blue-200 text-sm font-medium mb-1">
                            {{ $user->email }}
                        </p>
                        <div class="flex items-center text-xs text-blue-100 dark:text-blue-200">
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            مدير النظام
                        </div>
                    </div>
                </div>

                <!-- معلومات الوقت والتاريخ -->
                <div class="text-right rtl:text-left text-white">
                    <div class="text-2xl font-bold">{{ $currentTime }}</div>
                    <div class="text-sm text-blue-100 dark:text-blue-200">{{ $currentDate }}</div>
                    <div class="text-xs text-blue-100 dark:text-blue-200 mt-1">
                        {{ now()->locale('ar')->dayName }}
                    </div>
                </div>
            </div>
        </div>

        <!-- المحتوى الرئيسي -->
        <div class="p-6">
            <!-- الإحصائيات السريعة -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div
                    class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 text-center border border-blue-100 dark:border-blue-800">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalStudents }}</div>
                    <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">إجمالي الطلاب</div>
                </div>

                <div
                    class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 text-center border border-green-100 dark:border-green-800">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $activeStudents }}</div>
                    <div class="text-sm text-green-600 dark:text-green-400 font-medium">طلاب فعالين</div>
                </div>

                <div
                    class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 text-center border border-purple-100 dark:border-purple-800">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $todayRegistrations }}</div>
                    <div class="text-sm text-purple-600 dark:text-purple-400 font-medium">تسجيلات اليوم</div>
                </div>
            </div>

            <!-- الأزرار السريعة -->
            <div class="space-y-3">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">إجراءات سريعة</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <a href="{{ route('filament.admin.resources.users.index') }}"
                        class="group flex items-center justify-center px-4 py-3 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 ml-2 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                        <span class="font-medium">إدارة الطلاب</span>
                    </a>

                    <a href="{{ route('filament.admin.resources.books.index') }}"
                        class="group flex items-center justify-center px-4 py-3 bg-green-500 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-700 text-white rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 ml-2 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                        <span class="font-medium">إدارة الكتب</span>
                    </a>

                    <a href="{{ route('filament.admin.resources.study-plans.index') }}"
                        class="group flex items-center justify-center px-4 py-3 bg-purple-500 hover:bg-purple-600 dark:bg-purple-600 dark:hover:bg-purple-700 text-white rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 ml-2 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="font-medium">الخطط الدراسية</span>
                    </a>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        آخر تسجيل دخول: {{ now()->diffForHumans() }}
                    </div>
                    <div class="flex items-center">
                        <div class="h-2 w-2 bg-green-400 rounded-full ml-2 animate-pulse"></div>
                        متصل الآن
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* تحسين الخطوط العربية */
    .filament-widget {
        font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* تحسين الظلال في الوضع المظلم */
    .dark .shadow-lg {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
    }
</style>