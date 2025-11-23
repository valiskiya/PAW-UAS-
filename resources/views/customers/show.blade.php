@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('manajer.customers.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <a href="{{ route('manajer.customers.edit', $customer->id) }}" 
           class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $customer->name }}</h1>
                    <p class="text-gray-600">{{ $customer->code }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm {{ 
                    $customer->type === 'wholesale_high' ? 'bg-purple-100 text-purple-800' : 
                    ($customer->type === 'wholesale_low' ? 'bg-blue-100 text-blue-800' : 
                    ($customer->type === 'member' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'))
                }}">
                    {{ ucfirst(str_replace('_', ' ', $customer->type)) }}
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">Telepon</p>
                    <p class="font-semibold">{{ $customer->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Email</p>
                    <p class="font-semibold">{{ $customer->email ?? '-' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-600 text-sm">Alamat</p>
                    <p class="font-semibold">{{ $customer->address ?? '-' }}</p>
                </div>
                @if($customer->member_since)
                <div>
                    <p class="text-gray-600 text-sm">Member Sejak</p>
                    <p class="font-semibold">{{ $customer->member_since->format('d/m/Y') }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Benefit</h3>
            <div class="space-y-3">
                <div class="bg-green-50 p-3 rounded-lg">
                    <p class="text-green-600 text-sm">Diskon</p>
                    <p class="text-2xl font-bold text-green-800">{{ $customer->discount_percentage }}%</p>
                </div>
                @if($customer->free_shipping)
                <div class="bg-blue-50 p-3 rounded-lg">
                    <p class="text-blue-800 font-semibold">
                        <i class="fas fa-truck mr-2"></i>Free Ongkir
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Riwayat Transaksi (10 Terakhir)</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Kode Transaksi</th>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Kasir</th>
                        <th class="px-4 py-2 text-right">Total</th>
                        <th class="px-4 py-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr class="border-b">
                        <td class="px-4 py-3">{{ $trx->transaction_code }}</td>
                        <td class="px-4 py-3">{{ $trx->transaction_date->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">{{ $trx->cashier->full_name }}</td>
                        <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $trx->status }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection