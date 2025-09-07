@php
$user = auth()->user();
$hour = now()->hour;
$greeting = $hour < 12 ? 'صباح الخير' : ($hour < 17 ? 'مساء الخير' : 'مساء الخير' ); @endphp
    <x-filament-widgets::widget>
    <!-- الوضع النهاري: خلفية ملونة مع نص أبيض -->
    <!-- الوضع المظلم: نفس الأسلوب -->
    <div
        class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl p-6 shadow-lg border border-primary-200 dark:border-primary-800">
        <div class="flex items-center justify-between">
            <!-- الجزء الأيسر -->
            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                <!-- صورة المستخدم -->
                @if($user->img)
                <img src="{{ asset('storage/' . $user->img) }}" alt="{{ $user->name }}"
                    class="w-16 h-16 rounded-full border-3 border-white/30 object-cover shadow-lg">
                @else
                <div
                    class="w-16 h-16 bg-white/25 rounded-full border-2 border-white/40 flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                </div>
                @endif

                <!-- التحية -->
                <div>
                    <p class="text-white font-medium text-sm opacity-90">{{ $greeting }}</p>
                    <h2 class="text-xl font-bold text-white mb-1">{{ $user->name }}</h2>
                    @if($user->plan)
                    <div class="inline-flex items-center px-2 py-1 bg-white/20 rounded-full text-xs text-white">
                        <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z" />
                        </svg>
                        {{ $user->plan->name }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- زر تسجيل الخروج -->
            <form method="POST" action="{{ route('filament.admin.auth.logout') }}" class="inline">
                @csrf
                <button type="submit"
                    class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-200"
                    title="تسجيل الخروج" style="color:#5674d0">
                    تسجيل الخروج
                    <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="color:#5674d0">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <!-- <span class="hidden sm:inline">تسجيل الخروج</span> -->
                </button>
            </form>
        </div>

        <!-- معلومات إضافية -->
        <div class="mt-4 pt-3 border-t border-white/20">
            <div class="flex items-center justify-between text-white/80 text-sm">
                <div class="flex items-center">
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ now()->translatedFormat('l، j F Y') }}
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ now()->format('H:i') }}
                </div>
            </div>
        </div>

        <form id="logout-form" action="{{ route('filament.student.auth.logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>
    </x-filament-widgets::widget>