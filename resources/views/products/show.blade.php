@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route(auth()->user()->isManajerUnit() ? 'manajer.products.index' : 'logistik.products.index') }}" 
           class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        
        @if(auth()->user()->isManajerUnit())
        <div class="space-x-2">
            <a href="{{ route('manajer.products.edit', $product->id) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <button onclick="document.getElementById('adjustStockModal').classList.remove('hidden')" 
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-boxes mr-2"></i>Adjust Stok
            </button>
        </div>
        @endif
    </div>
    
    <!-- Product Info Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $product->name }}</h1>
                    <p class="text-gray-600">{{ $product->code }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $product->status }}
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-gray-600 text-sm">Kategori</p>
                    <p class="font-semibold">{{ $product->category }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Konversi Unit</p>
                    <p class="font-semibold">1 {{ $product->large_unit }} = {{ $product->conversion_factor }} {{ $product->small_unit }}</p>
                </div>
            </div>
            
            @if($product->description)
            <div class="mt-4">
                <p class="text-gray-600 text-sm">Deskripsi</p>
                <p class="text-gray-800">{{ $product->description }}</p>
            </div>
            @endif
        </div>
        
        <!-- Stock Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold mb-4">Stok Saat Ini</h3>
            <div class="space-y-3">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <p class="text-blue-600 text-sm">Stok {{ $product->large_unit }}</p>
                    <p class="text-3xl font-bold text-blue-800">{{ $product->stock_large }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-green-600 text-sm">Stok {{ $product->small_unit }}</p>
                    <p class="text-3xl font-bold text-green-800">{{ $product->stock_small }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-gray-600 text-sm">Total ({{ $product->small_unit }})</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $product->total_stock_small }}</p>
                </div>
                
                @if($product->isLowStock())
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3">
                    <p class="text-yellow-800 text-sm">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Peringatan!</strong> Stok di bawah minimum ({{ $product->min_stock }})
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Pricing Table -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Daftar Harga</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Jenis Harga</th>
                        <th class="px-4 py-2 text-right">Harga (Rp)</th>
                        <th class="px-4 py-2 text-right">Margin</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="px-4 py-3">Harga Beli</td>
                        <td class="px-4 py-3 text-right font-semibold">{{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right">-</td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-3">Harga Jual Eceran</td>
                        <td class="px-4 py-3 text-right font-semibold">{{ number_format($product->selling_price_retail, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-green-600">
                            {{ number_format((($product->selling_price_retail - $product->purchase_price) / $product->purchase_price) * 100, 1) }}%
                        </td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-3">Harga Jual Member</td>
                        <td class="px-4 py-3 text-right font-semibold">{{ number_format($product->selling_price_member, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-green-600">
                            {{ number_format((($product->selling_price_member - $product->purchase_price) / $product->purchase_price) * 100, 1) }}%
                        </td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-3">Harga Grosir Rendah</td>
                        <td class="px-4 py-3 text-right font-semibold">{{ number_format($product->selling_price_wholesale_low, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-green-600">
                            {{ number_format((($product->selling_price_wholesale_low - $product->purchase_price) / $product->purchase_price) * 100, 1) }}%
                        </td>
                    </tr>
                    <tr class="border-b">
                        <td class="px-4 py-3">Harga Grosir Tinggi</td>
                        <td class="px-4 py-3 text-right font-semibold">{{ number_format($product->selling_price_wholesale_high, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-green-600">
                            {{ number_format((($product->selling_price_wholesale_high - $product->purchase_price) / $product->purchase_price) * 100, 1) }}%
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Stock Movement History -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold mb-4">Riwayat Pergerakan Stok (20 Terakhir)</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Tipe</th>
                        <th class="px-4 py-2 text-left">Referensi</th>
                        <th class="px-4 py-2 text-right">Perubahan</th>
                        <th class="px-4 py-2 text-right">Stok Akhir</th>
                        <th class="px-4 py-2 text-left">User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockCards as $card)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $card->transaction_date->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full {{ 
                                $card->type === 'in' ? 'bg-green-100 text-green-800' : 
                                ($card->type === 'out' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') 
                            }}">
                                {{ strtoupper($card->type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div>{{ $card->reference_type }}</div>
                            @if($card->notes)
                            <div class="text-xs text-gray-500">{{ $card->notes }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="{{ $card->quantity_change_large > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $card->quantity_change_large > 0 ? '+' : '' }}{{ $card->quantity_change_large }} {{ $product->large_unit }}
                            </div>
                            <div class="text-xs {{ $card->quantity_change_small > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $card->quantity_change_small > 0 ? '+' : '' }}{{ $card->quantity_change_small }} {{ $product->small_unit }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="font-semibold">{{ $card->quantity_after_large }} {{ $product->large_unit }}</div>
                            <div class="text-xs text-gray-600">{{ $card->quantity_after_small }} {{ $product->small_unit }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $card->user->full_name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada riwayat pergerakan stok</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Adjust Stock Modal -->
@if(auth()->user()->isManajerUnit())
<div id="adjustStockModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4">Adjust Stok Produk</h3>
        
        <form method="POST" action="{{ route('manajer.products.adjust-stock', $product->id) }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Tipe Adjustment</label>
                <select name="adjustment_type" class="w-full px-4 py-2 border rounded-lg" required>
                    <option value="add">Tambah Stok</option>
                    <option value="subtract">Kurangi Stok</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Jumlah {{ $product->large_unit }}</label>
                <input type="number" name="quantity_large" value="0" min="0" 
                       class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Jumlah {{ $product->small_unit }}</label>
                <input type="number" name="quantity_small" value="0" min="0" 
                       class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Catatan</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border rounded-lg" 
                          placeholder="Alasan adjustment..."></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('adjustStockModal').classList.add('hidden')"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                    Batal
                </button>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-check mr-2"></i>Adjust
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection