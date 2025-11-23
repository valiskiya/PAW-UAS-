<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\SalaryPayment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function daily(Request $request)
    {
        $date = $request->filled('date') ? $request->date : today();
        
        $transactions = Transaction::with(['customer', 'cashier'])
            ->whereDate('transaction_date', $date)
            ->where('status', 'completed')
            ->get();
        
        $totalRevenue = $transactions->sum('total');
        $totalTransactions = $transactions->count();
        $totalDiscount = $transactions->sum('discount_amount');
        
        return view('reports.daily', compact('transactions', 'date', 'totalRevenue', 'totalTransactions', 'totalDiscount'));
    }
    
    public function stock(Request $request)
    {
        $query = Product::where('status', 'active');
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        if ($request->filled('low_stock')) {
            $query->whereRaw('(stock_large * conversion_factor + stock_small) < min_stock');
        }
        
        $products = $query->orderBy('name')->get();
        $categories = Product::distinct()->pluck('category');
        
        return view('reports.stock', compact('products', 'categories'));
    }
    
    public function sales(Request $request)
    {
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->startOfMonth();
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->endOfMonth();
        
        $topProducts = TransactionDetail::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total) as total_sales'))
            ->whereHas('transaction', function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('transaction_date', [$dateFrom, $dateTo])
                  ->where('status', 'completed');
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_sales', 'desc')
            ->take(20)
            ->get();
        
        return view('reports.sales', compact('topProducts', 'dateFrom', 'dateTo'));
    }
    
    public function profit(Request $request)
    {
        $dateFrom = $request->filled('date_from') ? $request->date_from : now()->startOfMonth();
        $dateTo = $request->filled('date_to') ? $request->date_to : now()->endOfMonth();
        
        $transactions = Transaction::with('details.product')
            ->whereBetween('transaction_date', [$dateFrom, $dateTo])
            ->where('status', 'completed')
            ->get();
        
        $totalRevenue = $transactions->sum('total');
        $totalCost = 0;
        
        foreach ($transactions as $transaction) {
            foreach ($transaction->details as $detail) {
                $quantitySmall = $detail->unit === 'large' 
                    ? $detail->quantity * $detail->product->conversion_factor 
                    : $detail->quantity;
                $totalCost += $quantitySmall * $detail->product->purchase_price;
            }
        }
        
        $profit = $totalRevenue - $totalCost;
        $profitMargin = $totalRevenue > 0 ? ($profit / $totalRevenue) * 100 : 0;
        
        return view('reports.profit', compact('dateFrom', 'dateTo', 'totalRevenue', 'totalCost', 'profit', 'profitMargin'));
    }
    
    public function salary(Request $request)
    {
        $month = $request->filled('month') ? $request->month : now()->month;
        $year = $request->filled('year') ? $request->year : now()->year;
        
        $salaries = SalaryPayment::with('employee')
            ->where('month', $month)
            ->where('year', $year)
            ->get();
        
        $totalSalary = $salaries->sum('total_salary');
        
        return view('reports.salary', compact('salaries', 'month', 'year', 'totalSalary'));
    }
    
    // Direktur reports
    public function labaRugi(Request $request)
    {
        $month = $request->filled('month') ? $request->month : now()->month;
        $year = $request->filled('year') ? $request->year : now()->year;
        
        $transactions = Transaction::with('details.product')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->where('status', 'completed')
            ->get();
        
        $revenue = $transactions->sum('total');
        $cost = 0;
        
        foreach ($transactions as $transaction) {
            foreach ($transaction->details as $detail) {
                $quantitySmall = $detail->unit === 'large' 
                    ? $detail->quantity * $detail->product->conversion_factor 
                    : $detail->quantity;
                $cost += $quantitySmall * $detail->product->purchase_price;
            }
        }
        
        $salaries = SalaryPayment::where('month', $month)
            ->where('year', $year)
            ->sum('total_salary');
        
        $totalCost = $cost + $salaries;
        $profit = $revenue - $totalCost;
        
        return view('reports.laba-rugi', compact('month', 'year', 'revenue', 'cost', 'salaries', 'totalCost', 'profit'));
    }

    public function bulanan(Request $request)
    {
        $year = $request->filled('year') ? (int) $request->year : now()->year;

        $monthly = Transaction::select(
                DB::raw("CAST(EXTRACT(MONTH FROM transaction_date) AS INTEGER) as month"),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as transactions_count'),
                DB::raw('SUM(discount_amount) as total_discount')
            )
            ->whereYear('transaction_date', $year)
            ->where('status', 'completed')
            ->groupBy(DB::raw("CAST(EXTRACT(MONTH FROM transaction_date) AS INTEGER)"))
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            if (isset($monthly[$m])) {
                $months[$m] = $monthly[$m];
            } else {
                $months[$m] = (object) [
                    'month' => $m,
                    'revenue' => 0,
                    'transactions_count' => 0,
                    'total_discount' => 0,
                ];
            }
        }

        $totalRevenue = array_sum(array_map(function ($row) {
            return (float) $row->revenue;
        }, $months));

        $totalTransactions = array_sum(array_map(function ($row) {
            return (int) $row->transactions_count;
        }, $months));

        $totalDiscount = array_sum(array_map(function ($row) {
            return (float) $row->total_discount;
        }, $months));

        return view('reports.bulanan', compact('months', 'year', 'totalRevenue', 'totalTransactions', 'totalDiscount'));
    }
}