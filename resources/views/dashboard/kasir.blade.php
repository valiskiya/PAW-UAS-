@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Kasir</h1>
    
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <i class="fas fa-cash-register text-4xl mb-3 opacity-75"></i>
            <p class="text-green-100">Transaksi Hari Ini</p>
            <h3 class="text-3xl font-bold">{{ $todayTransactions }}</h3>
        </div>
        
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <i class="fas fa-money-bill-wave text-4xl mb-3 opacity-75"></i>
            <p class="text-blue-100">Total Pendapatan Hari Ini</p>
            <h3 class="text-2xl font-bold">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <i class="fas fa-users text-4xl mb-3 opacity-75"></i>
            <p class="text-purple-100">Total Member</p>
            <h3 class="text-3xl font-bold">{{ $totalMembers }}</h3>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('kasir.pos') }}" class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300">
                <i class="fas fa-cash-register text-2xl mr-3"></i>
                <span>Buka POS / Kasir</span>
            </a>
            <a href="{{ route('kasir.transactions.index') }}" class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg transition duration-300">
                <i class="fas fa-history text-2xl mr-3"></i>
                <span>Riwayat Transaksi</span>
            </a>
        </div>
    </div>
    
    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Transaksi Terbaru</h2>
        <div class="space-y-3">
            @forelse($recentTransactions as $trx)
            <div class="flex justify-between items-center border-b pb-3">
                <div>
                    <p class="font-semibold">{{ $trx->transaction_code }}</p>
                    <p class="text-sm text-gray-600">{{ $trx->customer->name ?? 'Umum' }}</p>
                    <p class="text-xs text-gray-500">{{ $trx->transaction_time }}</p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-green-600">Rp {{ number_format($trx->total, 0, ',', '.') }}</p>
                    <a href="{{ route('kasir.transactions.show', $trx->id) }}" class="text-sm text-blue-600 hover:underline">Detail</a>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-8">Belum ada transaksi hari ini</p>
            @endforelse
        </div>
    </div>
</div>
@endsection