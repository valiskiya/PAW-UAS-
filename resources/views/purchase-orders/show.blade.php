@extends('layouts.app')

@section('title', 'Detail Purchase Order')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ auth()->user()->isManajerUnit() ? route('manajer.purchase-orders.index') : route('logistik.purchase-orders.index') }}" 
               class="text-blue-600 hover:text-blue-800 mr-4">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Detail Purchase Order</h1>
        </div>
        @if($purchaseOrder->status === 'pending' && auth()->user()->isLogistik())
        <form method="POST" action="{{ route('logistik.purchase-orders.receive', $purchaseOrder->id) }}" class="inline">
            @csrf
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg"
                    onclick="return confirm('Konfirmasi penerimaan barang?')">
                <i class="fas fa-check mr-2"></i>Terima Barang
            </button>
        </form>
        @endif
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- PO Header -->
        <div class="grid grid-cols-2 gap-6 mb-6 pb-6 border-b">
            <div>
                <p class="text-sm text-gray-600">PO Number</p>
                <p class="font-bold text-2xl text-blue-600">{{ $purchaseOrder->po_number }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Status</p>
                <span class="px-3 py-1 text-sm rounded-full inline-block
                    {{ $purchaseOrder->status === 'received' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $purchaseOrder->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $purchaseOrder->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($purchaseOrder->status) }}
                </span>
            </div>
        </div>
        
        <!-- Supplier & Product Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="space-y-3">
                <h3 class="font-semibold text-lg">Informasi Supplier</h3>
                <div>
                    <p class="text-sm text-gray-600">Nama Supplier</p>
                    <p class="font-semibold">{{ $purchaseOrder->supplier->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Telepon</p>
                    <p class="font-semibold">{{ $purchaseOrder->supplier->phone }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Alamat</p>
                    <p class="font-semibold">{{ $purchaseOrder->supplier->address }}</p>
                </div>
            </div>
            
            <div class="space-y-3">
                <h3 class="font-semibold text-lg">Informasi Produk</h3>
                <div>
                    <p class="text-sm text-gray-600">Nama Produk</p>
                    <p class="font-semibold">{{ $purchaseOrder->product->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kode Produk</p>
                    <p class="font-semibold">{{ $purchaseOrder->product->code }}</p>
                </div>
            </div>
        </div>
        
        <!-- Order Details -->
        <div class="border-t pt-6">
            <h3 class="font-semibold text-lg mb-4">Detail Pesanan</h3>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-600">Tanggal Order</p>
                    <p class="font-semibold">{{ $purchaseOrder->order_date->format('d F Y') }}</p>
                </div>
                @if($purchaseOrder->received_date)
                <div>
                    <p class="text-sm text-gray-600">Tanggal Diterima</p>
                    <p class="font-semibold">{{ $purchaseOrder->received_date->format('d F Y') }}</p>
                </div>
                @endif
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-700">Jumlah ({{ $purchaseOrder->product->large_unit }}):</span>
                    <span class="font-bold">{{ $purchaseOrder->quantity_large }} {{ $purchaseOrder->product->large_unit }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Jumlah ({{ $purchaseOrder->product->small_unit }}):</span>
                    <span class="font-bold">{{ $purchaseOrder->quantity_small }} {{ $purchaseOrder->product->small_unit }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-700">Total Unit Kecil:</span>
                    <span class="font-bold">
                        {{ ($purchaseOrder->quantity_large * $purchaseOrder->product->conversion_factor) + $purchaseOrder->quantity_small }} {{ $purchaseOrder->product->small_unit }}
                    </span>
                </div>
                <div class="flex justify-between border-t pt-3">
                    <span class="text-gray-700">Harga per Unit:</span>
                    <span class="font-bold">Rp {{ number_format($purchaseOrder->unit_price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-xl">
                    <span class="font-bold text-gray-700">TOTAL HARGA:</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($purchaseOrder->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        
        <!-- Notes -->
        @if($purchaseOrder->notes)
        <div class="mt-6 border-t pt-6">
            <h3 class="font-semibold text-lg mb-2">Catatan</h3>
            <p class="text-gray-700">{{ $purchaseOrder->notes }}</p>
        </div>
        @endif
        
        <!-- Received By -->
        @if($purchaseOrder->receivedBy)
        <div class="mt-6 border-t pt-6">
            <p class="text-sm text-gray-600">Diterima oleh</p>
            <p class="font-semibold">{{ $purchaseOrder->receivedBy->full_name }}</p>
        </div>
        @endif
    </div>
</div>
@endsection