<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\AduanController;
use App\Http\Controllers\StokRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\Admin\AsetController;
use App\Http\Controllers\Admin\StockController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Public auth routes (guests)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Single dashboard entry (can redirect by role inside DashboardController)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Assets resource:
    // We keep `show` accessible to authenticated users but restrict create/edit/delete to admin roles.
    Route::get('assets/{asset}', [AssetController::class, 'show'])->name('assets.show');

    Route::middleware('role:admin_ict,admin_hr')->group(function () {
        // admin-only asset management routes (create/edit/delete)
        Route::resource('assets', AssetController::class)->except(['show', 'index']);
    });

    // Allow listing assets to all authenticated users:
    Route::get('assets', [AssetController::class, 'index'])->name('assets.index');

    // Other resources (adjust middleware inside controllers if more granular control needed)
    Route::resource('pinjaman', PinjamanController::class);
    Route::resource('aduan', AduanController::class);
    Route::resource('stok-requests', StokRequestController::class)->names('stok-requests');

    // Notifications
    Route::get('/notifications/fetch', [NotificationController::class,'fetch'])->name('notifications.fetch');
    Route::post('/notifications/mark-all-read', [NotificationController::class,'markAllRead'])->name('notifications.markAll');

    // Role-specific dashboards (named routes)
    Route::get('/dashboard/admin', [AdminDashboardController::class,'index'])
        ->name('dashboard.admin')
        ->middleware('role:admin_ict');

    Route::get('/dashboard/hr', [HrController::class,'index'])
        ->name('dashboard.hr')
        ->middleware('role:admin_hr');

    Route::get('/dashboard/staff', [StaffController::class,'index'])
        ->name('dashboard.staff')
        ->middleware('role:staff');
    
   Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/help',   [HelpController::class, 'index'])->name('help.index');

Route::middleware(['auth'])->group(function () {
    // Staff create / list their requests
    Route::get('/stok/request/create', [StokRequestController::class, 'create'])->name('stok.request.create');
    Route::post('/stok/request', [StokRequestController::class, 'store'])->name('stok.request.store');

    // My requests
    Route::get('/my/stok-requests', [StokRequestController::class, 'index'])->name('stok.request.index');
    Route::get('/my/stok-requests/{request}', [StokRequestController::class, 'show'])->name('stok.request.show');
});

Route::get('assets/export', [AssetController::class, 'export'])->name('assets.export');


Route::get('pinjaman/history', [PinjamanController::class, 'history'])
    ->name('pinjaman.history')
    ->middleware('auth');

    // Categories handled by AduanController (not separate controller)
    Route::get('aduan/kategori', [AduanController::class, 'kategoriIndex'])->name('aduan.kategori');
    Route::get('aduan/kategori/create', [AduanController::class, 'kategoriCreate'])->name('aduan.kategori.create');
    Route::post('aduan/kategori', [AduanController::class, 'kategoriStore'])->name('aduan.kategori.store');
    Route::get('aduan/kategori/{kategori}/edit', [AduanController::class, 'kategoriEdit'])->name('aduan.kategori.edit');
    Route::put('aduan/kategori/{kategori}', [AduanController::class, 'kategoriUpdate'])->name('aduan.kategori.update');
    Route::delete('aduan/kategori/{kategori}', [AduanController::class, 'kategoriDestroy'])->name('aduan.kategori.destroy');

Route::middleware(['auth'])->group(function () {
    Route::get('aduan/create', [AduanController::class, 'create'])->name('aduan.create');
    Route::post('aduan', [AduanController::class, 'store'])->name('aduan.store');
});

Route::middleware(['auth'])->group(function () {
    // other aduan routes...
    Route::get('aduan/pdf', [AduanController::class, 'pdf'])->name('aduan.pdf');
});

Route::prefix('admin/assets')->name('admin.assets.')->middleware(['auth'])->group(function () {
    Route::get('/', [AsetController::class, 'index'])->name('index');
    Route::get('/create', [AsetController::class, 'create'])->name('create');
    Route::post('/', [AsetController::class, 'store'])->name('store');
    Route::get('/{asset}/edit', [AsetController::class, 'edit'])->name('edit');
    Route::put('/{asset}', [AsetController::class, 'update'])->name('update');
    Route::delete('/{asset}', [AsetController::class, 'destroy'])->name('destroy');

    Route::get('/export', [AsetController::class, 'exportIndex'])->name('export');
    Route::get('/export/csv', [AsetController::class, 'exportCsv'])->name('export.csv');
    Route::get('/export/pdf', [AsetController::class, 'exportPdf'])->name('export.pdf');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/pinjaman/index', [AdminPinjamanController::class, 'index'])->name('pinjaman.index');
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/pinjaman/approvals', [AdminPinjamanController::class, 'approvals'])->name('pinjaman.approvals');
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/pinjaman/history', [AdminPinjamanController::class, 'history'])->name('pinjaman.history');
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/aduan/list', [AdminAduanController::class, 'index'])->name('aduan.list');
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/aduan/status', [AdminAduanController::class, 'status'])->name('aduan.status');
});
Route::prefix('admin/stok')->name('admin.stok.')->middleware(['auth','role:admin_ict'])->group(function () {
    // Stock management
    Route::get('/', [StockController::class, 'index'])->name('index');            // optional: list registered stock
    Route::get('/register', [StockController::class, 'create'])->name('register');
    Route::post('/register', [StockController::class, 'store'])->name('store');
    Route::get('/{stok}/edit', [StockController::class, 'edit'])->name('edit');
    Route::put('/{stok}', [StockController::class, 'update'])->name('update');
    Route::delete('/{stok}', [StockController::class, 'destroy'])->name('destroy');

    // Requests
    Route::get('/requests', [StockController::class, 'requests'])->name('requests');
    Route::get('/requests/{request}/show', [StockController::class, 'showRequest'])->name('requests.show');
    Route::post('/requests/{request}/status', [StockController::class, 'updateRequestStatus'])->name('requests.update_status');

    // Optional: status page (summary)
    Route::get('/status', [StockController::class, 'statusOverview'])->name('status');
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/users/roles', [UserRoleController::class, 'index'])->name('users.roles');
});
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/users/roles', [UserRoleController::class, 'index'])->name('users.roles');
});
Route::prefix('admin')->name('admin.')->middleware(['auth','isAdmin'])->group(function () {
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/logs', [LogsController::class, 'index'])->name('logs');
});









    /*
     |--------------------------------------------------------------------------
     | Admin prefixed routes (name prefix = admin.)
     |--------------------------------------------------------------------------
     | All route names inside this group will be prefixed with `admin.` (e.g.
     | `admin.users.index`, `admin.reports.index`, `admin.assets.index`).
     | This avoids collisions with the public/auth routes (like `assets.index`).
     */
    Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin_ict'])->group(function() {
        // admin/users -> name: admin.users.index
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        // admin/reports -> name: admin.reports.index
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        // admin/help -> name: admin.help.index
        Route::get('/help', [HelpController::class, 'index'])->name('help.index');

        // admin/assets -> name: admin.assets.index
        // NOTE: If you intend to use a separate admin controller, swap AssetController for that controller.
        Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
    });

    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// auth scaffolding (Breeze / Fortify / Jetstream)
require __DIR__ . '/auth.php';
