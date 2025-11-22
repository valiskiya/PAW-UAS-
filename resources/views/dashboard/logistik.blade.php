@extends('layouts.app')

@section('title', 'Dashboard Logistik')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Logistik</h1>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <i class="fas fa-box text-blue-600 text-3xl mb-2"></i>
            <p class="text-gray-600">Total Produk</p>
            <p class="text-2xl font-bold">{{ $totalProducts }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <i class="fas fa-exclamation-triangle text-yellow-600 text-3xl mb-2"></i>
            <p class="text-gray-600">Stok Kritis</p>
            <p class="text-2xl font-bold">{{ $criticalStock }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <i class="fas fa-truck-loading text-green-600 text-3xl mb-2"></i>
            <p class="text-gray-600">PO Pending</p>
            <p class="text-2xl font-bold">{{ $pendingPOs->count() }}</p>
        </div>
    </div>
    
    <!-- Pending POs -->
    @if($pendingPOs->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Purchase Order Pending</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">PO Number</th>
                        <th class="px-4 py-2 text-left">Supplier</th>
                        <th class="px-4 py-2 text-left">Produk</th>
                        <th class="px-4 py-2 text-left">Jumlah</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingPOs as $po)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $po->po_number }}</td>
                        <td class="px-4 py-2">{{ $po->supplier->name }}</td>
                        <td class="px-4 py-2">{{ $po->product->name }}</td>
                        <td class="px-4 py-2">{{ $po->quantity_large }} {{ $po->product->large_unit }}</td>
                        <td class="px-4 py-2 text-center">
                            <form method="POST" action="{{ route('logistik.purchase-orders.receive', $po->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                    Terima
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    
    <!-- Low Stock Products -->
    @if($lowStockProducts->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Produk Stok Menipis</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($lowStockProducts as $product)
            <div class="border rounded-lg p-4">
                <p class="font-semibold">{{ $product->name }}</p>
                <p class="text-sm text-gray-600">{{ $product->code }}</p>
                <p class="text-red-600 font-bold mt-2">
                    Stok: {{ $product->stock_large }} {{ $product->large_unit }}, {{ $product->stock_small }} {{ $product->small_unit }}
                </p>
                <p class="text-xs text-gray-500">Min: {{ $product->min_stock }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection