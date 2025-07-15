{{-- resources/views/filament/hooks/header-styles.blade.php --}}
<style>
    /* تخصيص ارتفاع الهيدر الرئيسي */
    .flex.h-16.items-center.gap-x-4.bg-white.px-4.shadow-sm.ring-1.ring-gray-950\/5.dark\:bg-gray-900.dark\:ring-white\/10.md\:px-6.lg\:px-8 {
        height: 110px !important;
        min-height: 110px !important;
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
    }

    /* تخصيص ارتفاع هيدر الشريط الجانبي */
    .fi-sidebar-header.flex.h-16.items-center.bg-white.px-6.ring-1.ring-gray-950\/5.dark\:bg-gray-900.dark\:ring-white\/10.lg\:shadow-sm {
        height: 110px !important;
        min-height: 110px !important;
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
    }

    /* تخصيص أبسط وأكثر شمولية */
    .fi-sidebar-header,
    .h-16.items-center.gap-x-4 {
        height: 110px !important;
        min-height: 110px !important;
    }

    /* إزالة h-16 وإضافة الارتفاع المخصص */
    .fi-sidebar-header.h-16,
    .h-16.items-center {
        height: 110px !important;
    }
</style>