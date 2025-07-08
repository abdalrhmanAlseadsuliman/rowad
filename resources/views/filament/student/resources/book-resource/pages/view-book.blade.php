<div> <!-- العنصر الجذري -->

    <div class="grid grid-cols-12 gap-4">
        {{-- شريط الفهرس --}}
        <div class="col-span-3 bg-gray-100 p-4 rounded shadow overflow-auto max-h-[80vh]">
            <h2 class="text-lg font-bold mb-2">الفهرس</h2>
            <ul id="outline" class="space-y-1 text-sm"></ul>

            {{-- قائمة العلامات --}}
            <h3 class="mt-6 font-semibold">علاماتي</h3>
            <ul id="bookmarks" class="space-y-1 text-sm"></ul>
        </div>

        {{-- عرض صفحات PDF --}}
        <div class="col-span-9 border rounded shadow overflow-auto max-h-[80vh] p-4" id="pdf-container">
            <canvas id="pdf-canvas" class="mx-auto shadow rounded"></canvas>
            <div class="mt-2 text-center">
                <button onclick="prevPage()" class="bg-gray-300 px-3 py-1 rounded mr-2">الصفحة السابقة</button>
                <button onclick="nextPage()" class="bg-gray-300 px-3 py-1 rounded ml-2">الصفحة التالية</button>
            </div>
            <div class="mt-2 text-center">
                الصفحة <span id="current-page">0</span> من <span id="total-pages">0</span>
            </div>

            {{-- زر وضع علامة --}}
            <div class="mt-4 text-center">
                <button onclick="addBookmark()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    📌 وضع علامة على هذه الصفحة
                </button>
            </div>
        </div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const pdfUrl = "{{ asset('storage/' . $record->pdf_path) }}";
    const STORAGE_KEY = 'bookmarks_{{ $record->id }}'; // تخزين العلامات مميز لكل كتاب حسب ID

    let pdfDoc = null;
    let currentPage = 1;
    let totalPages = 0;

    const canvas = document.getElementById('pdf-canvas');
    const ctx = canvas.getContext('2d');
    const currentPageElem = document.getElementById('current-page');
    const totalPagesElem = document.getElementById('total-pages');
    const bookmarksList = document.getElementById('bookmarks');

   async function loadPdf() {
    pdfDoc = await pdfjsLib.getDocument(pdfUrl).promise;
    totalPages = pdfDoc.numPages;
    totalPagesElem.textContent = totalPages;

    // نحاول جلب الصفحة المحفوظة كعلامة (إذا وجدت)
    const savedBookmarks = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
    
    // نحدد الصفحة الافتراضية للفتح
    let startPage = 1;
    if (savedBookmarks.length > 0) {
        // مثلا نفتح الصفحة الأولى المحفوظة كعلامة (يمكن تغيير المنطق حسب الحاجة)
        startPage = savedBookmarks.sort((a,b) => a - b)[0];
    }
    
    renderPage(startPage);
    loadBookmarks();
}


    async function renderPage(pageNum) {
        const page = await pdfDoc.getPage(pageNum);
        const viewport = page.getViewport({ scale: 1.4 });
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        await page.render({ canvasContext: ctx, viewport }).promise;

        currentPage = pageNum;
        currentPageElem.textContent = currentPage;
    }

    function prevPage() {
        if (currentPage <= 1) return;
        currentPage--;
        renderPage(currentPage);
    }

    function nextPage() {
        if (currentPage >= totalPages) return;
        currentPage++;
        renderPage(currentPage);
    }

    // إضافة علامة
    function addBookmark() {
        let bookmarks = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
        if (!bookmarks.includes(currentPage)) {
            bookmarks.push(currentPage);
            localStorage.setItem(STORAGE_KEY, JSON.stringify(bookmarks));
            loadBookmarks();
            alert(`تم وضع علامة على الصفحة ${currentPage}`);
        } else {
            alert(`الصفحة ${currentPage} موسومة مسبقاً`);
        }
    }

    // تحميل وعرض العلامات
    function loadBookmarks() {
        let bookmarks = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
        bookmarksList.innerHTML = '';

        if (bookmarks.length === 0) {
            bookmarksList.innerHTML = '<li class="text-gray-500">لا توجد علامات محفوظة</li>';
            return;
        }

        bookmarks.sort((a,b) => a - b);

        bookmarks.forEach(pageNum => {
            const li = document.createElement('li');
            li.textContent = `الصفحة ${pageNum}`;
            li.classList.add('cursor-pointer', 'text-blue-600', 'hover:underline');
            li.onclick = () => {
                renderPage(pageNum);
            };
            bookmarksList.appendChild(li);
        });
    }

    loadPdf();
</script>
