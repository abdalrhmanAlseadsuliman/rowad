<x-filament-panels::page>
    <div class="w-full">
        <!-- المحتوى الرئيسي -->
        <div class="grid grid-cols-12 gap-6 p-4">
            <!-- قائمة الفهرس والعلامات والملاحظات -->
            <div class="col-span-3 space-y-6">
                <!-- الفهرس -->


                <!-- العلامات -->
                <div class="bg-white rounded-xl shadow p-5 border">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2" style="color:black">الصفحات التي توقفت عندها
                    </h3>
                    <ul id="bookmarks" class="space-y-2 text-sm" style="color:black"></ul>
                </div>

                <!-- نموذج الملاحظات البسيط -->
                <div class="bg-white rounded-xl shadow p-5 border">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4" style="color:black">📝 ملاحظاتي</h3>
                    <style>

                    </style>
                    <!-- نموذج إضافة ملاحظة بسيط -->


                    <!-- قائمة الملاحظات -->
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @forelse($notes as $note)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded-lg text-sm"
                            wire:key="note-{{ $note->id }}">
                            <div class="text-gray-700 mb-2">
                                {!! $note->note !!}
                            </div>
                            <div class="flex justify-between items-center text-xs text-gray-500">
                                <span>{{ $note->created_at->diffForHumans() }}</span>
                                <button wire:click="deleteNote({{ $note->id }})"
                                    wire:confirm="هل أنت متأكد من حذف هذه الملاحظة؟"
                                    class="text-red-500 hover:text-red-700 hover:underline font-medium">
                                    حذف
                                </button>
                            </div>
                        </div>
                        @empty
                        <div class="text-gray-500 italic text-sm text-center py-8 bg-gray-50 rounded-lg">
                            <div class="text-2xl mb-2">📝</div>
                            <div>لا توجد ملاحظات بعد.</div>
                            <div class="text-xs mt-1">ابدأ بكتابة أول ملاحظة لك!</div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- عارض الـ PDF -->
            <div class="col-span-9 flex flex-col items-center bg-white rounded-xl shadow p-5 border relative">
                <canvas id="pdf-canvas" class="rounded-xl border shadow-lg max-w-full"></canvas>

                <!-- معلومات الصفحة -->
                <div class="mt-4 text-gray-600 text-sm">
                    الصفحة <span id="current-page" class="font-bold">0</span> من <span id="total-pages"
                        class="font-bold">0</span>
                </div>

                <!-- أدوات التنقل -->
                <div class="mt-3 space-x-2">
                    <button onclick="prevPage()" class="bg-gray-200 hover:bg-gray-300 text-sm px-4 py-2 rounded">←
                        الصفحة السابقة</button>
                    <button onclick="nextPage()" class="bg-gray-200 hover:bg-gray-300 text-sm px-4 py-2 rounded">الصفحة
                        التالية →</button>
                </div>

                <!-- حالة العلامة وزر الإجراء -->
                <div class="mt-5 text-center">
                    <div id="bookmark-status" class="mb-3 text-sm text-gray-700"></div>
                    <button onclick="toggleBookmark()" id="bookmark-btn"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded transition">
                        📌 وضع علامة على هذه الصفحة
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- إضافة الـ JavaScript للـ PDF -->
    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const pdfUrl = "{{ asset('storage/' . $record->pdf_path) }}";
        const STORAGE_KEY = 'bookmarks_{{ $record->id }}';

        let pdfDoc = null;
        let currentPage = 1;
        let totalPages = 0;

        const canvas = document.getElementById('pdf-canvas');
        const ctx = canvas.getContext('2d');
        const currentPageElem = document.getElementById('current-page');
        const totalPagesElem = document.getElementById('total-pages');
        const bookmarksList = document.getElementById('bookmarks');
        const bookmarkStatus = document.getElementById('bookmark-status');
        const bookmarkBtn = document.getElementById('bookmark-btn');

        async function loadPdf() {
            pdfDoc = await pdfjsLib.getDocument(pdfUrl).promise;
            totalPages = pdfDoc.numPages;
            totalPagesElem.textContent = totalPages;

            const savedBookmarks = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];

            let startPage = 1;
            if (savedBookmarks.length > 0) {
                startPage = savedBookmarks.sort((a, b) => a - b)[0];
            }

            renderPage(startPage);
            loadBookmarks();
        }

        async function renderPage(pageNum) {
            const page = await pdfDoc.getPage(pageNum);
            const viewport = page.getViewport({
                scale: 1.4
            });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            await page.render({
                canvasContext: ctx,
                viewport
            }).promise;

            currentPage = pageNum;
            currentPageElem.textContent = currentPage;

            updateBookmarkStatus();
        }

        function prevPage() {
            if (currentPage <= 1) return;
            renderPage(--currentPage);
        }

        function nextPage() {
            if (currentPage >= totalPages) return;
            renderPage(++currentPage);
        }

        function loadBookmarks() {
            const bookmarks = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
            bookmarksList.innerHTML = '';

            if (bookmarks.length === 0) {
                bookmarksList.innerHTML = '<li class="text-gray-500 italic">لا توجد علامات محفوظة.</li>';
                return;
            }

            bookmarks.sort((a, b) => a - b);
            bookmarks.forEach(page => {
                const li = document.createElement('li');
                li.className =
                    'flex justify-between items-center bg-gray-100 px-3 py-1 rounded hover:bg-gray-200 cursor-pointer';
                li.innerHTML = `
                    <span onclick="renderPage(${page})">📄 صفحة ${page}</span>
                    <button onclick="removeBookmark(${page})" class="text-red-500 hover:underline text-xs">حذف</button>
                `;
                bookmarksList.appendChild(li);
            });
        }

        function toggleBookmark() {
            const bookmarks = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];

            const index = bookmarks.indexOf(currentPage);
            if (index > -1) {
                bookmarks.splice(index, 1);
            } else {
                bookmarks.push(currentPage);
            }

            localStorage.setItem(STORAGE_KEY, JSON.stringify(bookmarks));
            loadBookmarks();
            updateBookmarkStatus();
        }

        function removeBookmark(page) {
            const bookmarks = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
            const index = bookmarks.indexOf(page);
            if (index > -1) {
                bookmarks.splice(index, 1);
                localStorage.setItem(STORAGE_KEY, JSON.stringify(bookmarks));
                loadBookmarks();
                if (page === currentPage) updateBookmarkStatus();
            }
        }

        function updateBookmarkStatus() {
            const bookmarks = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
            const isBookmarked = bookmarks.includes(currentPage);

            if (isBookmarked) {
                bookmarkStatus.innerHTML = `✅ هذه الصفحة موسومة بالفعل كعلامة مرجعية.`;
                bookmarkBtn.textContent = '❌ إزالة العلامة من هذه الصفحة';
                bookmarkBtn.classList.replace('bg-green-600', 'bg-red-600');
                bookmarkBtn.classList.replace('hover:bg-green-700', 'hover:bg-red-700');
            } else {
                bookmarkStatus.innerHTML = `📌 يمكنك وضع علامة على هذه الصفحة للرجوع إليها لاحقاً.`;
                bookmarkBtn.textContent = '📌 وضع علامة على هذه الصفحة';
                bookmarkBtn.classList.replace('bg-red-600', 'bg-green-600');
                bookmarkBtn.classList.replace('hover:bg-red-700', 'hover:bg-green-700');
            }
        }

        // الاستماع لإعادة تحميل الملاحظات
        document.addEventListener('livewire:init', () => {
            Livewire.on('refreshNotes', (event) => {
                // إعادة تحميل الصفحة أو تحديث قائمة الملاحظات
                window.location.reload();
            });
        });

        loadPdf();
    </script>
    @endpush
</x-filament-panels::page>