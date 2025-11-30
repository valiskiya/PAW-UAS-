@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-800">Daftar Transaksi</h1>
    
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="Dari Tanggal">
            
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="Sampai Tanggal">
            
            <select name="status" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
            </select>
            
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari kode transaksi..." 
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
        </form>
    </div>
    
    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Kode Transaksi</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Pelanggan</th>
                    <th class="px-4 py-3 text-left">Kasir</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold">{{ $trx->transaction_code }}</td>
                    <td class="px-4 py-3">{{ $trx->transaction_date->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3">{{ $trx->customer->name ?? 'Umum' }}</td>
                    <td class="px-4 py-3">{{ $trx->cashier->full_name }}</td>
                    <td class="px-4 py-3 text-right font-bold text-green-600">
                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $trx->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $trx->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $trx->status === 'returned' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                            {{ ucfirst($trx->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('manajer.transactions.show', $trx->id) }}" 
                           class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
@endsection