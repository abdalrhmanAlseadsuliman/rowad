{{-- resources/views/filament/student/resources/book-resource/pages/view-book.blade.php --}}
<x-filament-panels::page>
    <div class="space-y-6">
        {{-- معلومات الكتاب --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        {{ $record->title }}
                    </h1>
                    <p class="text-gray-600 dark:text-gray-300">
                        المؤلف: {{ $record->author ?? 'غير محدد' }}
                    </p>
                    @if($record->description)
                        <p class="text-gray-600 dark:text-gray-300 mt-2">
                            {{ $record->description }}
                        </p>
                    @endif
                </div>

                <div class="flex space-x-2">
                    <button
                        onclick="openBookViewer()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z"></path>
                        </svg>
                        قراءة الكتاب
                    </button>
                </div>
            </div>

            {{-- عارض الكتاب --}}
            <div id="book-viewer" class="hidden">
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold">{{ $record->title }}</h3>
                        <div class="flex space-x-2">
                            <button
                                onclick="toggleFullscreen()"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm">
                                ملء الشاشة
                            </button>
                            <button
                                onclick="closeBookViewer()"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                إغلاق
                            </button>
                        </div>
                    </div>
                </div>

                <div class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
                    <iframe
                        id="book-frame"
                        src=""
                        width="100%"
                        height="600"
                        style="border: none;"
                        oncontextmenu="return false;"
                        title="{{ $record->title }}">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openBookViewer() {
            const viewer = document.getElementById('book-viewer');
            const frame = document.getElementById('book-frame');

            viewer.classList.remove('hidden');
            frame.src = '{{ $bookUrl }}';

            // تسجيل بداية القراءة
            updateReadingProgress();
        }

        function closeBookViewer() {
            const viewer = document.getElementById('book-viewer');
            const frame = document.getElementById('book-frame');

            viewer.classList.add('hidden');
            frame.src = '';
        }

        function toggleFullscreen() {
            const viewer = document.getElementById('book-viewer');

            if (viewer.requestFullscreen) {
                viewer.requestFullscreen();
            } else if (viewer.webkitRequestFullscreen) {
                viewer.webkitRequestFullscreen();
            } else if (viewer.msRequestFullscreen) {
                viewer.msRequestFullscreen();
            }
        }

        function updateReadingProgress() {
            // يمكنك إضافة AJAX call هنا لتحديث تقدم القراءة
            fetch('{{ route("student.book.progress", $record) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    book_id: {{ $record->id }},
                    started_reading: true
                })
            });
        }

        // منع النقر بالزر الأيمن والطباعة
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && (e.keyCode === 83 || e.keyCode === 80)) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</x-filament-panels::page>
