<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;

Route::get('/', function () {
    return view('welcome');
});


// في routes/web.php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/student/book/read/{book}', function (App\Models\Book $book) {
        // التحقق من صلاحيات الطالب
        if (!auth()->user()->hasRole('student')) {
            abort(403);
        }

        // $filePath = storage_path('app/' . $book->file_path);
        $filePath = public_path('storage/' . $book->pdf_path);

        if (!file_exists($filePath)) {
            abort(404, 'الكتاب غير موجود');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $book->title . '.pdf"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'X-Frame-Options' => 'SAMEORIGIN',
        ]);
    })->name('student.book.read');
});


Route::post('/student/book/progress/{book}', function (App\Models\Book $book) {
    $user = auth()->user();

    RecentlyReadBook::updateOrCreate(
        [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ],
        [
            'last_read_at' => now(),
        ]
    );

    return response()->json(['success' => true]);
})->name('student.book.progress');


Route::get('/subscription/expired', [SubscriptionController::class, 'expired'])
    ->name('subscription.expired')
    ->middleware('auth');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
})->name('logout');
