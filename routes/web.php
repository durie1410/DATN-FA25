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
use App\Http\Controllers\AdvancedReportController;
use App\Http\Controllers\AdvancedSearchController;
use App\Http\Controllers\AdvancedStatisticsController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NapTienController;

Route::get('/nap-tien', [NapTienController::class, 'form'])->name('nap-tien.form');
Route::post('/nap-tien/momo', [NapTienController::class, 'momoPayment'])->name('nap-tien.momo');

// Callback (MoMo trả về sau khi thanh toán xong)
Route::get('/nap-tien/callback', [NapTienController::class, 'callback'])->name('nap-tien.callback');

// IPN (MoMo gửi thông báo server-to-server) → có thể trống nếu bạn không cần dùng
Route::post('/nap-tien/ipn', function() {
    return response()->json(['message' => 'IPN received']);
})->name('nap-tien.ipn');

// Các trang kết quả
Route::get('/nap-tien/thanh-cong', function () {
    return view('frontend.nap-thanh-cong');
})->name('nap-tien.thanhcong');

Route::get('/nap-tien/that-bai', function () {
    return view('frontend.nap-that-bai');
})->name('nap-tien.thatbai');




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
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', function() { return redirect()->route('home'); });
Route::get('/modern', [HomeController::class, 'modern'])->name('home.modern');
Route::get('/test-simple', [HomeController::class, 'testSimple'])->name('home.test');
Route::get('/purchasable-books', [App\Http\Controllers\PurchasableBookController::class, 'index'])->name('purchasable-books.index');
Route::get('/purchasable-books/{id}', [App\Http\Controllers\PurchasableBookController::class, 'show'])->name('purchasable-books.show');
Route::post('/purchasable-books/purchase', [App\Http\Controllers\PurchasableBookController::class, 'purchase'])->name('purchasable-books.purchase')->middleware('auth');
// Route hiển thị danh sách sách cho người dùng frontend
Route::get('/books', [PublicBookController::class, 'index'])->name('books.public');
Route::get('/books/{id}', [PublicBookController::class, 'show'])->name('books.show');
Route::post('/borrow-book', [HomeController::class, 'borrowBook'])->name('borrow.book')->middleware('auth');

// Public Categories Route
Route::get('/categories', [CategoryController::class, 'publicIndex'])->name('categories.index');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Order Routes
Route::get('/checkout', [App\Http\Controllers\OrderController::class, 'checkout'])->name('checkout');
Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Google OAuth Routes
    Route::get('/auth/google', [App\Http\Controllers\GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [App\Http\Controllers\GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// 👇 Di chuyển phần đăng ký độc giả ra đây
Route::middleware('auth')->group(function () {
    Route::get('/register-reader', [App\Http\Controllers\ReaderRegistrationController::class, 'showRegistrationForm'])
        ->name('register.reader.form');
    Route::post('/register-reader', [App\Http\Controllers\ReaderRegistrationController::class, 'register'])
        ->name('register.reader');
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
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    // Test route for email marketing (no permission required)
    Route::get('/email-marketing-test', function() {
        return 'Email Marketing Admin Test - Routes are working!';
    })->name('email-marketing.test');
    
    // Simple test route for email marketing controller
    Route::get('/email-marketing-simple', function() {
        return view('admin.email-marketing.test', [
            'campaigns' => App\Models\EmailCampaign::all(),
            'stats' => [
                'total_campaigns' => App\Models\EmailCampaign::count(),
                'active_subscribers' => App\Models\EmailSubscriber::active()->count(),
                'total_emails_sent' => App\Models\EmailLog::count(),
                'open_rate' => 0,
            ]
        ]);
    })->name('email-marketing.simple');
    
      // Resource routes
      Route::resource('categories', CategoryController::class)->middleware('permission:view-categories');
    Route::get('categories-export', [CategoryController::class, 'export'])->name('categories.export')->middleware('permission:view-categories');
    Route::get('categories-print', [CategoryController::class, 'print'])->name('categories.print')->middleware('permission:view-categories');
    Route::get('categories-statistics', [CategoryController::class, 'statistics'])->name('categories.statistics')->middleware('permission:view-categories');
    Route::post('categories-bulk-action', [CategoryController::class, 'bulkAction'])->name('categories.bulk-action')->middleware('permission:edit-categories');
    Route::post('categories/{id}/move-books', [CategoryController::class, 'moveBooks'])->name('categories.move-books')->middleware('permission:edit-categories');
        Route::resource('authors', App\Http\Controllers\Admin\AuthorController::class)->middleware('permission:view-readers');
      Route::resource('books', BookController::class)->middleware('permission:view-books');
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
      
      // Reports routes
      Route::get('reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:view-reports');
      Route::get('reports/borrows', [ReportController::class, 'borrowsReport'])->name('reports.borrows')->middleware('permission:view-reports');
      Route::get('reports/readers', [ReportController::class, 'readersReport'])->name('reports.readers')->middleware('permission:view-reports');
      Route::get('reports/books', [ReportController::class, 'booksReport'])->name('reports.books')->middleware('permission:view-reports');
      
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
      
      // Test route without permission middleware
      Route::get('test-stats', [AdvancedStatisticsController::class, 'dashboard'])->name('test.stats');
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
      Route::resource('inventory', InventoryController::class)->middleware('permission:view-books');
      Route::post('inventory/{id}/transfer', [InventoryController::class, 'transfer'])->name('inventory.transfer')->middleware('permission:edit-books');
      Route::post('inventory/{id}/repair', [InventoryController::class, 'repair'])->name('inventory.repair')->middleware('permission:edit-books');
      Route::get('inventory-transactions', [InventoryController::class, 'transactions'])->name('inventory.transactions')->middleware('permission:view-books');
      Route::get('inventory-dashboard', [InventoryController::class, 'dashboard'])->name('inventory.dashboard')->middleware('permission:view-books');
      Route::post('inventory/scan-barcode', [InventoryController::class, 'scanBarcode'])->name('inventory.scan-barcode')->middleware('permission:view-books');
      
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
    Route::get('/', [App\Http\Controllers\StaffDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\StaffDashboardController::class, 'index'])->name('dashboard.index');
    
    // Staff có thể quản lý sách, độc giả, mượn trả nhưng không thể xóa
    Route::resource('books', App\Http\Controllers\StaffBookController::class)->except(['destroy'])->middleware('permission:view-books');
    Route::resource('categories', CategoryController::class)->except(['destroy'])->middleware('permission:view-categories');
    Route::resource('readers', ReaderController::class)->except(['destroy'])->middleware('permission:view-readers');
    Route::get('readers/{id}/renew-card', [ReaderController::class, 'renewCard'])->name('readers.renew-card')->middleware('permission:edit-readers');
    Route::post('readers/{id}/suspend', [ReaderController::class, 'suspend'])->name('readers.suspend')->middleware('permission:edit-readers');
    Route::post('readers/{id}/activate', [ReaderController::class, 'activate'])->name('readers.activate')->middleware('permission:edit-readers');
    Route::get('readers-export', [ReaderController::class, 'export'])->name('readers.export')->middleware('permission:view-readers');
    Route::get('readers-print', [ReaderController::class, 'print'])->name('readers.print')->middleware('permission:view-readers');
    Route::get('readers-statistics', [ReaderController::class, 'statistics'])->name('readers.statistics')->middleware('permission:view-readers');
    Route::resource('borrows', BorrowController::class)->except(['destroy'])->middleware('permission:view-borrows');
    Route::post('borrows/{id}/return', [BorrowController::class, 'return'])->name('borrows.return')->middleware('permission:return-books');
    Route::post('borrows/{id}/extend', [BorrowController::class, 'extend'])->name('borrows.extend')->middleware('permission:edit-borrows');
    
    // Staff có thể xử lý đặt chỗ và phê duyệt đánh giá
    Route::resource('reservations', ReservationController::class)->except(['destroy'])->middleware('permission:view-reservations');
    Route::post('reservations/{id}/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm')->middleware('permission:confirm-reservations');
    Route::post('reservations/{id}/mark-ready', [ReservationController::class, 'markReady'])->name('reservations.mark-ready')->middleware('permission:confirm-reservations');
    
    Route::resource('reviews', ReviewController::class)->middleware('permission:view-reviews');
    Route::post('comments/{id}/approve', [CommentController::class, 'approve'])->name('comments.approve')->middleware('permission:approve-reviews');
    Route::post('comments/{id}/reject', [CommentController::class, 'reject'])->name('comments.reject')->middleware('permission:approve-reviews');
    
    // Staff có thể quản lý phạt nhưng không thể miễn phạt
    Route::resource('fines', FineController::class)->except(['destroy'])->middleware('permission:view-fines');
    Route::post('fines/{id}/mark-paid', [FineController::class, 'markAsPaid'])->name('fines.mark-paid')->middleware('permission:edit-fines');
    
    // Staff có thể xem báo cáo nhưng không thể xuất
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index')->middleware('permission:view-reports');
    Route::get('reports/borrows', [ReportController::class, 'borrowsReport'])->name('reports.borrows')->middleware('permission:view-reports');
    Route::get('reports/readers', [ReportController::class, 'readersReport'])->name('reports.readers')->middleware('permission:view-reports');
    Route::get('reports/books', [ReportController::class, 'booksReport'])->name('reports.books')->middleware('permission:view-reports');
    
    // Staff có thể gửi thông báo
    Route::get('notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index')->middleware('permission:view-borrows');
    Route::post('notifications/send-reminders', [App\Http\Controllers\NotificationController::class, 'sendReminders'])->name('notifications.send-reminders')->middleware('permission:edit-borrows');
    Route::post('notifications/send-custom', [App\Http\Controllers\NotificationController::class, 'sendCustomReminder'])->name('notifications.send-custom')->middleware('permission:edit-borrows');
});

// Test route for email marketing
Route::get('/test-email-marketing', function() {
    return 'Email Marketing routes are working!';
});
