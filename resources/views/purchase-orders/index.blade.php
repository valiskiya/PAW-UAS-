@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Purchase Orders</h1>
        @if(auth()->user()->isManajerUnit())
        <a href="{{ route('manajer.purchase-orders.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Buat PO
        </a>
        @endif
    </div>
    
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <select name="status" class="px-4 py-2 border rounded-lg">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            
            <select name="supplier_id" class="px-4 py-2 border rounded-lg">
                <option value="">Semua Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
            
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>
    
    <!-- PO Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">PO Number</th>
                    <th class="px-4 py-3 text-left">Tanggal Order</th>
                    <th class="px-4 py-3 text-left">Supplier</th>
                    <th class="px-4 py-3 text-left">Produk</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchaseOrders as $po)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold">{{ $po->po_number }}</td>
                    <td class="px-4 py-3">{{ $po->order_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $po->supplier->name }}</td>
                    <td class="px-4 py-3">
                        <div>{{ $po->product->name }}</div>
                        <div class="text-xs text-gray-500">
                            {{ $po->quantity_large }} {{ $po->product->large_unit }}, 
                            {{ $po->quantity_small }} {{ $po->product->small_unit }}
                        </div>
                    </td>
                    <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($po->total_price, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs rounded-full {{ 
                            $po->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                            ($po->status === 'received' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800')
                        }}">
                            {{ ucfirst($po->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route(auth()->user()->isManajerUnit() ? 'manajer.purchase-orders.show' : 'logistik.purchase-orders.show', $po->id) }}" 
                           class="text-blue-600 hover:text-blue-800 mx-1">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($po->status === 'pending')
                        <form method="POST" action="{{ route(auth()->user()->isManajerUnit() ? 'manajer.purchase-orders.receive' : 'logistik.purchase-orders.receive', $po->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 mx-1" 
                                    onclick="return confirm('Terima barang dari PO ini?')">
                                <i class="fas fa-check-circle"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data PO</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $purchaseOrders->links() }}
    </div>
</div>
@endsection