@extends('layouts.app')

@section('title', 'Kartu Stok - ' . $product->name)

@section('content')
<div class="space-y-6">
    <div class="flex items-center">
        <a href="{{ auth()->user()->isManajerUnit() ? route('manajer.stock-cards.index') : route('logistik.stock-cards.index') }}" 
           class="text-blue-600 hover:text-blue-800 mr-4">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Kartu Stok: {{ $product->name }}</h1>
    </div>
    
    <!-- Product Info -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-600">Kode Produk</p>
                <p class="font-semibold">{{ $product->code }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Kategori</p>
                <p class="font-semibold">{{ $product->category }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Stok Saat Ini</p>
                <p class="font-semibold text-lg text-green-600">
                    {{ $product->stock_large }} {{ $product->large_unit }}, 
                    {{ $product->stock_small }} {{ $product->small_unit }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Unit Kecil</p>
                <p class="font-semibold text-lg">{{ $product->total_stock_small }} {{ $product->small_unit }}</p>
            </div>
        </div>
    </div>
    
    <!-- Stock Movement History -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-center">Tipe</th>
                    <th class="px-4 py-3 text-left">Referensi</th>
                    <th class="px-4 py-3 text-right">Stok Sebelum</th>
                    <th class="px-4 py-3 text-right">Perubahan</th>
                    <th class="px-4 py-3 text-right">Stok Sesudah</th>
                    <th class="px-4 py-3 text-left">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stockCards as $card)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $card->transaction_date->format('d/m/Y H:i') }}</td>
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
                        {{ $card->quantity_before_large }}L / {{ $card->quantity_before_small }}S
                    </td>
                    <td class="px-4 py-3 text-right text-xs font-bold {{ $card->type === 'in' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $card->quantity_change_large > 0 ? '+' : '' }}{{ $card->quantity_change_large }}L / 
                        {{ $card->quantity_change_small > 0 ? '+' : '' }}{{ $card->quantity_change_small }}S
                    </td>
                    <td class="px-4 py-3 text-right text-xs font-bold">
                        {{ $card->quantity_after_large }}L / {{ $card->quantity_after_small }}S
                    </td>
                    <td class="px-4 py-3 text-xs">{{ $card->notes }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Belum ada pergerakan stok</td>
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