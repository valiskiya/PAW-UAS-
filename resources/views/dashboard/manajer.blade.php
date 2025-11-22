@extends('layouts.app')

@section('title', 'Dashboard Manajer Unit')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Manajer Unit</h1>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Total Produk</p>
            <p class="text-2xl font-bold text-blue-600">{{ $totalProducts }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Total Pelanggan</p>
            <p class="text-2xl font-bold text-green-600">{{ $totalCustomers }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Total Karyawan</p>
            <p class="text-2xl font-bold text-purple-600">{{ $totalEmployees }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Pendapatan Hari Ini</p>
            <p class="text-2xl font-bold text-orange-600">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-gray-600 text-sm">Transaksi Hari Ini</p>
            <p class="text-2xl font-bold text-red-600">{{ $todayTransactions }}</p>
        </div>
    </div>
    
    <!-- Alerts -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @if($pendingPOs > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-truck text-blue-600 text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold text-blue-800">Purchase Order Pending</p>
                    <p class="text-blue-600">{{ $pendingPOs }} PO menunggu penerimaan</p>
                </div>
            </div>
        </div>
        @endif
        
        @if($lowStockCount > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold text-yellow-800">Stok Menipis</p>
                    <p class="text-yellow-600">{{ $lowStockCount }} produk perlu restok</p>
                </div>
            </div>
        </div>
        @endif
        
        @if($employeesNearLimit->count() > 0)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-user-clock text-red-600 text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold text-red-800">Peringatan Izin Karyawan</p>
                    <p class="text-red-600">{{ $employeesNearLimit->count() }} karyawan mendekati batas izin</p>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Transaksi Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Kode</th>
                        <th class="px-4 py-2 text-left">Pelanggan</th>
                        <th class="px-4 py-2 text-left">Kasir</th>
                        <th class="px-4 py-2 text-right">Total</th>
                        <th class="px-4 py-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $trx)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $trx->transaction_code }}</td>
                        <td class="px-4 py-2">{{ $trx->customer->name ?? 'Umum' }}</td>
                        <td class="px-4 py-2">{{ $trx->cashier->full_name }}</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $trx->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection