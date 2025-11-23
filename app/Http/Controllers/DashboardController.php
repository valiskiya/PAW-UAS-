<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\PurchaseOrder;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * /dashboard
     * Redirect ke dashboard sesuai role user.
     */
    public function index()
{
    $user = auth()->user();

    if (! $user || ! $user->role) {
        abort(403, 'Role pengguna tidak ditemukan');
    }

    $role = $user->role->name;

    switch ($role) {
        case 'direktur':
            return redirect()->route('direktur.dashboard');

        case 'manajer_unit':
            return redirect()->route('manajer.dashboard');

        case 'kasir':
            return redirect()->route('kasir.dashboard');

        case 'logistik':
            return redirect()->route('logistik.dashboard');

        case 'admin':
            return redirect()->route('admin.dashboard');

        default:
            // Daripada cari view yang tidak ada, lebih aman abort 403
            abort(403, 'Role "' . $role . '" tidak dikenali. Silakan hubungi Admin TI.');
    }
}


    /**
     * Dashboard Direktur
     */
    public function direkturDashboard()
    {
        $today     = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear  = Carbon::now()->year;

        // KPI Data
        $totalRevenueToday = Transaction::whereDate('transaction_date', $today)
            ->where('status', 'completed')
            ->sum('total');

        $totalRevenueMonth = Transaction::whereMonth('transaction_date', $thisMonth)
            ->whereYear('transaction_date', $thisYear)
            ->where('status', 'completed')
            ->sum('total');

        $totalTransactionsToday = Transaction::whereDate('transaction_date', $today)
            ->where('status', 'completed')
            ->count();

        $totalTransactionsMonth = Transaction::whereMonth('transaction_date', $thisMonth)
            ->whereYear('transaction_date', $thisYear)
            ->where('status', 'completed')
            ->count();

        // Grafik penjualan 7 hari terakhir
        $salesChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date  = Carbon::today()->subDays($i);
            $sales = Transaction::whereDate('transaction_date', $date)
                ->where('status', 'completed')
                ->sum('total');

            $salesChart[] = [
                'date'  => $date->format('d M'),
                'sales' => $sales,
            ];
        }

        // Top products bulan ini
        $topProducts = Product::withCount(['transactionDetails' => function ($query) use ($thisMonth, $thisYear) {
                $query->whereHas('transaction', function ($q) use ($thisMonth, $thisYear) {
                    $q->whereMonth('transaction_date', $thisMonth)
                      ->whereYear('transaction_date', $thisYear)
                      ->where('status', 'completed');
                });
            }])
            ->orderBy('transaction_details_count', 'desc')
            ->take(5)
            ->get();

        // Produk stok menipis
        $lowStockProducts = Product::whereRaw('(stock_large * conversion_factor + stock_small) < min_stock')
            ->where('status', 'active')
            ->take(10)
            ->get();

        return view('dashboard.direktur', compact(
            'totalRevenueToday',
            'totalRevenueMonth',
            'totalTransactionsToday',
            'totalTransactionsMonth',
            'salesChart',
            'topProducts',
            'lowStockProducts'
        ));
    }

    /**
     * Dashboard Manajer Unit
     */
    public function manajerDashboard()
    {
        $today     = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear  = Carbon::now()->year;

        // Summary cards
        $totalProducts  = Product::where('status', 'active')->count();
        $totalCustomers = Customer::where('status', 'active')->count();
        $totalEmployees = Employee::where('status', 'active')->count();

        $todayRevenue = Transaction::whereDate('transaction_date', $today)
            ->where('status', 'completed')
            ->sum('total');

        $todayTransactions = Transaction::whereDate('transaction_date', $today)
            ->where('status', 'completed')
            ->count();

        // Pending POs
        $pendingPOs = PurchaseOrder::where('status', 'pending')->count();

        // Low stock alert
        $lowStockCount = Product::whereRaw('(stock_large * conversion_factor + stock_small) < min_stock')
            ->where('status', 'active')
            ->count();

        // Recent transactions
        $recentTransactions = Transaction::with(['customer', 'cashier'])
            ->latest()
            ->take(5)
            ->get();

        // Attendance today
        $todayAttendance = Attendance::with(['employee', 'shift'])
            ->whereDate('attendance_date', $today)
            ->get();

        // Employees on leave warning (>=6 izin dalam sebulan)
        $employeesNearLimit = Employee::whereHas('attendances', function ($query) use ($thisMonth, $thisYear) {
                $query->whereMonth('attendance_date', $thisMonth)
                      ->whereYear('attendance_date', $thisYear)
                      ->where('status', 'izin');
            }, '>=', 6)
            ->get();

        return view('dashboard.manajer', compact(
            'totalProducts',
            'totalCustomers',
            'totalEmployees',
            'todayRevenue',
            'todayTransactions',
            'pendingPOs',
            'lowStockCount',
            'recentTransactions',
            'todayAttendance',
            'employeesNearLimit'
        ));
    }

    /**
     * Dashboard Kasir
     */
    public function kasirDashboard()
    {
        $today = Carbon::today();
        $user  = auth()->user();

        // Transaksi hari ini oleh kasir ini
        $todayTransactions = Transaction::where('cashier_id', $user->id)
            ->whereDate('transaction_date', $today)
            ->where('status', 'completed')
            ->count();

        $todayRevenue = Transaction::where('cashier_id', $user->id)
            ->whereDate('transaction_date', $today)
            ->where('status', 'completed')
            ->sum('total');

        // Recent transactions
        $recentTransactions = Transaction::with(['customer'])
            ->where('cashier_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Total member
        $totalMembers = Customer::where('type', '!=', 'non_member')
            ->where('status', 'active')
            ->count();

        return view('dashboard.kasir', compact(
            'todayTransactions',
            'todayRevenue',
            'recentTransactions',
            'totalMembers'
        ));
    }

    /**
     * Dashboard Logistik
     */
    public function logistikDashboard()
    {
        $today = Carbon::today();

        // Pending POs
        $pendingPOs = PurchaseOrder::with(['supplier', 'product'])
            ->where('status', 'pending')
            ->get();

        // Low stock products
        $lowStockProducts = Product::whereRaw('(stock_large * conversion_factor + stock_small) < min_stock')
            ->where('status', 'active')
            ->get();

        // Today's received POs
        $todayReceived = PurchaseOrder::with(['supplier', 'product'])
            ->whereDate('received_date', $today)
            ->where('status', 'received')
            ->get();

        // Stock summary
        $totalProducts = Product::where('status', 'active')->count();
        $criticalStock = Product::whereRaw('(stock_large * conversion_factor + stock_small) < (min_stock * 0.5)')
            ->where('status', 'active')
            ->count();

        return view('dashboard.logistik', compact(
            'pendingPOs',
            'lowStockProducts',
            'todayReceived',
            'totalProducts',
            'criticalStock'
        ));
    }

    /**
     * Dashboard Admin TI
     */
    public function adminDashboard()
    {
        $today = Carbon::today();

        // System stats
        $totalUsers        = \App\Models\User::where('status', 'active')->count();
        $totalTransactions = Transaction::count();
        $totalProducts     = Product::count();
        $totalCustomers    = Customer::count();

        // Recent activities (user terbaru)
        $recentUsers = \App\Models\User::with('role')
            ->latest()
            ->take(5)
            ->get();

        // Database size (bisa diisi kalau mau hitung)
        $dbSize = 0;

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalTransactions',
            'totalProducts',
            'totalCustomers',
            'recentUsers',
            'dbSize'
        ));
    }
}
