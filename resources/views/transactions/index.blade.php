@extends('layouts.app')

@section('title', 'Riwayat Transaksi Kasir')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Riwayat Transaksi Saya</h1>
        <span class="text-sm text-gray-500">
            Kasir: <strong>{{ auth()->user()->full_name }}</strong>
        </span>
    </div>

    <!-- Filter Tanggal -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal
                </label>
                <input type="date" name="date" value="{{ request('date') ?? now()->toDateString() }}"
                       class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
        </form>
    </div>

    <!-- Tabel Transaksi -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Kode Transaksi</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Pelanggan</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-semibold">
                            {{ $trx->transaction_code }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $trx->transaction_date->format('d/m/Y') }}
                            <span class="text-xs text-gray-500">
                                {{ $trx->transaction_time }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            {{ $trx->customer->name ?? 'Umum' }}
                        </td>
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
                        <td class="px-4 py-3 text-center space-x-2">
                            <a href="{{ route('kasir.transactions.show', $trx->id) }}"
                               class="text-blue-600 hover:text-blue-800">
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
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            Belum ada transaksi pada tanggal ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        @if(method_exists($transactions, 'links'))
            {{ $transactions->links() }}
        @endif
    </div>
</div>
@endsection
