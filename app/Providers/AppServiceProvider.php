<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

use Filament\Support\Facades\FilamentView;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::body.end',
            fn(): string => Blade::render('
            <style>
/* إخفاء زر التحميل من PDF Viewer - جميع الاحتمالات */

.pdf-viewer-container {
    position: relative !important;
    overflow: hidden !important;
}

/* Overlay لإخفاء منطقة زر التحميل */
.pdf-viewer-container::after {
    content: "" !important;
    position: absolute !important;
    top: 8px !important;
    right: 8px !important;
    width: 60px !important;
    height: 45px !important;
    background: rgba(248, 250, 252, 0.98) !important;
    z-index: 9999 !important;
    pointer-events: none !important;
    border-radius: 6px !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
}

/* إخفاء أزرار التحميل بجميع اللغات والأشكال */
.pdf-viewer-container button[title*="Download"] !important,
.pdf-viewer-container button[title*="download"] !important,
.pdf-viewer-container button[title*="DOWNLOAD"] !important,
.pdf-viewer-container button[title*="تحميل"] !important,
.pdf-viewer-container button[title*="تنزيل"] !important,
.pdf-viewer-container button[title*="حفظ"] !important,
.pdf-viewer-container button[title*="Save"] !important,
.pdf-viewer-container button[title*="save"] !important,
.pdf-viewer-container button[title*="SAVE"] !important,
.pdf-viewer-container button[title*="Print"] !important,
.pdf-viewer-container button[title*="print"] !important,
.pdf-viewer-container button[title*="طباعة"] !important {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
}

/* إخفاء بواسطة aria-label */
.pdf-viewer-container button[aria-label*="Download"] !important,
.pdf-viewer-container button[aria-label*="download"] !important,
.pdf-viewer-container button[aria-label*="تحميل"] !important,
.pdf-viewer-container button[aria-label*="تنزيل"] !important,
.pdf-viewer-container button[aria-label*="Save"] !important,
.pdf-viewer-container button[aria-label*="save"] !important,
.pdf-viewer-container button[aria-label*="Print"] !important,
.pdf-viewer-container button[aria-label*="print"] !important,
.pdf-viewer-container button[aria-label*="طباعة"] !important {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
}

/* إخفاء بواسطة الكلاسات الشائعة */
.pdf-viewer-container .download-button !important,
.pdf-viewer-container .download-btn !important,
.pdf-viewer-container .btn-download !important,
.pdf-viewer-container .save-button !important,
.pdf-viewer-container .save-btn !important,
.pdf-viewer-container .print-button !important,
.pdf-viewer-container .print-btn !important,
.pdf-viewer-container .toolbar-download !important,
.pdf-viewer-container .toolbar-save !important,
.pdf-viewer-container .toolbar-print !important {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
}

/* إخفاء بواسطة data attributes */
.pdf-viewer-container [data-element-id="downloadButton"] !important,
.pdf-viewer-container [data-element-id="saveButton"] !important,
.pdf-viewer-container [data-element-id="printButton"] !important,
.pdf-viewer-container [data-action="download"] !important,
.pdf-viewer-container [data-action="save"] !important,
.pdf-viewer-container [data-action="print"] !important,
.pdf-viewer-container [data-button="download"] !important,
.pdf-viewer-container [data-button="save"] !important,
.pdf-viewer-container [data-button="print"] !important {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
}

/* إخفاء أيقونات التحميل */
.pdf-viewer-container svg[title*="Download"] !important,
.pdf-viewer-container svg[title*="تحميل"] !important,
.pdf-viewer-container .icon-download !important,
.pdf-viewer-container .fa-download !important,
.pdf-viewer-container .fa-save !important,
.pdf-viewer-container .fa-print !important {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
}

/* إخفاء عناصر التحميل في Webkit */
.pdf-viewer-container embed::-webkit-media-controls-download-button !important,
.pdf-viewer-container object::-webkit-media-controls-download-button !important,
.pdf-viewer-container iframe::-webkit-media-controls-download-button !important {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
}

/* إخفاء Context Menu */
.pdf-viewer-container embed !important,
.pdf-viewer-container iframe !important,
.pdf-viewer-container object !important {
    -webkit-touch-callout: none !important;
    -webkit-user-select: none !important;
    -khtml-user-select: none !important;
    -moz-user-select: none !important;
    -ms-user-select: none !important;
    user-select: none !important;
    pointer-events: auto !important;
}

/* إخفاء شريط التحميل في Firefox */
@-moz-document url-prefix() {
    .pdf-viewer-container embed !important {
        -moz-user-select: none !important;
    }
}

/* إخفاء أدوات PDF في Safari */
.pdf-viewer-container embed::-webkit-media-controls !important,
.pdf-viewer-container embed::-webkit-media-controls-enclosure !important {
    display: none !important;
    visibility: hidden !important;
}

/* إخفاء الروابط التي تحتوي على كلمات التحميل */
.pdf-viewer-container a[href*="download"] !important,
.pdf-viewer-container a[href*="save"] !important,
.pdf-viewer-container a[download] !important {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
}

/* إخفاء عناصر بنص التحميل */
.pdf-viewer-container *:contains("Download") !important,
.pdf-viewer-container *:contains("تحميل") !important,
.pdf-viewer-container *:contains("تنزيل") !important,
.pdf-viewer-container *:contains("Save") !important,
.pdf-viewer-container *:contains("حفظ") !important {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
}

/* Overlays إضافية للحماية */
.pdf-viewer-container::before {
    content: "" !important;
    position: absolute !important;
    top: 5px !important;
    right: 5px !important;
    width: 70px !important;
    height: 50px !important;
    background: transparent !important;
    z-index: 9998 !important;
    pointer-events: none !important;
}

/* إخفاء toolbar items بالترتيب */
.pdf-viewer-container .toolbar button:nth-last-child(1) !important,
.pdf-viewer-container .toolbar button:nth-last-child(2) !important,
.pdf-viewer-container .toolbar button:nth-last-child(3) !important {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
}

/* إخفاء عناصر الطباعة أيضاً */
.pdf-viewer-container button[onclick*="print"] !important,
.pdf-viewer-container button[onclick*="window.print"] !important,
.pdf-viewer-container *[onclick*="download"] !important {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
}

/* حماية شاملة من جميع عمليات التحميل */
.pdf-viewer-container {
    -webkit-touch-callout: none !important;
    -webkit-user-select: none !important;
    -khtml-user-select: none !important;
    -moz-user-select: none !important;
    -ms-user-select: none !important;
    user-select: none !important;
}

/* منع النقر الأيمن */
.pdf-viewer-container * {
    -webkit-touch-callout: none !important;
    -webkit-user-select: none !important;
    -khtml-user-select: none !important;
    -moz-user-select: none !important;
    -ms-user-select: none !important;
    user-select: none !important;
}
</style>
        ')
        );
    }
}
