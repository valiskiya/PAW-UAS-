@extends('layouts.app')

@section('title', 'Laporan Harian')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Laporan Transaksi Harian</h1>
    
    <!-- Date Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-gray-700 font-semibold mb-2">Tanggal</label>
                <input type="date" name="date" value="{{ request('date', $date) }}" 
                       class="w-full px-4 py-2 border rounded-lg">
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                <i class="fas fa-search mr-2"></i>Tampilkan
            </button>
            <button type="button" onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">
                <i class="fas fa-print mr-2"></i>Cetak
            </button>
        </form>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600">Total Transaksi</p>
            <p class="text-3xl font-bold text-blue-600">{{ $totalTransactions }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600">Total Pendapatan</p>
            <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600">Total Diskon</p>
            <p class="text-3xl font-bold text-red-600">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</p>
        </div>
    </div>
    
    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Waktu</th>
                    <th class="px-4 py-3 text-left">Pelanggan</th>
                    <th class="px-4 py-3 text-left">Kasir</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-center">Metode</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr class="border-b">
                    <td class="px-4 py-3">{{ $trx->transaction_code }}</td>
                    <td class="px-4 py-3">{{ $trx->transaction_time }}</td>
                    <td class="px-4 py-3">{{ $trx->customer->name ?? 'Umum' }}</td>
                    <td class="px-4 py-3">{{ $trx->cashier->full_name }}</td>
                    <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-center">{{ ucfirst($trx->payment_method) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada transaksi pada tanggal ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection