<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AdminItemController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ItemImageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
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
    Route::get('/category/{id}', 'itemsByCategory')->name('items.byCategory');
    Route::get('/{id}', 'show')->where('id', '[0-9]+')->name('items.show');
});
Route::get('/api/featured-items', [HomeController::class, 'loadFeaturedItems'])->name('api.featured-items');


// Transactions (cần đăng nhập)
Route::middleware(['auth'])->group(function () {
    Route::get('/momo/return', [PaymentController::class, 'handleReturn'])->name('momo.return');
    Route::post('/momo/notify', [PaymentController::class, 'handleNotify'])->name('momo.notify');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    // Khi user bấm nút “Report this user”
    Route::middleware('auth')
        ->post('/users/{reported}/report', [\App\Http\Controllers\ReportController::class, 'store'])
        ->name('users.report');
//    Route::post('items/', 'store')->name('items.store');
    Route::get('items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'storeBorrowRequest'])->name('borrow-requests.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
    Route::post('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
    Route::post('/transactions/cancel', [TransactionController::class, 'cancel'])
        ->name('transactions.cancel');
    Route::delete('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])
        ->name('transactions.cancel');

    // Item routes (separate group)
    Route::prefix('items')->group(function() {
        Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
        Route::put('/{id}', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
        Route::delete('/images/{id}', [ItemImageController::class, 'destroy'])->name('item.images.destroy');
        Route::delete('/item-images/{id}', [ItemController::class, 'destroyImage'])
            ->name('item-images.destroy');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
    });

});

Route::prefix('admin')
    ->middleware(['auth', 'verified', 'can:admin-access'])
    ->name('admin.')
    ->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);

        Route::prefix('accounts')->name('accounts.')->group(function () {
            Route::get('/', [AccountController::class, 'index'])->name('index');
            Route::post('/{user}/toggle', [AccountController::class, 'toggle'])->name('toggle');
            Route::delete('/{user}', [AccountController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('items')->name('items.')->group(function () {
            Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
            Route::get('/', [AdminItemController::class, 'index'])->name('index');
            Route::post('/{item}/approve', [AdminItemController::class, 'approve'])->name('approve');
            Route::delete('/{item}', [AdminItemController::class, 'destroy'])->name('destroy');
            Route::post('/{item}/reject', [AdminItemController::class, 'reject'])->name('admin.items.reject');
            Route::get('/{item}', [AdminItemController::class, 'show'])->name('show');
        });

        // Add these report routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
            Route::get('/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'show'])->name('show');
            Route::post('/{report}/resolve', [\App\Http\Controllers\Admin\ReportController::class, 'resolve'])->name('resolve');
        });
        Route::prefix('contacts')->name('contacts.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('index');
            Route::get('/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('show');
            Route::delete('/{contact}', [\App\Http\Controllers\Admin\ContactController::class, 'destroy'])->name('destroy');
        });
    });
require __DIR__ . '/auth.php';
