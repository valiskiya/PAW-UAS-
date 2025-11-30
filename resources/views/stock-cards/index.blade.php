@extends('layouts.app')

@section('title', 'Kartu Stok')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-800">Kartu Stok</h1>
    
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <select name="product_id" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Produk</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
            
            <select name="type" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Tipe</option>
                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Masuk</option>
                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Keluar</option>
                <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                <option value="conversion" {{ request('type') == 'conversion' ? 'selected' : '' }}>Konversi</option>
            </select>
            
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="Dari Tanggal">
            
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                   placeholder="Sampai Tanggal">
            
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
        </form>
    </div>
    
    <!-- Stock Cards Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Produk</th>
                    <th class="px-4 py-3 text-center">Tipe</th>
                    <th class="px-4 py-3 text-left">Referensi</th>
                    <th class="px-4 py-3 text-right">Stok Sebelum</th>
                    <th class="px-4 py-3 text-right">Perubahan</th>
                    <th class="px-4 py-3 text-right">Stok Sesudah</th>
                    <th class="px-4 py-3 text-left">User</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockCards as $card)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $card->transaction_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $card->product->name }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs rounded-full 
                            {{ $card->type === 'in' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $card->type === 'out' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $card->type === 'adjustment' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $card->type === 'conversion' ? 'bg-blue-100 text-blue-800' : '' }}">
                            {{ ucfirst($card->type) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs">{{ $card->reference_type }}</td>
                    <td class="px-4 py-3 text-right text-xs">
                        <div>{{ $card->quantity_before_large }} {{ $card->product->large_unit }}</div>
                        <div class="text-gray-600">{{ $card->quantity_before_small }} {{ $card->product->small_unit }}</div>
                    </td>
                    <td class="px-4 py-3 text-right text-xs font-bold {{ $card->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                        <div>{{ $card->quantity_change_large > 0 ? '+' : '' }}{{ $card->quantity_change_large }} {{ $card->product->large_unit }}</div>
                        <div>{{ $card->quantity_change_small > 0 ? '+' : '' }}{{ $card->quantity_change_small }} {{ $card->product->small_unit }}</div>
                    </td>
                    <td class="px-4 py-3 text-right text-xs font-bold">
                        <div>{{ $card->quantity_after_large }} {{ $card->product->large_unit }}</div>
                        <div class="text-gray-600">{{ $card->quantity_after_small }} {{ $card->product->small_unit }}</div>
                    </td>
                    <td class="px-4 py-3 text-xs">{{ $card->user->full_name ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada data kartu stok</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4">
        {{ $stockCards->links() }}
    </div>
</div>
@endsection