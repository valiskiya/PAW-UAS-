@extends('layouts.app')

@section('title', 'Laporan Stok')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Stok Barang</h1>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-print mr-2"></i>Cetak
        </button>
    </div>
    
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4 print:hidden">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <select name="category" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            
            <div class="flex items-center">
                <input type="checkbox" name="low_stock" value="1" {{ request('low_stock') ? 'checked' : '' }} 
                       class="mr-2" id="low_stock">
                <label for="low_stock" class="text-sm">Tampilkan hanya stok menipis</label>
            </div>
            
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>
    
    <!-- Stock Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Nama Produk</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-right">Stok</th>
                    <th class="px-4 py-3 text-right">Min. Stok</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="border-b {{ $product->isLowStock() ? 'bg-yellow-50' : '' }}">
                    <td class="px-4 py-3">{{ $product->code }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $product->name }}</td>
                    <td class="px-4 py-3">{{ $product->category }}</td>
                    <td class="px-4 py-3 text-right">
                        <div>{{ $product->stock_large }} {{ $product->large_unit }}</div>
                        <div class="text-sm text-gray-600">{{ $product->stock_small }} {{ $product->small_unit }}</div>
                        <div class="text-xs text-gray-500">({{ $product->total_stock_small }} {{ $product->small_unit }})</div>
                    </td>
                    <td class="px-4 py-3 text-right">{{ $product->min_stock }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($product->isLowStock())
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-exclamation-triangle"></i> Low Stock
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle"></i> OK
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
@media print {
    .print\:hidden { display: none !important; }
}
</style>
@endsection