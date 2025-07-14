<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>انتهاء الاشتراك</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'arabic': ['Cairo', 'Arial', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-red-50 to-orange-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- بطاقة رئيسية -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center relative overflow-hidden">
            <!-- خلفية زخرفية -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-red-500 to-orange-500"></div>

            <!-- أيقونة التحذير -->
            <div class="mb-6">
                <div class="mx-auto w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">انتهى اشتراكك!</h1>
                <p class="text-gray-600">عذراً، لقد انتهت صلاحية اشتراكك في المنصة</p>
            </div>

            <!-- الرسالة -->
            <div class="mb-8">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800 font-medium">
                        🚫 لا يمكنك الوصول للمحتوى حالياً
                    </p>
                </div>

                <p class="text-gray-700 leading-relaxed mb-4">
                    يرجى التواصل مع الإدارة لتجديد اشتراكك والاستمتاع بجميع المميزات المتاحة.
                </p>

                <div class="text-sm text-gray-500 space-y-1">
                    <p>✅ إعادة تفعيل فورية</p>
                    <p>✅ استرداد جميع بياناتك</p>
                    <p>✅ دعم فني متواصل</p>
                </div>
            </div>

            <!-- أزرار التواصل -->
            <div class="space-y-4">
                <!-- زر الواتساب -->
                <a href="https://wa.me/966565479600?text=مرحباً، أريد تجديد اشتراكي في المنصة"
                    target="_blank"
                    class="block w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <div class="flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.109" />
                        </svg>
                        <span>تواصل عبر الواتساب</span>
                    </div>
                    <div class="text-sm opacity-90 mt-1">
                        +966 56 547 9600
                    </div>
                </a>

                <!-- زر تسجيل الخروج -->
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-xl transition-all duration-300 border border-gray-300">
                        تسجيل الخروج
                    </button>
                </form>

            </div>

            <!-- معلومات إضافية -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    ساعات العمل: من 9 صباحاً إلى 6 مساءً
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    الرد خلال دقائق معدودة ⚡
                </p>
            </div>
        </div>

        <!-- رسالة تشجيعية -->
        <div class="mt-6 text-center">
            <p class="text-gray-600 text-sm">
                💡 <strong>تلميح:</strong> احفظ رقم الواتساب في جهات الاتصال للتواصل السريع
            </p>
        </div>
    </div>

    <!-- تأثيرات بصرية -->
    <div class="fixed top-10 left-10 w-20 h-20 bg-red-200 rounded-full opacity-20 animate-pulse"></div>
    <div class="fixed bottom-10 right-10 w-16 h-16 bg-orange-200 rounded-full opacity-20 animate-pulse delay-1000"></div>
</body>

</html>