<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\BookApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BorrowApiController;
use App\Http\Controllers\Api\DepartmentApiController;
use App\Http\Controllers\Api\FacultyApiController;
use App\Http\Controllers\Api\PublisherApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public API routes
Route::get('/', [ApiController::class, 'index']);
Route::get('/stats', [ApiController::class, 'stats']);

// Books API
Route::get('/books', [BookApiController::class, 'index']);
Route::get('/books/{id}', [BookApiController::class, 'show']);
Route::get('/books/featured', [BookApiController::class, 'featured']);
Route::get('/categories', [BookApiController::class, 'categories']);

// Departments API
Route::get('/departments', [DepartmentApiController::class, 'index']);
Route::get('/departments/active', [DepartmentApiController::class, 'getActive']);
Route::get('/departments/faculty/{facultyId}', [DepartmentApiController::class, 'getByFaculty']);
Route::get('/departments/{id}', [DepartmentApiController::class, 'show']);

// Faculties API
Route::get('/faculties', [FacultyApiController::class, 'index']);
Route::get('/faculties/active', [FacultyApiController::class, 'getActive']);
Route::get('/faculties/with-departments', [FacultyApiController::class, 'getWithDepartments']);
Route::get('/faculties/{id}', [FacultyApiController::class, 'show']);

// Publishers API
Route::get('/publishers', [PublisherApiController::class, 'index']);
Route::get('/publishers/active', [PublisherApiController::class, 'getActive']);
Route::get('/publishers/with-books', [PublisherApiController::class, 'getWithBooks']);
Route::get('/publishers/{id}', [PublisherApiController::class, 'show']);

// Authentication API
Route::post('/auth/login', [AuthApiController::class, 'login']);
Route::post('/auth/register', [AuthApiController::class, 'register']);

// Reviews API (sử dụng web authentication)
Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'createReview'])->middleware('web');
Route::get('/books/{bookId}/reviews', [\App\Http\Controllers\ReviewController::class, 'getBookReviews']);

// Favorites API (sử dụng web authentication)
Route::post('/favorites/toggle', [\App\Http\Controllers\Api\BookApiController::class, 'toggleFavorite'])->middleware('web');

// Borrow API routes (public for mobile app)
Route::prefix('borrows')->group(function () {
    Route::get('/reader', [BorrowApiController::class, 'getReaderBorrows']);
    Route::get('/{id}', [BorrowApiController::class, 'getBorrowDetail']);
    Route::post('/{id}/extend', [BorrowApiController::class, 'extendBorrow']);
    Route::post('/{id}/return', [BorrowApiController::class, 'returnBook']);
    Route::get('/reader/stats', [BorrowApiController::class, 'getReaderStats']);
});

Route::get('/books/available', [BorrowApiController::class, 'getAvailableBooks']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/user', [AuthApiController::class, 'user']);
    Route::post('/auth/logout', [AuthApiController::class, 'logout']);
    Route::get('/auth/reader-profile', [AuthApiController::class, 'readerProfile']);
    
    // Admin only borrow operations
    Route::post('/borrows', [BorrowApiController::class, 'createBorrow'])->middleware('admin');
    
    // Backup API routes (Admin only)
    Route::prefix('backups')->middleware('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BackupController::class, 'list']);
        Route::post('/create', [\App\Http\Controllers\Admin\BackupController::class, 'apiCreate']);
        Route::post('/restore', [\App\Http\Controllers\Admin\BackupController::class, 'apiRestore']);
        Route::get('/statistics', [\App\Http\Controllers\Admin\BackupController::class, 'statistics']);
    });
    
    // Advanced Reports API routes (Admin only)
    Route::prefix('reports')->middleware('admin')->group(function () {
        Route::get('/dashboard-stats', [\App\Http\Controllers\Admin\AdvancedReportController::class, 'dashboardStats']);
        Route::get('/borrowing-trends', [\App\Http\Controllers\Admin\AdvancedReportController::class, 'borrowingTrends']);
        Route::get('/popular-books', [\App\Http\Controllers\Admin\AdvancedReportController::class, 'popularBooks']);
        Route::get('/active-readers', [\App\Http\Controllers\Admin\AdvancedReportController::class, 'activeReaders']);
        Route::get('/overdue-books', [\App\Http\Controllers\Admin\AdvancedReportController::class, 'overdueBooks']);
        Route::get('/fine-statistics', [\App\Http\Controllers\Admin\AdvancedReportController::class, 'fineStatistics']);
        Route::get('/category-performance', [\App\Http\Controllers\Admin\AdvancedReportController::class, 'categoryPerformance']);
        Route::get('/monthly-report', [\App\Http\Controllers\Admin\AdvancedReportController::class, 'monthlyReport']);
        Route::get('/yearly-report', [\App\Http\Controllers\Admin\AdvancedReportController::class, 'yearlyReport']);
        Route::get('/real-time-stats', [\App\Http\Controllers\Admin\AdvancedReportController::class, 'realTimeStats']);
    });
    
    // Mobile API routes
    Route::prefix('mobile')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Api\MobileApiController::class, 'dashboard']);
        Route::get('/search-books', [\App\Http\Controllers\Api\MobileApiController::class, 'searchBooks']);
        Route::get('/book/{id}', [\App\Http\Controllers\Api\MobileApiController::class, 'getBookDetails']);
        Route::get('/categories', [\App\Http\Controllers\Api\MobileApiController::class, 'getCategories']);
        Route::get('/borrow-history', [\App\Http\Controllers\Api\MobileApiController::class, 'getBorrowHistory']);
        Route::get('/reservations', [\App\Http\Controllers\Api\MobileApiController::class, 'getReservations']);
        Route::get('/fines', [\App\Http\Controllers\Api\MobileApiController::class, 'getFines']);
        Route::post('/borrow/{id}/extend', [\App\Http\Controllers\Api\MobileApiController::class, 'extendBorrow']);
        Route::post('/reservations', [\App\Http\Controllers\Api\MobileApiController::class, 'createReservation']);
        Route::delete('/reservations/{id}', [\App\Http\Controllers\Api\MobileApiController::class, 'cancelReservation']);
    });
    
    // Real-time Notifications API routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
        Route::post('/{id}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
        Route::post('/read-all', [\App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
        Route::get('/unread-count', [\App\Http\Controllers\Api\NotificationController::class, 'unreadCount']);
        Route::post('/online', [\App\Http\Controllers\Api\NotificationController::class, 'markOnline']);
        Route::post('/offline', [\App\Http\Controllers\Api\NotificationController::class, 'markOffline']);
        Route::post('/test', [\App\Http\Controllers\Api\NotificationController::class, 'sendTest']);
    });
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
