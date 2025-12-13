<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\PurchaseOrder;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
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
                abort(403, 'Role "' . $role . '" tidak dikenali. Silakan hubungi Admin TI.');
        }
    }

    /**
     * Dashboard Direktur
     */
    public function direkturDashboard()
    {
        $today        = Carbon::today();
        $now          = Carbon::now();
        $startOfWeek  = $now->copy()->startOfWeek();   // Senin
        $startOfMonth = $now->copy()->startOfMonth();
        $thisMonth    = (int) $now->format('m');
        $thisYear     = (int) $now->format('Y');

        /**
         * 1. RINGKASAN KINERJA TOKO
         */
        // Omzet hari / minggu / bulan
        $totalRevenueToday = Transaction::whereDate('transaction_date', $today)
            ->where('status', 'completed')
            ->sum('total');

        $totalRevenueWeek = Transaction::whereBetween('transaction_date', [$startOfWeek, $today])
            ->where('status', 'completed')
            ->sum('total');

        $totalRevenueMonth = Transaction::whereBetween('transaction_date', [$startOfMonth, $today])
            ->where('status', 'completed')
            ->sum('total');

        // Jumlah transaksi
        $totalTransactionsToday = Transaction::whereDate('transaction_date', $today)
            ->where('status', 'completed')
            ->count();

        $totalTransactionsMonth = Transaction::whereBetween('transaction_date', [$startOfMonth, $today])
            ->where('status', 'completed')
            ->count();

        // Laba kotor (omzet - HPP)
        $monthDetails = TransactionDetail::with(['product', 'transaction'])
            ->whereHas('transaction', function ($q) use ($startOfMonth, $today) {
                $q->whereBetween('transaction_date', [$startOfMonth, $today])
                  ->where('status', 'completed');
            })
            ->get();

        $cogsMonth = $monthDetails->sum(function ($detail) {
            if (! $detail->product) {
                return 0;
            }

            $product    = $detail->product;
            $conversion = $product->conversion_factor ?: 1;

            // Asumsi purchase_price = harga per unit kecil
            $quantitySmall = $detail->unit === 'large'
                ? $detail->quantity * $conversion
                : $detail->quantity;

            return $quantitySmall * (float) $product->purchase_price;
        });

        $grossProfitMonth = $totalRevenueMonth - $cogsMonth;

        // Biaya tenaga kerja bulan ini
        // NOTE: sementara diset 0 supaya tidak error kolom (silakan sesuaikan nanti dengan struktur tabel salary_payments-mu)
        $labourCostMonth = 0;

        // Laba bersih sederhana
        $netProfitMonth = $grossProfitMonth - $labourCostMonth;

        // Target vs realisasi omzet bulanan
        $targetRevenueMonth = (float) config('onemart.target_monthly_revenue', 50000000); // default 50 jt
        if ($targetRevenueMonth > 0) {
            $targetAchievementPercent = round(($totalRevenueMonth / $targetRevenueMonth) * 100, 1);
        } else {
            // supaya selalu terdefinisi
            $targetAchievementPercent = 0;
        }

        // Grafik omzet 7 hari terakhir
        $salesChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date  = $today->copy()->subDays($i);
            $sales = Transaction::whereDate('transaction_date', $date)
                ->where('status', 'completed')
                ->sum('total');

            $salesChart[] = [
                'date'  => $date->format('d M'),
                'sales' => $sales,
            ];
        }

        /**
         * 2. STOK & INVENTORI
         */
        $totalActiveProducts = Product::where('status', 'active')->count();

        // Produk stok menipis
        $lowStockProducts = Product::whereRaw('(stock_large * conversion_factor + stock_small) < min_stock')
            ->where('status', 'active')
            ->get();
        $lowStockCount = $lowStockProducts->count();

        // Stok mati: 60 hari tidak terjual
        $cutoffDead = $today->copy()->subDays(60);

        $deadStockCount = Product::where('status', 'active')
            ->whereDoesntHave('transactionDetails.transaction', function ($q) use ($cutoffDead) {
                $q->where('status', 'completed')
                  ->whereDate('transaction_date', '>=', $cutoffDead);
            })
            ->count();

        // Nilai persediaan (stok * purchase_price per unit kecil)
        $inventoryValue = Product::where('status', 'active')->get()->sum(function ($product) {
            $totalSmall = ($product->stock_large * $product->conversion_factor) + $product->stock_small;
            return $totalSmall * (float) $product->purchase_price;
        });

        // Skor kesehatan stok
        $healthyProducts = 0;
        if ($totalActiveProducts > 0) {
            $healthyProducts = Product::where('status', 'active')
                ->whereRaw('(stock_large * conversion_factor + stock_small) >= min_stock')
                ->whereHas('transactionDetails.transaction', function ($q) use ($cutoffDead) {
                    $q->where('status', 'completed')
                      ->whereDate('transaction_date', '>=', $cutoffDead);
                })
                ->count();
        }

        $inventoryHealthPercent = $totalActiveProducts > 0
            ? round(($healthyProducts / $totalActiveProducts) * 100, 1)
            : 0;

        /**
         * 3. PELANGGAN & DISKON
         */
        $totalCustomers = Customer::where('status', 'active')->count();

        $memberTypes     = ['member', 'wholesale_low', 'wholesale_high'];
        $totalMembers    = Customer::where('status', 'active')->whereIn('type', $memberTypes)->count();
        $totalNonMembers = max($totalCustomers - $totalMembers, 0);

        $memberSharePercent    = $totalCustomers > 0 ? round(($totalMembers / $totalCustomers) * 100, 1) : 0;
        $nonMemberSharePercent = $totalCustomers > 0 ? round(($totalNonMembers / $totalCustomers) * 100, 1) : 0;

        $wholesaleLowCount = Customer::where('status', 'active')
            ->where('type', 'wholesale_low')
            ->count();

        $wholesaleHighCount = Customer::where('status', 'active')
            ->where('type', 'wholesale_high')
            ->count();

        // Pertumbuhan anggota (bulan ini vs bulan lalu)
        $lastMonth = $now->copy()->subMonthNoOverflow();

        $newMembersThisMonth = Customer::whereIn('type', $memberTypes)
            ->whereMonth('created_at', $thisMonth)
            ->whereYear('created_at', $thisYear)
            ->count();

        $newMembersLastMonth = Customer::whereIn('type', $memberTypes)
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        $newWholesaleThisMonth = Customer::whereIn('type', ['wholesale_low', 'wholesale_high'])
            ->whereMonth('created_at', $thisMonth)
            ->whereYear('created_at', $thisYear)
            ->count();

        // Efektivitas program diskon (kontribusi omzet per tipe customer)
        $baseQueryByType = Transaction::where('status', 'completed')
            ->whereBetween('transaction_date', [$startOfMonth, $today]);

        $revenueRetailMonth = (clone $baseQueryByType)
            ->where(function ($q) {
                $q->whereNull('customer_id')
                  ->orWhereDoesntHave('customer')
                  ->orWhereHas('customer', function ($qc) {
                      $qc->where('type', 'non_member');
                  });
            })
            ->sum('total');

        $revenueMemberMonth = (clone $baseQueryByType)
            ->whereHas('customer', function ($q) {
                $q->where('type', 'member');
            })
            ->sum('total');

        $revenueWholesaleLowMonth = (clone $baseQueryByType)
            ->whereHas('customer', function ($q) {
                $q->where('type', 'wholesale_low');
            })
            ->sum('total');

        $revenueWholesaleHighMonth = (clone $baseQueryByType)
            ->whereHas('customer', function ($q) {
                $q->where('type', 'wholesale_high');
            })
            ->sum('total');

        $totalRevenueByType = $revenueRetailMonth + $revenueMemberMonth
            + $revenueWholesaleLowMonth + $revenueWholesaleHighMonth;

        /**
         * 4. SDM
         */
        $totalEmployees = Employee::where('status', 'active')->count();

        $attendanceSummaryByStatus = Attendance::select('status', DB::raw('count(*) as total'))
            ->whereMonth('attendance_date', $thisMonth)
            ->whereYear('attendance_date', $thisYear)
            ->groupBy('status')
            ->get();

        /**
         * 5. Top Produk Bulan Ini
         */
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

        return view('dashboard.direktur', compact(
            // Ringkasan kinerja toko
            'totalRevenueToday',
            'totalRevenueWeek',
            'totalRevenueMonth',
            'totalTransactionsToday',
            'totalTransactionsMonth',
            'grossProfitMonth',
            'netProfitMonth',
            'labourCostMonth',
            'targetRevenueMonth',
            'targetAchievementPercent',
            'salesChart',
            // Stok & inventori
            'inventoryValue',
            'lowStockProducts',
            'lowStockCount',
            'deadStockCount',
            'inventoryHealthPercent',
            // Pelanggan & diskon
            'totalCustomers',
            'totalMembers',
            'totalNonMembers',
            'memberSharePercent',
            'nonMemberSharePercent',
            'wholesaleLowCount',
            'wholesaleHighCount',
            'newMembersThisMonth',
            'newMembersLastMonth',
            'newWholesaleThisMonth',
            'revenueRetailMonth',
            'revenueMemberMonth',
            'revenueWholesaleLowMonth',
            'revenueWholesaleHighMonth',
            'totalRevenueByType',
            // SDM
            'totalEmployees',
            'attendanceSummaryByStatus',
            // Produk
            'topProducts'
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

        $todayTransactions = Transaction::where('cashier_id', $user->id)
            ->whereDate('transaction_date', $today)
            ->where('status', 'completed')
            ->count();

        $todayRevenue = Transaction::where('cashier_id', $user->id)
            ->whereDate('transaction_date', $today)
            ->where('status', 'completed')
            ->sum('total');

        $recentTransactions = Transaction::with(['customer'])
            ->where('cashier_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

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

        $pendingPOs = PurchaseOrder::with(['supplier', 'product'])
            ->where('status', 'pending')
            ->get();

        $lowStockProducts = Product::whereRaw('(stock_large * conversion_factor + stock_small) < min_stock')
            ->where('status', 'active')
            ->get();

        $todayReceived = PurchaseOrder::with(['supplier', 'product'])
            ->whereDate('received_date', $today)
            ->where('status', 'received')
            ->get();

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
        $totalUsers        = \App\Models\User::where('status', 'active')->count();
        $totalTransactions = Transaction::count();
        $totalProducts     = Product::count();
        $totalCustomers    = Customer::count();

        $recentUsers = \App\Models\User::with('role')
            ->latest()
            ->take(5)
            ->get();

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
