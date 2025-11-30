@extends('layouts.app')

@section('title', 'Laporan Profit')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Profit & Margin</h1>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg print:hidden">
            <i class="fas fa-print mr-2"></i>Cetak
        </button>
    </div>
    
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4 print:hidden">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="date" name="date_from" value="{{ request('date_from', $dateFrom) }}" 
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="date" name="date_to" value="{{ request('date_to', $dateTo) }}" 
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Total Pendapatan</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Total HPP</p>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalCost, 0, ',', '.') }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Profit</p>
            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($profit, 0, ',', '.') }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Margin Profit</p>
            <p class="text-2xl font-bold text-purple-600">{{ number_format($profitMargin, 2) }}%</p>
        </div>
    </div>
    
    <!-- Chart -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Visualisasi</h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded">
            <div class="text-center">
                <div class="text-6xl mb-4">
                    <i class="fas fa-chart-pie text-blue-600"></i>
                </div>
                <p class="text-gray-600">Grafik profit akan ditampilkan di sini</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .print\:hidden { display: none !important; }
}
</style>
@endsection 