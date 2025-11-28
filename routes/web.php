<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicBookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReaderController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AdvancedSearchController;
use App\Http\Controllers\AdvancedStatisticsController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\BorrowItemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// CSRF Token routes
Route::get('/csrf-token', [App\Http\Controllers\CsrfController::class, 'getToken'])->name('csrf.token');
Route::post('/csrf-refresh', [App\Http\Controllers\CsrfController::class, 'refreshToken'])->name('csrf.refresh');

// Frontend Routes
Route::get('/', [HomeController::class, 'trangchu'])->name('home');
Route::get('/home', function() { return redirect()->route('home'); });
Route::get('/trangchu', [HomeController::class, 'trangchu'])->name('trangchu');
// Route hiển thị danh sách sách cho người dùng frontend
Route::get('/books', [PublicBookController::class, 'index'])->name('books.public');
Route::get('/books/{id}', [PublicBookController::class, 'show'])->name('books.show');
Route::get('/diem-sach/{id}', [PublicBookController::class, 'showDiemSach'])->name('diem-sach.show');
Route::get('/tin-tuc/{id}', [PublicBookController::class, 'showTinTuc'])->name('tin-tuc.show');
Route::post('/borrow-book', [HomeController::class, 'borrowBook'])->name('borrow.book')->middleware('auth');

// Public Categories Route
Route::get('/categories', [CategoryController::class, 'publicIndex'])->name('categories.index');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add')->middleware('auth');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Public Comments Routes (for book detail pages)
Route::post('/books/{id}/comments', [CommentController::class, 'storePublic'])->name('books.comments.store')->middleware('auth');

// Order Routes
Route::get('/checkout', [App\Http\Controllers\OrderController::class, 'checkout'])->name('checkout')->middleware('auth');
// Đặt GET routes trước POST để tránh conflict
Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
Route::post('/orders/{id}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('orders.cancel')->middleware('auth');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // 5 attempts per minute
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1'); // 3 attempts per minute
    
    // Password Reset Routes
    Route::get('/password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [AuthController::class, 'reset'])->name('password.update');
    
    // Email Verification Routes
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])->middleware('throttle:6,1')->name('verification.send');
    Route::get('/register-reader', [App\Http\Controllers\ReaderRegistrationController::class, 'showRegistrationForm'])->name('register.reader.form');
    Route::post('/register-reader', [App\Http\Controllers\ReaderRegistrationController::class, 'register'])->name('register.reader');
    
    // Google OAuth Routes
    Route::get('/auth/google', [App\Http\Controllers\GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [App\Http\Controllers\GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Dashboard Route (for authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Redirect based on user role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isLibrarian()) {
            return redirect()->route('staff.dashboard');
        }
        
        // For regular users, redirect to home or books page
        return redirect()->route('home');
    })->name('dashboard');
    
    // Account/Profile Routes
    Route::get('/account', [App\Http\Controllers\UserAccountController::class, 'account'])->name('account');
    Route::put('/account', [App\Http\Controllers\UserAccountController::class, 'updateAccount'])->name('account.update');
    Route::get('/account/purchased-books', [App\Http\Controllers\UserAccountController::class, 'purchasedBooks'])->name('account.purchased-books');
    Route::get('/account/borrowed-books', [App\Http\Controllers\UserAccountController::class, 'borrowedBooks'])->name('account.borrowed-books');
    Route::get('/account/reading-books', [App\Http\Controllers\UserAccountController::class, 'readingBooks'])->name('account.reading-books');
    Route::get('/account/purchased-documents', [App\Http\Controllers\UserAccountController::class, 'purchasedDocuments'])->name('account.purchased-documents');
    Route::get('/account/change-password', [App\Http\Controllers\UserAccountController::class, 'showChangePassword'])->name('account.change-password');
    Route::put('/account/change-password', [App\Http\Controllers\UserAccountController::class, 'updatePassword'])->name('account.update-password');
    Route::get('/account/reader-info', [App\Http\Controllers\UserAccountController::class, 'readerInfo'])->name('account.reader-info');
    
    // Reader Registration Routes (for authenticated users)
    Route::get('/account/register-reader', [App\Http\Controllers\ReaderRegistrationController::class, 'showRegistrationFormForUser'])->name('account.register-reader');
    Route::post('/account/register-reader', [App\Http\Controllers\ReaderRegistrationController::class, 'registerForUser'])->name('account.register-reader.store');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Resource routes
      Route::resource('categories', CategoryController::class)->middleware('permission:view-categories');
    Route::get('categories-export', [CategoryController::class, 'export'])->name('categories.export')->middleware('permission:view-categories');
    Route::get('categories-print', [CategoryController::class, 'print'])->name('categories.print')->middleware('permission:view-categories');
    Route::get('categories-statistics', [CategoryController::class, 'statistics'])->name('categories.statistics')->middleware('permission:view-categories');
    Route::post('categories-bulk-action', [CategoryController::class, 'bulkAction'])->name('categories.bulk-action')->middleware('permission:edit-categories');
    Route::post('categories/{id}/move-books', [CategoryController::class, 'moveBooks'])->name('categories.move-books')->middleware('permission:edit-categories');
        Route::resource('authors', App\Http\Controllers\Admin\AuthorController::class)->middleware('permission:view-readers');
      // Vô hiệu hóa create và store - sách mới chỉ được tạo từ quản lý kho
      Route::resource('books', BookController::class)->except(['create', 'store'])->middleware('permission:view-books');
      Route::post('books/{id}/hide', [BookController::class, 'hide'])->name('books.hide')->middleware('permission:edit-books');
      Route::post('books/{id}/unhide', [BookController::class, 'unhide'])->name('books.unhide')->middleware('permission:edit-books');
      Route::post('books/delete-without-inventory', [BookController::class, 'deleteBooksWithoutInventory'])->name('books.delete-without-inventory')->middleware('permission:edit-books');
      Route::post('books/reset-ids', [BookController::class, 'resetIds'])->name('books.reset-ids')->middleware('permission:edit-books');
      Route::resource('purchasable-books', App\Http\Controllers\Admin\PurchasableBookController::class)->middleware('permission:view-books');
      Route::get('books-unified', [App\Http\Controllers\Admin\UnifiedBookController::class, 'index'])->name('books.unified')->middleware('permission:view-books');
      Route::resource('readers', ReaderController::class)->middleware('permission:view-readers');
      Route::get('readers/{id}/renew-card', [ReaderController::class, 'renewCard'])->name('readers.renew-card')->middleware('permission:edit-readers');
      Route::post('readers/{id}/suspend', [ReaderController::class, 'suspend'])->name('readers.suspend')->middleware('permission:edit-readers');
      Route::post('readers/{id}/activate', [ReaderController::class, 'activate'])->name('readers.activate')->middleware('permission:edit-readers');
      Route::get('readers-export', [ReaderController::class, 'export'])->name('readers.export')->middleware('permission:view-readers');
      Route::get('readers-print', [ReaderController::class, 'print'])->name('readers.print')->middleware('permission:view-readers');
      Route::get('readers-statistics', [ReaderController::class, 'statistics'])->name('readers.statistics')->middleware('permission:view-readers');
      Route::post('readers-bulk-action', [ReaderController::class, 'bulkAction'])->name('readers.bulk-action')->middleware('permission:edit-readers');
   
     Route::resource('borrows', BorrowController::class)->middleware('permission:view-borrows');
      Route::post('borrows/{id}/return', [BorrowController::class, 'return'])->name('borrows.return')->middleware('permission:return-books');
      Route::post('borrows/{id}/extend', [BorrowController::class, 'extend'])->name('borrows.extend')->middleware('permission:edit-borrows');
      Route::get('borrows-dashboard', [App\Http\Controllers\BorrowDashboardController::class, 'index'])->name('borrows.dashboard')->middleware('permission:view-borrows');
      Route::get('borrows-dashboard/export', [App\Http\Controllers\BorrowDashboardController::class, 'export'])->name('borrows.dashboard.export')->middleware('permission:view-reports');
      route::get('borrows/{id}/create-item', [BorrowController::class, 'createItem'])->name('borrows.createitem')->middleware('permission:create-borrows');
      Route::post('borrows/{id}/store-item', [BorrowController::class, 'storeItem'])->name('borrows.storeItem')->middleware('permission:create-borrows');
      Route::get('borrow-items/{id}/edit', [BorrowItemController::class, 'edit'])->name('borrowitems.edit'); // <--- tên route
      Route::put('borrow-items/{id}', [BorrowItemController::class, 'update'])->name('borrowitems.update');
      Route::get('borrow-items/{id}', [BorrowItemController::class, 'show'])->name('borrowitems.show');
      Route::get('borrow-items', [BorrowItemController::class, 'returnedItems'])->name('borrowitems.returned');

Route::post('borrow-items/{id}/return', [BorrowController::class, 'returnItem'])->name('borrowitems.return');
///////////////////////////////////////////////////////////////////////
Route::post('/borrowitems/{id}/approve', [BorrowItemController::class, 'approve'])
    ->name('borrowitems.approve');
Route::post('/borrowitems/{id}/change-status', 
    [BorrowItemController::class, 'changeStatus']
)->name('borrowitems.changeStatus');
Route::post('/borrowitems/{id}/lost', [BorrowItemController::class, 'markLost'])
    ->name('borrowitems.lost');
Route::post('/inventory/{id}/return', [InventoryController::class, 'returnToStock'])
     ->name('inventories.return');
Route::post('/borrowitems/{id}/report-damage', 
    [BorrowItemController::class, 'reportDamage']
)->name('borrowitems.report-damage');



/// ships
Route::resource('vouchers', VoucherController::class);

      Route::get('vouchers/{id}/restore', [VoucherController::class, 'restore'])->name('admin.vouchers.restore');
      Route::delete('vouchers/{id}/force-delete', [VoucherController::class, 'forceDelete'])->name('admin.vouchers.forceDelete');

      
      // Reports routes
      Route::get('reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:view-reports');
      Route::get('reports/borrows', [ReportController::class, 'borrowsReport'])->name('reports.borrows')->middleware('permission:view-reports');
      Route::get('reports/readers', [ReportController::class, 'readersReport'])->name('reports.readers')->middleware('permission:view-reports');
      Route::get('reports/books', [ReportController::class, 'booksReport'])->name('reports.books')->middleware('permission:view-reports');
      
      // Inventory Reports routes (Báo cáo kho sách)
      Route::prefix('inventory-reports')->name('inventory-reports.')->group(function() {
          Route::get('/', [App\Http\Controllers\InventoryReportController::class, 'index'])->name('index')->middleware('permission:view-reports');
          Route::get('book-statistics', [App\Http\Controllers\InventoryReportController::class, 'bookStatistics'])->name('book-statistics')->middleware('permission:view-reports');
          Route::get('borrow-return', [App\Http\Controllers\InventoryReportController::class, 'borrowReturnReport'])->name('borrow-return')->middleware('permission:view-reports');
          Route::get('import', [App\Http\Controllers\InventoryReportController::class, 'importReport'])->name('import')->middleware('permission:view-reports');
          Route::get('disposal', [App\Http\Controllers\InventoryReportController::class, 'disposalReport'])->name('disposal')->middleware('permission:view-reports');
          Route::get('fine', [App\Http\Controllers\InventoryReportController::class, 'fineReport'])->name('fine')->middleware('permission:view-reports');
          Route::get('late-return', [App\Http\Controllers\InventoryReportController::class, 'lateReturnReport'])->name('late-return')->middleware('permission:view-reports');
      });
      
      // Reviews routes
      Route::resource('reviews', ReviewController::class)->middleware('permission:view-reviews');
      Route::post('comments', [CommentController::class, 'store'])->name('comments.store')->middleware('permission:create-reviews');
      Route::put('comments/{id}', [CommentController::class, 'update'])->name('comments.update')->middleware('permission:edit-reviews');
      Route::delete('comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy')->middleware('permission:delete-reviews');
      Route::post('comments/{id}/like', [CommentController::class, 'like'])->name('comments.like')->middleware('permission:view-reviews');
      Route::post('comments/{id}/approve', [CommentController::class, 'approve'])->name('comments.approve')->middleware('permission:approve-reviews');
      Route::post('comments/{id}/reject', [CommentController::class, 'reject'])->name('comments.reject')->middleware('permission:approve-reviews');
      
      // Fines routes
      Route::resource('fines', FineController::class)->middleware('permission:view-fines');
      Route::post('fines/{id}/mark-paid', [FineController::class, 'markAsPaid'])->name('fines.mark-paid')->middleware('permission:edit-fines');
      Route::post('fines/{id}/waive', [FineController::class, 'waive'])->name('fines.waive')->middleware('permission:waive-fines');
      Route::post('fines/create-late-returns', [FineController::class, 'createLateReturnFines'])->name('fines.create-late-returns')->middleware('permission:create-fines');
      Route::get('fines-report', [FineController::class, 'report'])->name('fines.report')->middleware('permission:view-reports');
      
      // Reservations routes
      Route::resource('reservations', ReservationController::class)->middleware('permission:view-reservations');
      Route::post('reservations/{id}/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm')->middleware('permission:confirm-reservations');
      Route::post('reservations/{id}/mark-ready', [ReservationController::class, 'markReady'])->name('reservations.mark-ready')->middleware('permission:confirm-reservations');
      Route::post('reservations/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel')->middleware('permission:edit-reservations');
      Route::get('reservations/export', [ReservationController::class, 'export'])->name('reservations.export')->middleware('permission:view-reservations');
      
      // Orders routes (Admin)
      Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
      Route::get('order-edit/{id}', [App\Http\Controllers\Admin\OrderController::class, 'edit'])->name('orders.edit');
      Route::get('orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
      Route::put('orders/{id}', [App\Http\Controllers\Admin\OrderController::class, 'update'])->name('orders.update');
      
      // Advanced Reports routes
      Route::get('advanced-reports', [App\Http\Controllers\Admin\AdvancedReportController::class, 'index'])->name('advanced-reports.index')->middleware('permission:view-reports');
      Route::get('advanced-reports/dashboard-stats', [App\Http\Controllers\Admin\AdvancedReportController::class, 'dashboardStats'])->name('advanced-reports.dashboard-stats')->middleware('permission:view-reports');
      Route::get('advanced-reports/borrowing-trends', [App\Http\Controllers\Admin\AdvancedReportController::class, 'borrowingTrends'])->name('advanced-reports.borrowing-trends')->middleware('permission:view-reports');
      Route::get('advanced-reports/borrowing-trends-chart', [App\Http\Controllers\Admin\AdvancedReportController::class, 'borrowingTrendsChart'])->name('advanced-reports.borrowing-trends-chart')->middleware('permission:view-reports');
      Route::get('advanced-reports/popular-books', [App\Http\Controllers\Admin\AdvancedReportController::class, 'popularBooks'])->name('advanced-reports.popular-books')->middleware('permission:view-reports');
      Route::get('advanced-reports/active-readers', [App\Http\Controllers\Admin\AdvancedReportController::class, 'activeReaders'])->name('advanced-reports.active-readers')->middleware('permission:view-reports');
      Route::get('advanced-reports/overdue-books', [App\Http\Controllers\Admin\AdvancedReportController::class, 'overdueBooks'])->name('advanced-reports.overdue-books')->middleware('permission:view-reports');
      Route::get('advanced-reports/fine-statistics', [App\Http\Controllers\Admin\AdvancedReportController::class, 'fineStatistics'])->name('advanced-reports.fine-statistics')->middleware('permission:view-reports');
      Route::get('advanced-reports/fine-trends-chart', [App\Http\Controllers\Admin\AdvancedReportController::class, 'fineTrendsChart'])->name('advanced-reports.fine-trends-chart')->middleware('permission:view-reports');
      Route::get('advanced-reports/category-performance', [App\Http\Controllers\Admin\AdvancedReportController::class, 'categoryPerformance'])->name('advanced-reports.category-performance')->middleware('permission:view-reports');
      Route::get('advanced-reports/monthly-report', [App\Http\Controllers\Admin\AdvancedReportController::class, 'monthlyReport'])->name('advanced-reports.monthly-report')->middleware('permission:view-reports');
      Route::get('advanced-reports/yearly-report', [App\Http\Controllers\Admin\AdvancedReportController::class, 'yearlyReport'])->name('advanced-reports.yearly-report')->middleware('permission:view-reports');
      Route::get('advanced-reports/books-by-category', [App\Http\Controllers\Admin\AdvancedReportController::class, 'booksByCategory'])->name('advanced-reports.books-by-category')->middleware('permission:view-reports');
      Route::get('advanced-reports/real-time-stats', [App\Http\Controllers\Admin\AdvancedReportController::class, 'realTimeStats'])->name('advanced-reports.real-time-stats')->middleware('permission:view-reports');
      Route::post('advanced-reports/export', [App\Http\Controllers\Admin\AdvancedReportController::class, 'export'])->name('advanced-reports.export')->middleware('permission:view-reports');
      
      // Bulk Operations routes
      Route::get('bulk-operations', [App\Http\Controllers\Admin\BulkOperationController::class, 'index'])->name('bulk-operations.index')->middleware('permission:manage-bulk-operations');
      Route::post('bulk-operations/books/update', [App\Http\Controllers\Admin\BulkOperationController::class, 'bulkUpdateBooks'])->name('bulk-operations.books.update')->middleware('permission:manage-bulk-operations');
      Route::delete('bulk-operations/books/delete', [App\Http\Controllers\Admin\BulkOperationController::class, 'bulkDeleteBooks'])->name('bulk-operations.books.delete')->middleware('permission:manage-bulk-operations');
      Route::post('bulk-operations/readers/update', [App\Http\Controllers\Admin\BulkOperationController::class, 'bulkUpdateReaders'])->name('bulk-operations.readers.update')->middleware('permission:manage-bulk-operations');
      Route::post('bulk-operations/borrows/extend', [App\Http\Controllers\Admin\BulkOperationController::class, 'bulkExtendBorrows'])->name('bulk-operations.borrows.extend')->middleware('permission:manage-bulk-operations');
      Route::post('bulk-operations/borrows/return', [App\Http\Controllers\Admin\BulkOperationController::class, 'bulkReturnBooks'])->name('bulk-operations.borrows.return')->middleware('permission:manage-bulk-operations');
      Route::delete('bulk-operations/reservations/cancel', [App\Http\Controllers\Admin\BulkOperationController::class, 'bulkCancelReservations'])->name('bulk-operations.reservations.cancel')->middleware('permission:manage-bulk-operations');
      Route::post('bulk-operations/fines/create', [App\Http\Controllers\Admin\BulkOperationController::class, 'bulkCreateFines'])->name('bulk-operations.fines.create')->middleware('permission:manage-bulk-operations');
      Route::post('bulk-operations/categories/update', [App\Http\Controllers\Admin\BulkOperationController::class, 'bulkUpdateCategories'])->name('bulk-operations.categories.update')->middleware('permission:manage-bulk-operations');
      Route::post('bulk-operations/books/import', [App\Http\Controllers\Admin\BulkOperationController::class, 'bulkImportBooks'])->name('bulk-operations.books.import')->middleware('permission:manage-bulk-operations');
      Route::get('bulk-operations/books/export', [App\Http\Controllers\Admin\BulkOperationController::class, 'exportBooks'])->name('bulk-operations.books.export')->middleware('permission:manage-bulk-operations');
      Route::get('bulk-operations/stats', [App\Http\Controllers\Admin\BulkOperationController::class, 'getStats'])->name('bulk-operations.stats')->middleware('permission:manage-bulk-operations');
      
      // Advanced Statistics routes
      Route::get('statistics/advanced', [AdvancedStatisticsController::class, 'dashboard'])->name('statistics.advanced.dashboard')->middleware('permission:view-reports');
      Route::get('statistics/advanced/overview', [AdvancedStatisticsController::class, 'overview'])->name('statistics.advanced.overview')->middleware('permission:view-reports');
      Route::get('statistics/advanced/trends', [AdvancedStatisticsController::class, 'trends'])->name('statistics.advanced.trends')->middleware('permission:view-reports');
      Route::get('statistics/advanced/category-stats', [AdvancedStatisticsController::class, 'categoryStats'])->name('statistics.advanced.category-stats')->middleware('permission:view-reports');
      Route::get('statistics/advanced/faculty-stats', [AdvancedStatisticsController::class, 'facultyStats'])->name('statistics.advanced.faculty-stats')->middleware('permission:view-reports');
      Route::get('statistics/advanced/search-stats', [AdvancedStatisticsController::class, 'searchStats'])->name('statistics.advanced.search-stats')->middleware('permission:view-reports');
      Route::get('statistics/advanced/notification-stats', [AdvancedStatisticsController::class, 'notificationStats'])->name('statistics.advanced.notification-stats')->middleware('permission:view-reports');
      Route::get('statistics/advanced/inventory-stats', [AdvancedStatisticsController::class, 'inventoryStats'])->name('statistics.advanced.inventory-stats')->middleware('permission:view-reports');
      
      // Advanced Search routes
      Route::get('advanced-search', [AdvancedSearchController::class, 'index'])->name('advanced-search.index')->middleware('permission:view-books');
      Route::get('search/books', [AdvancedSearchController::class, 'searchBooks'])->name('search.books')->middleware('permission:view-books');
      Route::get('search/readers', [AdvancedSearchController::class, 'searchReaders'])->name('search.readers')->middleware('permission:view-readers');
      Route::get('search/borrows', [AdvancedSearchController::class, 'searchBorrows'])->name('search.borrows')->middleware('permission:view-borrows');
      Route::get('search/global', [AdvancedSearchController::class, 'globalSearch'])->name('search.global')->middleware('permission:view-books');
      Route::get('search/suggestions', [AdvancedSearchController::class, 'getSuggestions'])->name('search.suggestions')->middleware('permission:view-books');
      
      // Autocomplete routes for borrow form
      Route::get('autocomplete/readers', [AdvancedSearchController::class, 'autocompleteReaders'])->name('autocomplete.readers')->middleware('permission:view-readers');
      Route::get('autocomplete/books', [AdvancedSearchController::class, 'autocompleteBooks'])->name('autocomplete.books')->middleware('permission:view-books');
      Route::get('books/{book}/inventories', [AdvancedSearchController::class, 'getBookInventories'])
    ->name('books.inventories')
    ->middleware('permission:view-books'); // hoặc middleware phù hợp

      // Notification routes
      Route::get('notifications', [App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index')->middleware('permission:view-borrows');
      Route::post('notifications/send-reminders', [App\Http\Controllers\Admin\NotificationController::class, 'sendReminders'])->name('notifications.send-reminders')->middleware('permission:edit-borrows');
      Route::post('notifications/send-custom', [App\Http\Controllers\Admin\NotificationController::class, 'sendCustomReminder'])->name('notifications.send-custom')->middleware('permission:edit-borrows');
      
    // Email Marketing Routes (temporary without permission for testing)
    Route::resource('email-marketing', App\Http\Controllers\Admin\EmailMarketingController::class);
    Route::post('email-marketing/{id}/send', [App\Http\Controllers\Admin\EmailMarketingController::class, 'sendNow'])->name('email-marketing.send');
    Route::post('email-marketing/{id}/schedule', [App\Http\Controllers\Admin\EmailMarketingController::class, 'schedule'])->name('email-marketing.schedule');
    Route::post('email-marketing/{id}/cancel', [App\Http\Controllers\Admin\EmailMarketingController::class, 'cancel'])->name('email-marketing.cancel');
    Route::get('email-marketing/subscribers', [App\Http\Controllers\Admin\EmailMarketingController::class, 'subscribers'])->name('email-marketing.subscribers');
    Route::post('email-marketing/subscribers/add', [App\Http\Controllers\Admin\EmailMarketingController::class, 'addSubscriber'])->name('email-marketing.subscribers.add');
    Route::post('email-marketing/subscribers/{id}/unsubscribe', [App\Http\Controllers\Admin\EmailMarketingController::class, 'unsubscribeSubscriber'])->name('email-marketing.subscribers.unsubscribe');
      
      // Inventory routes
      Route::resource('inventory', InventoryController::class)->except(['destroy'])->middleware('permission:view-books');
      Route::delete('inventory/{id}', [InventoryController::class, 'destroy'])->name('inventory.destroy')->middleware('permission:edit-books');
      Route::get('inventory/book/{book_id}/show-all', [InventoryController::class, 'showByBook'])->name('inventory.show-by-book')->middleware('permission:view-books');
      Route::delete('inventory/book/{book_id}/delete-all', [InventoryController::class, 'destroyByBook'])->name('inventory.destroy-by-book')->middleware('permission:edit-books');
      Route::post('inventory/{id}/transfer', [InventoryController::class, 'transfer'])->name('inventory.transfer')->middleware('permission:edit-books');
      Route::post('inventory/{id}/repair', [InventoryController::class, 'repair'])->name('inventory.repair')->middleware('permission:edit-books');
      Route::get('inventory-transactions', [InventoryController::class, 'transactions'])->name('inventory.transactions')->middleware('permission:view-books');
      Route::get('inventory-dashboard', [InventoryController::class, 'dashboard'])->name('inventory.dashboard')->middleware('permission:view-books');
      Route::post('inventory/scan-barcode', [InventoryController::class, 'scanBarcode'])->name('inventory.scan-barcode')->middleware('permission:view-books');
      Route::post('inventory/sync-to-homepage', [InventoryController::class, 'syncToHomepage'])->name('inventory.sync-to-homepage')->middleware('permission:edit-books');
      
      // Inventory Receipts (Phiếu nhập kho)
      Route::get('inventory-receipts', [InventoryController::class, 'receipts'])->name('inventory.receipts')->middleware('permission:view-books');
      Route::get('inventory-receipts/create', [InventoryController::class, 'createReceipt'])->name('inventory.receipts.create')->middleware('permission:edit-books');
      Route::post('inventory-receipts', [InventoryController::class, 'storeReceipt'])->name('inventory.receipts.store')->middleware('permission:edit-books');
      Route::get('inventory-receipts/{id}', [InventoryController::class, 'showReceipt'])->name('inventory.receipts.show')->middleware('permission:view-books');
      Route::post('inventory-receipts/{id}/approve', [InventoryController::class, 'approveReceipt'])->name('inventory.receipts.approve')->middleware('permission:edit-books');
      Route::post('inventory-receipts/{id}/reject', [InventoryController::class, 'rejectReceipt'])->name('inventory.receipts.reject')->middleware('permission:edit-books');
      
      // Display Allocations (Phân bổ trưng bày)
      Route::get('inventory-display-allocations', [InventoryController::class, 'displayAllocations'])->name('inventory.display-allocations')->middleware('permission:view-books');
      Route::get('inventory-display-allocations/create', [InventoryController::class, 'createDisplayAllocation'])->name('inventory.display-allocations.create')->middleware('permission:edit-books');
      Route::post('inventory-display-allocations', [InventoryController::class, 'storeDisplayAllocation'])->name('inventory.display-allocations.store')->middleware('permission:edit-books');
      Route::post('inventory-display-allocations/{id}/return', [InventoryController::class, 'returnFromDisplay'])->name('inventory.display-allocations.return')->middleware('permission:edit-books');
      
      // Inventory Report
      Route::get('inventory-report', [InventoryController::class, 'report'])->name('inventory.report')->middleware('permission:view-books');
      Route::post('inventory-report/sync', [InventoryController::class, 'syncInventoryBorrowItems'])->name('inventory.report.sync')->middleware('permission:view-books');
      Route::post('inventory/sync-data', [InventoryController::class, 'syncData'])->name('inventory.sync-data')->middleware('permission:edit-books');
      
      // Inventory Export/Import
      Route::get('inventory/export', [InventoryController::class, 'export'])->name('inventory.export')->middleware('permission:view-books');
      Route::post('inventory/import', [InventoryController::class, 'import'])->name('inventory.import')->middleware('permission:edit-books');
      Route::post('inventory/import-all-books', [InventoryController::class, 'importAllBooks'])->name('inventory.import-all-books')->middleware('permission:edit-books');
      
      // Admin Management routes
      Route::resource('publishers', App\Http\Controllers\Admin\PublisherController::class)->middleware('permission:view-books');
      Route::post('publishers/{id}/toggle-status', [App\Http\Controllers\Admin\PublisherController::class, 'toggleStatus'])->name('publishers.toggle-status')->middleware('permission:edit-books');
      Route::post('publishers-bulk-action', [App\Http\Controllers\Admin\PublisherController::class, 'bulkAction'])->name('publishers.bulk-action')->middleware('permission:edit-books');
      
      Route::resource('departments', App\Http\Controllers\Admin\DepartmentController::class)->middleware('permission:view-readers');
      Route::post('departments/{id}/toggle-status', [App\Http\Controllers\Admin\DepartmentController::class, 'toggleStatus'])->name('departments.toggle-status')->middleware('permission:edit-readers');
      Route::post('departments-bulk-action', [App\Http\Controllers\Admin\DepartmentController::class, 'bulkAction'])->name('departments.bulk-action')->middleware('permission:edit-readers');
      
      Route::resource('faculties', App\Http\Controllers\Admin\FacultyController::class)->middleware('permission:view-readers');
      Route::post('faculties/{id}/toggle-status', [App\Http\Controllers\Admin\FacultyController::class, 'toggleStatus'])->name('faculties.toggle-status')->middleware('permission:edit-readers');
      Route::post('faculties-bulk-action', [App\Http\Controllers\Admin\FacultyController::class, 'bulkAction'])->name('faculties.bulk-action')->middleware('permission:edit-readers');
      
      // User Management routes
      Route::get('user-management', [App\Http\Controllers\Admin\UserManagementController::class, 'dashboard'])->name('user-management.dashboard')->middleware('permission:view-users');
      Route::resource('users', App\Http\Controllers\Admin\UserController::class)->middleware('permission:view-users');
      Route::post('users-bulk-action', [App\Http\Controllers\Admin\UserController::class, 'bulkAction'])->name('users.bulk-action')->middleware('permission:edit-users');
      Route::get('users-export', [App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export')->middleware('permission:view-users');
      
      // Librarian Management routes
      Route::resource('librarians', App\Http\Controllers\Admin\LibrarianController::class)->middleware('permission:view-users');
      Route::post('librarians/{id}/toggle-status', [App\Http\Controllers\Admin\LibrarianController::class, 'toggleStatus'])->name('librarians.toggle-status')->middleware('permission:edit-users');
      Route::post('librarians/{id}/renew-contract', [App\Http\Controllers\Admin\LibrarianController::class, 'renewContract'])->name('librarians.renew-contract')->middleware('permission:edit-users');
      Route::post('librarians-bulk-action', [App\Http\Controllers\Admin\LibrarianController::class, 'bulkAction'])->name('librarians.bulk-action')->middleware('permission:edit-users');
      Route::get('librarians-export', [App\Http\Controllers\Admin\LibrarianController::class, 'export'])->name('librarians.export')->middleware('permission:view-users');
      
      // Backup Management routes
      Route::get('backups', [App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backups.index')->middleware('permission:manage-backup');
      Route::post('backups/create', [App\Http\Controllers\Admin\BackupController::class, 'create'])->name('backups.create')->middleware('permission:manage-backup');
      Route::post('backups/restore', [App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('backups.restore')->middleware('permission:manage-backup');
      Route::get('backups/download/{filename}', [App\Http\Controllers\Admin\BackupController::class, 'download'])->name('backups.download')->middleware('permission:manage-backup');
      Route::delete('backups/delete', [App\Http\Controllers\Admin\BackupController::class, 'delete'])->name('backups.delete')->middleware('permission:manage-backup');
      Route::post('backups/validate', [App\Http\Controllers\Admin\BackupController::class, 'validateBackup'])->name('backups.validate')->middleware('permission:manage-backup');
      Route::get('backups/statistics', [App\Http\Controllers\Admin\BackupController::class, 'statistics'])->name('backups.statistics')->middleware('permission:manage-backup');
      Route::post('backups/schedule', [App\Http\Controllers\Admin\BackupController::class, 'schedule'])->name('backups.schedule')->middleware('permission:manage-backup');
      Route::get('backups/list', [App\Http\Controllers\Admin\BackupController::class, 'list'])->name('backups.list')->middleware('permission:manage-backup');
      
      // System Settings routes
      Route::get('settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index')->middleware('permission:view-settings');
      Route::put('settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update')->middleware('permission:edit-settings');
      Route::post('settings/cache/clear', [App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('settings.cache.clear')->middleware('permission:manage-settings');
      Route::post('settings/database/optimize', [App\Http\Controllers\Admin\SettingsController::class, 'optimizeDatabase'])->name('settings.database.optimize')->middleware('permission:manage-settings');
      
      // Test route without permission middleware
      Route::get('settings/backup/test', [App\Http\Controllers\Admin\SettingsController::class, 'listBackups'])->name('settings.backup.test');
      
      // Banner Management routes
      Route::get('banners', [App\Http\Controllers\Admin\BannerController::class, 'index'])->name('banners.index');
      Route::post('banners/{bannerNumber}/upload', [App\Http\Controllers\Admin\BannerController::class, 'upload'])->name('banners.upload');
      Route::delete('banners/{bannerNumber}/delete', [App\Http\Controllers\Admin\BannerController::class, 'delete'])->name('banners.delete');
      
      // Audit Logs routes
      Route::get('audit-logs', [App\Http\Controllers\Admin\AuditController::class, 'index'])->name('audit-logs.index')->middleware('permission:view-logs');
      Route::get('audit-logs/{auditLog}', [App\Http\Controllers\Admin\AuditController::class, 'show'])->name('audit-logs.show')->middleware('permission:view-logs');
      Route::get('audit-logs-export', [App\Http\Controllers\Admin\AuditController::class, 'export'])->name('audit-logs.export')->middleware('permission:view-logs');
      Route::get('audit-logs-statistics', [App\Http\Controllers\Admin\AuditController::class, 'statistics'])->name('audit-logs.statistics')->middleware('permission:view-logs');
      Route::post('audit-logs/clear-old', [App\Http\Controllers\Admin\AuditController::class, 'clearOld'])->name('audit-logs.clear-old')->middleware('permission:manage-logs');
      Route::get('audit-logs/realtime', [App\Http\Controllers\Admin\AuditController::class, 'realTime'])->name('audit-logs.realtime')->middleware('permission:view-logs');
      
      // Logs & Audit Trail routes
      Route::get('logs', [App\Http\Controllers\Admin\LogController::class, 'index'])->name('logs.index')->middleware('permission:view-logs');
      Route::get('logs/{id}', [App\Http\Controllers\Admin\LogController::class, 'show'])->name('logs.show')->middleware('permission:view-logs');
      Route::get('logs-export', [App\Http\Controllers\Admin\LogController::class, 'export'])->name('logs.export')->middleware('permission:view-logs');
      Route::get('logs/{id}/export', [App\Http\Controllers\Admin\LogController::class, 'exportSingle'])->name('logs.export-single')->middleware('permission:view-logs');
      Route::post('logs/clear-old', [App\Http\Controllers\Admin\LogController::class, 'clearOld'])->name('logs.clear-old')->middleware('permission:manage-logs');
      Route::get('logs/realtime', [App\Http\Controllers\Admin\LogController::class, 'realTime'])->name('logs.realtime')->middleware('permission:view-logs');
});

// Staff Routes
Route::prefix('staff')->name('staff.')->middleware(['auth', 'staff'])->group(function () {
    Route::get('/', [App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard.index');
    
    // Categories routes - Staff có thể quản lý nhưng không thể xóa
    Route::resource('categories', CategoryController::class)->except(['destroy'])->middleware('permission:view-categories');
    Route::get('categories-export', [CategoryController::class, 'export'])->name('categories.export')->middleware('permission:view-categories');
    Route::get('categories-print', [CategoryController::class, 'print'])->name('categories.print')->middleware('permission:view-categories');
    Route::get('categories-statistics', [CategoryController::class, 'statistics'])->name('categories.statistics')->middleware('permission:view-categories');
    
    // Books routes - Staff có thể quản lý nhưng không thể xóa
    Route::resource('books', App\Http\Controllers\Staff\BookController::class)->except(['destroy'])->middleware('permission:view-books');
    Route::post('books/{id}/hide', [App\Http\Controllers\Staff\BookController::class, 'hide'])->name('books.hide')->middleware('permission:edit-books');
    Route::post('books/{id}/unhide', [App\Http\Controllers\Staff\BookController::class, 'unhide'])->name('books.unhide')->middleware('permission:edit-books');
    
    // Purchasable Books routes
    Route::resource('purchasable-books', App\Http\Controllers\Staff\PurchasableBookController::class)->except(['destroy'])->middleware('permission:view-books');
    
    // Readers routes
    Route::resource('readers', ReaderController::class)->except(['destroy'])->middleware('permission:view-readers');
    Route::get('readers/{id}/renew-card', [ReaderController::class, 'renewCard'])->name('readers.renew-card')->middleware('permission:edit-readers');
    Route::post('readers/{id}/suspend', [ReaderController::class, 'suspend'])->name('readers.suspend')->middleware('permission:edit-readers');
    Route::post('readers/{id}/activate', [ReaderController::class, 'activate'])->name('readers.activate')->middleware('permission:edit-readers');
    Route::post('readers-bulk-action', [ReaderController::class, 'bulkAction'])->name('readers.bulk-action')->middleware('permission:edit-readers');
    Route::get('readers-export', [ReaderController::class, 'export'])->name('readers.export')->middleware('permission:view-readers');
    Route::get('readers-print', [ReaderController::class, 'print'])->name('readers.print')->middleware('permission:view-readers');
    Route::get('readers-statistics', [ReaderController::class, 'statistics'])->name('readers.statistics')->middleware('permission:view-readers');
    
    // Borrows routes
    Route::resource('borrows', BorrowController::class)->except(['destroy'])->middleware('permission:view-borrows');
    Route::post('borrows/{id}/return', [BorrowController::class, 'return'])->name('borrows.return')->middleware('permission:return-books');
    Route::post('borrows/{id}/extend', [BorrowController::class, 'extend'])->name('borrows.extend')->middleware('permission:edit-borrows');
    Route::get('borrows/{id}/create-item', [BorrowController::class, 'createItem'])->name('borrows.createitem')->middleware('permission:create-borrows');
    Route::post('borrows/{id}/store-item', [BorrowController::class, 'storeItem'])->name('borrows.storeItem')->middleware('permission:create-borrows');
    
    // Borrow Items routes
    Route::get('borrow-items/{id}/edit', [BorrowItemController::class, 'edit'])->name('borrowitems.edit')->middleware('permission:edit-borrows');
    Route::put('borrow-items/{id}', [BorrowItemController::class, 'update'])->name('borrowitems.update')->middleware('permission:edit-borrows');
    Route::get('borrow-items/{id}', [BorrowItemController::class, 'show'])->name('borrowitems.show')->middleware('permission:view-borrows');
    Route::get('borrow-items', [BorrowItemController::class, 'returnedItems'])->name('borrowitems.returned')->middleware('permission:view-borrows');
    Route::post('borrow-items/{id}/return', [BorrowController::class, 'returnItem'])->name('borrowitems.return')->middleware('permission:return-books');
    Route::post('borrowitems/{id}/approve', [BorrowItemController::class, 'approve'])->name('borrowitems.approve')->middleware('permission:edit-borrows');
    Route::post('borrowitems/{id}/change-status', [BorrowItemController::class, 'changeStatus'])->name('borrowitems.changeStatus')->middleware('permission:edit-borrows');
    
    // Reservations routes
    Route::resource('reservations', ReservationController::class)->except(['destroy'])->middleware('permission:view-reservations');
    Route::post('reservations/{id}/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm')->middleware('permission:confirm-reservations');
    Route::post('reservations/{id}/mark-ready', [ReservationController::class, 'markReady'])->name('reservations.mark-ready')->middleware('permission:confirm-reservations');
    Route::post('reservations/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel')->middleware('permission:edit-reservations');
    Route::get('reservations/export', [ReservationController::class, 'export'])->name('reservations.export')->middleware('permission:view-reservations');
    
    // Reviews routes
    Route::resource('reviews', ReviewController::class)->middleware('permission:view-reviews');
    Route::post('comments', [CommentController::class, 'store'])->name('comments.store')->middleware('permission:create-reviews');
    Route::put('comments/{id}', [CommentController::class, 'update'])->name('comments.update')->middleware('permission:edit-reviews');
    Route::delete('comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy')->middleware('permission:delete-reviews');
    Route::post('comments/{id}/like', [CommentController::class, 'like'])->name('comments.like')->middleware('permission:view-reviews');
    Route::post('comments/{id}/approve', [CommentController::class, 'approve'])->name('comments.approve')->middleware('permission:approve-reviews');
    Route::post('comments/{id}/reject', [CommentController::class, 'reject'])->name('comments.reject')->middleware('permission:approve-reviews');
    
    // Fines routes - Staff có thể quản lý nhưng không thể miễn phạt
    Route::resource('fines', FineController::class)->except(['destroy'])->middleware('permission:view-fines');
    Route::post('fines/{id}/mark-paid', [FineController::class, 'markAsPaid'])->name('fines.mark-paid')->middleware('permission:edit-fines');
    Route::post('fines/create-late-returns', [FineController::class, 'createLateReturnFines'])->name('fines.create-late-returns')->middleware('permission:create-fines');
    
    // Reports routes - Staff có thể xem báo cáo
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:view-reports');
    Route::get('reports/borrows', [ReportController::class, 'borrowsReport'])->name('reports.borrows')->middleware('permission:view-reports');
    Route::get('reports/readers', [ReportController::class, 'readersReport'])->name('reports.readers')->middleware('permission:view-reports');
    Route::get('reports/books', [ReportController::class, 'booksReport'])->name('reports.books')->middleware('permission:view-reports');
    
    // Advanced Search routes
    Route::get('advanced-search', [AdvancedSearchController::class, 'index'])->name('advanced-search.index')->middleware('permission:view-books');
    Route::get('search/books', [AdvancedSearchController::class, 'searchBooks'])->name('search.books')->middleware('permission:view-books');
    Route::get('search/readers', [AdvancedSearchController::class, 'searchReaders'])->name('search.readers')->middleware('permission:view-readers');
    Route::get('search/borrows', [AdvancedSearchController::class, 'searchBorrows'])->name('search.borrows')->middleware('permission:view-borrows');
    Route::get('search/global', [AdvancedSearchController::class, 'globalSearch'])->name('search.global')->middleware('permission:view-books');
    Route::get('search/suggestions', [AdvancedSearchController::class, 'getSuggestions'])->name('search.suggestions')->middleware('permission:view-books');
    Route::get('autocomplete/readers', [AdvancedSearchController::class, 'autocompleteReaders'])->name('autocomplete.readers')->middleware('permission:view-readers');
    Route::get('autocomplete/books', [AdvancedSearchController::class, 'autocompleteBooks'])->name('autocomplete.books')->middleware('permission:view-books');
    Route::get('books/{book}/inventories', [AdvancedSearchController::class, 'getBookInventories'])->name('books.inventories')->middleware('permission:view-books');
    
    // Notifications routes
    Route::get('notifications', [App\Http\Controllers\Staff\NotificationController::class, 'index'])->name('notifications.index')->middleware('permission:view-borrows');
    Route::post('notifications/send-reminders', [App\Http\Controllers\Staff\NotificationController::class, 'sendReminders'])->name('notifications.send-reminders')->middleware('permission:edit-borrows');
    Route::post('notifications/send-custom', [App\Http\Controllers\Staff\NotificationController::class, 'sendCustomReminder'])->name('notifications.send-custom')->middleware('permission:edit-borrows');
});
