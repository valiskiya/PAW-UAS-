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

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard - All roles
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile - All roles
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');
    
    // DIREKTUR ROUTES
    Route::middleware(['role:direktur'])->prefix('direktur')->name('direktur.')->group(function () {
        Route::get('/laporan/laba-rugi', [ReportController::class, 'labaRugi'])->name('laporan.laba-rugi');
        Route::get('/laporan/bulanan', [ReportController::class, 'bulanan'])->name('laporan.bulanan');
        Route::get('/laporan/pelanggan', [ReportController::class, 'pelanggan'])->name('laporan.pelanggan');
        Route::get('/laporan/efisiensi', [ReportController::class, 'efisiensi'])->name('laporan.efisiensi');
    });
    
    // MANAJER UNIT ROUTES
    Route::middleware(['role:manajer_unit'])->prefix('manajer')->name('manajer.')->group(function () {
        // Products
        Route::resource('products', ProductController::class);
        Route::post('products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');
        
        // Customers
        Route::resource('customers', CustomerController::class);
        
        // Suppliers
        Route::resource('suppliers', SupplierController::class);
        
        // Transactions
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::post('transactions/{transaction}/return', [TransactionController::class, 'return'])->name('transactions.return');
        
        // Employees
        Route::resource('employees', EmployeeController::class);
        
        // Attendance
        Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::get('attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
        Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store');
        Route::get('attendances/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendances.edit');
        Route::put('attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');
        
        // Salary
        Route::get('salaries', [SalaryPaymentController::class, 'index'])->name('salaries.index');
        Route::get('salaries/generate', [SalaryPaymentController::class, 'generate'])->name('salaries.generate');
        Route::post('salaries/calculate', [SalaryPaymentController::class, 'calculate'])->name('salaries.calculate');
        Route::post('salaries/{salary}/pay', [SalaryPaymentController::class, 'pay'])->name('salaries.pay');
        
        // Purchase Orders
        Route::resource('purchase-
        orders', PurchaseOrderController::class);
        Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
        
        // Stock Cards
        Route::get('stock-cards', [StockCardController::class, 'index'])->name('stock-cards.index');
        Route::get('stock-cards/product/{product}', [StockCardController::class, 'byProduct'])->name('stock-cards.product');
        
        // Reports
        Route::get('reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
        Route::get('reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
        Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
        Route::get('reports/profit', [ReportController::class, 'profit'])->name('reports.profit');
        Route::get('reports/salary', [ReportController::class, 'salary'])->name('reports.salary');
    });
    
    // KASIR ROUTES
    Route::middleware(['role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('pos', [TransactionController::class, 'pos'])->name('pos');
        Route::post('pos/create', [TransactionController::class, 'store'])->name('pos.store');
        Route::get('transactions', [TransactionController::class, 'kasirIndex'])->name('transactions.index');
        Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');
        
        // Customer lookup
        Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    });
    
    // LOGISTIK ROUTES
    Route::middleware(['role:logistik'])->prefix('logistik')->name('logistik.')->group(function () {
        // Products view only
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
        
        // Stock Cards
        Route::get('stock-cards', [StockCardController::class, 'index'])->name('stock-cards.index');
        Route::get('stock-cards/product/{product}', [StockCardController::class, 'byProduct'])->name('stock-cards.product');
        
        // Purchase Orders
        Route::get('purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
        Route::get('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase-orders.show');
        Route::post('purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
        
        // Unit Conversion
        Route::get('conversion', [ProductController::class, 'conversionPage'])->name('conversion');
        Route::post('conversion/execute', [ProductController::class, 'executeConversion'])->name('conversion.execute');
        
        // Stock Opname
        Route::get('stock-opname', [ProductController::class, 'stockOpname'])->name('stock-opname');
        Route::post('stock-opname/save', [ProductController::class, 'saveStockOpname'])->name('stock-opname.save');
        
        // Suppliers view
        Route::get('suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    });
    
    // ADMIN TI ROUTES
    Route::middleware(['role:admin_ti'])->prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::resource('users', UserController::class);
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // System Settings
        Route::get('settings', [UserController::class, 'settings'])->name('settings');
        Route::post('settings/update', [UserController::class, 'updateSettings'])->name('settings.update');
        
        // Backup
        Route::get('backup', [UserController::class, 'backup'])->name('backup');
        Route::post('backup/create', [UserController::class, 'createBackup'])->name('backup.create');
        Route::get('backup/download/{file}', [UserController::class, 'downloadBackup'])->name('backup.download');
        
        // Activity Logs
        Route::get('logs', [UserController::class, 'logs'])->name('logs');
        
        // System Monitoring
        Route::get('monitoring', [UserController::class, 'monitoring'])->name('monitoring');
    });
});

// API Routes for AJAX (if needed)
Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    Route::get('products/search', [ProductController::class, 'apiSearch'])->name('products.search');
    Route::get('customers/search', [CustomerController::class, 'apiSearch'])->name('customers.search');
    Route::get('products/{product}/price', [ProductController::class, 'getPrice'])->name('products.price');
});