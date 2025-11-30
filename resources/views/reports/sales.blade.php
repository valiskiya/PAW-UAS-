@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Penjualan Top Produk</h1>
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
    
    <!-- Top Products -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-center">#</th>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Nama Produk</th>
                    <th class="px-4 py-3 text-right">Qty Terjual</th>
                    <th class="px-4 py-3 text-right">Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $index => $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 text-center font-bold text-lg">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">{{ $item->product->code }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $item->product->name }}</td>
                    <td class="px-4 py-3 text-right font-bold text-blue-600">{{ $item->total_qty }}</td>
                    <td class="px-4 py-3 text-right font-bold text-green-600">
                        Rp {{ number_format($item->total_sales, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection