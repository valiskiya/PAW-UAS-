@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Kasir</h1>
        <a href="{{ route('kasir.pos') }}" 
           class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 transition">
            <i class="fas fa-cash-register text-xl mr-2"></i>
            <span class="text-lg">BUKA POS</span>
        </a>
    </div>
    
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
        <h2 class="text-xl font-semibold mb-4">Menu Cepat</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('kasir.pos') }}" 
               class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-6 px-6 rounded-lg transition duration-300 shadow-lg">
                <i class="fas fa-cash-register text-3xl mr-4"></i>
                <div class="text-left">
                    <div class="text-xl">Buka POS</div>
                    <div class="text-sm opacity-90">Mulai Transaksi Baru</div>
                </div>
            </a>
            
            <a href="{{ route('kasir.transactions.index') }}" 
               class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-6 px-6 rounded-lg transition duration-300 shadow-lg">
                <i class="fas fa-history text-3xl mr-4"></i>
                <div class="text-left">
                    <div class="text-xl">Riwayat Transaksi</div>
                    <div class="text-sm opacity-90">Lihat Transaksi Hari Ini</div>
                </div>
            </a>
        </div>
    </div>
    
    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Transaksi Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left">Kode Transaksi</th>
                        <th class="px-4 py-3 text-left">Pelanggan</th>
                        <th class="px-4 py-3 text-center">Waktu</th>
                        <th class="px-4 py-3 text-right">Total</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $trx)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold">{{ $trx->transaction_code }}</td>
                        <td class="px-4 py-3">{{ $trx->customer->name ?? 'Umum' }}</td>
                        <td class="px-4 py-3 text-center text-sm">{{ $trx->transaction_time }}</td>
                        <td class="px-4 py-3 text-right font-bold text-green-600">
                            Rp {{ number_format($trx->total, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('kasir.transactions.show', $trx->id) }}" 
                               class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('kasir.transactions.print', $trx->id) }}" target="_blank"
                               class="text-green-600 hover:text-green-800">
                                <i class="fas fa-print"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            Belum ada transaksi hari ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Tips -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <div class="flex">
            <i class="fas fa-info-circle text-blue-600 mr-3 mt-1"></i>
            <div class="flex-1">
                <h3 class="font-semibold text-blue-800">Tips Kasir</h3>
                <ul class="text-blue-700 text-sm mt-1 space-y-1">
                    <li>• Selalu cek status member pelanggan untuk memberikan harga yang sesuai</li>
                    <li>• Pastikan stok produk tersedia sebelum melakukan transaksi</li>
                    <li>• Hitung kembalian dengan teliti</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
