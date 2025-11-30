@extends('layouts.app')

@section('title', 'Detail Supplier')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('manajer.suppliers.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Detail Supplier</h1>
        </div>
        <a href="{{ route('manajer.suppliers.edit', $supplier->id) }}" 
           class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Supplier Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Informasi Supplier</h2>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Kode Supplier</p>
                        <p class="font-semibold text-lg">{{ $supplier->code }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Nama Supplier</p>
                        <p class="font-semibold">{{ $supplier->name }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Contact Person</p>
                        <p class="font-semibold">{{ $supplier->contact_person ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Telepon</p>
                        <p class="font-semibold">{{ $supplier->phone }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold">{{ $supplier->email ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Alamat</p>
                        <p class="font-semibold">{{ $supplier->address }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="px-3 py-1 text-sm rounded-full {{ $supplier->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($supplier->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Purchase Orders History -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Riwayat Purchase Order</h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-2 text-left">PO Number</th>
                                <th class="px-4 py-2 text-left">Produk</th>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-right">Total</th>
                                <th class="px-4 py-2 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchaseOrders as $po)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $po->po_number }}</td>
                                <td class="px-4 py-2">{{ $po->product->name }}</td>
                                <td class="px-4 py-2">{{ $po->order_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-2 text-right">Rp {{ number_format($po->total_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 text-center">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $po->status === 'received' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $po->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $po->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($po->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada riwayat purchase order
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection