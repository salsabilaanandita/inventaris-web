<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LendingController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\DamageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;

// ======= GUEST (Belum Login) =======
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('landing');
})->name('landing');

// ======= AUTH (Sudah Login) =======
Route::middleware('auth')->group(function () {

    // Dashboard & Profile Edit
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/staff-edit', [UserController::class, 'staffEdit'])->name('staff.users.edit');
    Route::put('/staff-update', [UserController::class, 'staffUpdate'])->name('staff.users.update');

    // --- KHUSUS SUPER ADMIN ---
    Route::middleware('checkrole:super_admin')->group(function () {
        Route::get('users/export', [UserController::class, 'exportExcel'])->name('users.export');
        Route::put('users/{id}/reset', [UserController::class, 'resetPassword'])->name('users.reset');
        Route::resource('users', UserController::class);
    });

    // --- SUPER ADMIN & ADMIN GUDANG ---
    Route::middleware('checkrole:super_admin,admin_gudang')->group(function () {
        Route::get('items/export', [ItemController::class, 'exportExcel'])->name('items.export');
        Route::resource('items', ItemController::class)->except(['index', 'show']);
        Route::resource('categories', CategoryController::class);
        Route::resource('locations', LocationController::class)->except('show');
        Route::resource('units', UnitController::class)->except('show');
        Route::resource('suppliers', SupplierController::class)->except('show');
        Route::resource('stock-opnames', StockOpnameController::class)->only(['index', 'create', 'store']);
        Route::get('stock-histories', [StockHistoryController::class, 'index'])->name('stock-histories.index');
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    // --- SUPER ADMIN, ADMIN GUDANG & STAFF GUDANG ---
    Route::middleware('checkrole:super_admin,admin_gudang,staff_gudang')->group(function () {
        Route::patch('/stocks/{item}', [StockController::class, 'updateStock'])->name('stocks.update');
        Route::resource('stock-ins', StockInController::class)->only(['create', 'store', 'destroy']);
        Route::get('lending/{id}', [LendingController::class, 'show'])->name('lending.show');
        Route::patch('lendings/{id}/return', [LendingController::class, 'returnItem'])->name('lendings.return');
        Route::delete('lending/{id}/delete', [LendingController::class, 'destroy'])->name('lending.destroy');
        Route::resource('lendings', LendingController::class)->except(['index', 'show']);
        Route::resource('orders', OrderController::class)->except(['index', 'show', 'edit', 'update', 'destroy']);
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::resource('customers', CustomerController::class)->except(['index', 'show', 'destroy']);
        Route::resource('maintenances', MaintenanceController::class)->only(['create', 'store']);
        Route::resource('returns', ReturnController::class)->only(['create', 'store']);
        Route::resource('damages', DamageController::class)->only(['create', 'store']);
    });

    // --- ALL ROLES (Read-Only Data) ---
    Route::middleware('checkrole:super_admin,admin_gudang,staff_gudang,manager')->group(function () {
        Route::resource('items', ItemController::class)->only(['index', 'show']);
        Route::get('items/{item}/lendings', [ItemController::class, 'lendingDetail'])->name('items.lending.detail');
        Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
        Route::resource('stock-ins', StockInController::class)->only(['index']);
        Route::resource('lendings', LendingController::class)->only(['index']);
        Route::resource('customers', CustomerController::class)->only(['index']);
        Route::resource('orders', OrderController::class)->only(['index', 'show']);
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        Route::resource('maintenances', MaintenanceController::class)->only(['index']);
        Route::resource('returns', ReturnController::class)->only(['index']);
        Route::resource('damages', DamageController::class)->only(['index']);
        Route::get('reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
        Route::get('reports/lendings', [ReportController::class, 'lendings'])->name('reports.lendings');
        Route::get('reports/mutations', [ReportController::class, 'mutations'])->name('reports.mutations');
        Route::get('reports/low-stock', [ReportController::class, 'lowStock'])->name('reports.low-stock');
    });

});