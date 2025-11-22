@extends('layouts.app')

@section('title', 'Dashboard Direktur')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Direktur</h1>
        <p class="text-gray-600">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
    </div>
    
    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-blue-100 text-sm">Pendapatan Hari Ini</p>
                    <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}</h3>
                </div>
                <i class="fas fa-money-bill-wave text-3xl text-blue-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-green-100 text-sm">Pendapatan Bulan Ini</p>
                    <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($totalRevenueMonth, 0, ',', '.') }}</h3>
                </div>
                <i class="fas fa-chart-line text-3xl text-green-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-purple-100 text-sm">Transaksi Hari Ini</p>
                    <h3 class="text-2xl font-bold mt-2">{{ $totalTransactionsToday }}</h3>
                </div>
                <i class="fas fa-receipt text-3xl text-purple-200"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-orange-100 text-sm">Transaksi Bulan Ini</p>
                    <h3 class="text-2xl font-bold mt-2">{{ $totalTransactionsMonth }}</h3>
                </div>
                <i class="fas fa-shopping-cart text-3xl text-orange-200"></i>
            </div>
        </div>
    </div>
    
    <!-- Charts & Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Grafik Penjualan 7 Hari Terakhir</h2>
            <canvas id="salesChart" height="200"></canvas>
        </div>
        
        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Top 5 Produk Bulan Ini</h2>
            <div class="space-y-3">
                @forelse($topProducts as $product)
                    <div class="flex justify-between items-center border-b pb-2">
                        <div>
                            <p class="font-semibold">{{ $product->name }}</p>
                            <p class="text-sm text-gray-600">{{ $product->transaction_details_count }} transaksi</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Belum ada data</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Low Stock Alert -->
    @if($lowStockProducts->count() > 0)
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
        <div class="flex">
            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-1"></i>
            <div class="flex-1">
                <h3 class="font-semibold text-yellow-800">Peringatan Stok Menipis</h3>
                <p class="text-yellow-700 text-sm mt-1">{{ $lowStockProducts->count() }} produk memiliki stok di bawah minimum</p>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json(collect($salesChart)->pluck('date')),
        datasets: [{
            label: 'Penjualan (Rp)',
            data: @json(collect($salesChart)->pluck('sales')),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endpush
@endsection