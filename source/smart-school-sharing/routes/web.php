<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Đây là nơi bạn đăng ký các route cho ứng dụng web. Các route này sẽ
| được tải bởi RouteServiceProvider và gắn với nhóm middleware "web".
|
*/

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard (yêu cầu xác thực)
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Trang tĩnh
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Tìm kiếm
Route::get('/search', [HomeController::class, 'search'])->name('items.search');

// Items
Route::prefix('items')->controller(ItemController::class)->group(function () {
    Route::get('/create', 'create')->name('items.create');
    Route::post('/', 'store')->name('items.store');
    Route::get('/category/{id}', 'itemsByCategory')->name('items.byCategory');
    Route::get('/{id}', 'show')->where('id', '[0-9]+')->name('items.show');
});

// Transactions (cần đăng nhập)
Route::middleware(['auth'])->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'storeBorrowRequest'])->name('borrow-requests.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
    Route::post('/transactions/cancel', [TransactionController::class, 'cancel'])
        ->name('transactions.cancel');
});

// Các route xác thực (được Laravel Breeze hoặc Jetstream tạo sẵn)
require __DIR__ . '/auth.php';
