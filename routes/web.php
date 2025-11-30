<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\StockCardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalaryPaymentController;
use App\Http\Controllers\UserController;


Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

/*
|--------------------------------------------------------------------------
| ROUTE YANG HARUS LOGIN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // /dashboard -> redirect ke dashboard per role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile - semua role
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');

    /*
    |--------------------------------------------------------------------------
    | DIREKTUR
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:direktur'])
        ->prefix('direktur')
        ->name('direktur.')
        ->group(function () {

        Route::get('dashboard', [DashboardController::class, 'direkturDashboard'])->name('dashboard');

        Route::get('laporan/laba-rugi', [ReportController::class, 'labaRugi'])->name('laporan.laba-rugi');
        Route::get('laporan/bulanan', [ReportController::class, 'bulanan'])->name('laporan.bulanan');
        Route::get('laporan/pelanggan', [ReportController::class, 'pelanggan'])->name('laporan.pelanggan');
        Route::get('laporan/efisiensi', [ReportController::class, 'efisiensi'])->name('laporan.efisiensi');
    });

    /*
    |--------------------------------------------------------------------------
    | MANAJER UNIT
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:manajer_unit'])
        ->prefix('manajer')
        ->name('manajer.')
        ->group(function () {

        Route::get('dashboard', [DashboardController::class, 'manajerDashboard'])->name('dashboard');

        Route::resource('products', ProductController::class);
        Route::post('products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');

        Route::resource('customers', CustomerController::class);
        Route::resource('suppliers', SupplierController::class);

        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::post('transactions/{transaction}/return', [TransactionController::class, 'return'])->name('transactions.return');

        Route::resource('employees', EmployeeController::class);

        Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::get('attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
        Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store');
        Route::get('attendances/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendances.edit');
        Route::put('attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');

        Route::get('salaries', [SalaryPaymentController::class, 'index'])->name('salaries.index');
        Route::get('salaries/generate', [SalaryPaymentController::class, 'generate'])->name('salaries.generate');
        Route::post('salaries/calculate', [SalaryPaymentController::class, 'calculate'])->name('salaries.calculate');
        Route::post('salaries/{salary}/pay', [SalaryPaymentController::class, 'pay'])->name('salaries.pay');

        Route::resource('purchase-orders', PurchaseOrderController::class);
        Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');

        Route::get('stock-cards', [StockCardController::class, 'index'])->name('stock-cards.index');
        Route::get('stock-cards/product/{product}', [StockCardController::class, 'byProduct'])->name('stock-cards.product');

        Route::get('reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
        Route::get('reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
        Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('reports/profit', [ReportController::class, 'profit'])->name('reports.profit');
        Route::get('reports/salary', [ReportController::class, 'salary'])->name('reports.salary');
    });

    /*
    |--------------------------------------------------------------------------
    | KASIR
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:kasir'])
        ->prefix('kasir')
        ->name('kasir.')
        ->group(function () {

        Route::get('dashboard', [DashboardController::class, 'kasirDashboard'])->name('dashboard');

        Route::get('pos', [TransactionController::class, 'pos'])->name('pos');
        Route::post('pos/create', [TransactionController::class, 'store'])->name('pos.store');
        Route::get('transactions', [TransactionController::class, 'kasirIndex'])->name('transactions.index');
        Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');

        Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    });

    /*
    |--------------------------------------------------------------------------
    | LOGISTIK
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:logistik'])
        ->prefix('logistik')
        ->name('logistik.')
        ->group(function () {

        Route::get('dashboard', [DashboardController::class, 'logistikDashboard'])->name('dashboard');

        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

        Route::get('stock-cards', [StockCardController::class, 'index'])->name('stock-cards.index');
        Route::get('stock-cards/product/{product}', [StockCardController::class, 'byProduct'])->name('stock-cards.product');

        Route::get('purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
        Route::get('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
        Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');

        Route::get('conversion', [ProductController::class, 'conversionPage'])->name('conversion');
        Route::post('conversion/execute', [ProductController::class, 'executeConversion'])->name('conversion.execute');

        Route::get('stock-opname', [ProductController::class, 'stockOpname'])->name('stock-opname');
        Route::post('stock-opname/save', [ProductController::class, 'saveStockOpname'])->name('stock-opname.save');

        Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

        Route::get('dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

        Route::resource('users', UserController::class);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        Route::get('settings', [UserController::class, 'settings'])->name('settings');
        Route::post('settings/update', [UserController::class, 'updateSettings'])->name('settings.update');

        Route::get('backup', [UserController::class, 'backup'])->name('backup');
        Route::post('backup/create', [UserController::class, 'createBackup'])->name('backup.create');
        Route::get('backup/download/{file}', [UserController::class, 'downloadBackup'])->name('backup.download');

        Route::get('logs', [UserController::class, 'logs'])->name('logs');
        Route::get('monitoring', [UserController::class, 'monitoring'])->name('monitoring');
    });
});

/*
|--------------------------------------------------------------------------
| API (AJAX)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('api')
    ->name('api.')
    ->group(function () {

    Route::get('products/search', [ProductController::class, 'apiSearch'])->name('products.search');
    Route::get('customers/search', [CustomerController::class, 'apiSearch'])->name('customers.search');
    Route::get('products/{product}/price', [ProductController::class, 'getPrice'])->name('products.price');
});
