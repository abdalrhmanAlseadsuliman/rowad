{{-- resources/views/filament/widgets/simple-account.blade.php --}}
<div class="filament-simple-account-widget">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex items-center justify-between">
            <!-- معلومات المستخدم -->
            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                <!-- صورة المستخدم -->
                <div class="relative">
                    @if($user->img)
                    <img class="h-12 w-12 rounded-full object-cover border-2 border-blue-200 dark:border-blue-600"
                        src="{{ Storage::disk('public_direct')->url($user->img) }}" alt="{{ $user->name }}">
                    @else
                    <div
                        class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center border-2 border-blue-200 dark:border-blue-600">
                        <span class="text-white font-bold text-lg">
                            {{ substr($user->name, 0, 1) }}
                        </span>
                    </div>
                    @endif
                    <!-- نقطة الحالة النشطة -->
                    <div
                        class="absolute -bottom-1 -right-1 h-4 w-4 bg-green-400 border-2 border-white dark:border-gray-800 rounded-full">
                    </div>
                </div>

                <!-- معلومات أساسية -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        مرحباً، {{ $user->name }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $user->email }}
                    </p>
                </div>
            </div>

            <!-- الجانب الأيمن -->
            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                <!-- بادج المدير -->
                <div
                    class="hidden sm:flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm font-medium">
                    <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    مدير النظام
                </div>

                <!-- الوقت -->
                <div class="hidden md:block text-right rtl:text-left">
                    <div class="text-sm font-medium text-gray-900 dark:text-white" id="current-time">
                        {{ now()->format('H:i') }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ now()->locale('ar')->dayName }}
                    </div>
                </div>

                <!-- زر تسجيل الخروج -->
                <form method="POST" action="{{ route('filament.admin.auth.logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                        class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors duration-200"
                        title="تسجيل الخروج">
                        تسجيل الخروج
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <!-- <span class="hidden sm:inline">تسجيل الخروج</span> -->
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- تحديث الوقت كل دقيقة -->
<script>
    function updateTime() {
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            const now = new Date();
            const timeString = now.toLocaleTimeString('ar-SA', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            timeElement.textContent = timeString;
        }
    }

    // تحديث كل دقيقة
    setInterval(updateTime, 60000);
</script>

<style>
    .filament-simple-account-widget {
        font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
</style>