@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Produk</h1>
        @if(auth()->user()->isManajerUnit())
        <a href="{{ route('manajer.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Tambah Produk
        </a>
        @endif
    </div>
    
    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." 
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            
            <select name="category" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Kategori</option>
                @foreach(($categories ?? $products->pluck('category')->unique()) as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            
            <select name="status" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
        </form>
    </div>
    
    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-right">Stok</th>
                    <th class="px-4 py-3 text-right">Harga Jual</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="border-b hover:bg-gray-50 {{ $product->isLowStock() ? 'bg-yellow-50' : '' }}">
                    <td class="px-4 py-3">{{ $product->code }}</td>
                    <td class="px-4 py-3 font-semibold">
                        {{ $product->name }}
                        @if($product->isLowStock())
                            <i class="fas fa-exclamation-triangle text-yellow-600 ml-2" title="Stok menipis"></i>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $product->category }}</td>
                    <td class="px-4 py-3 text-right">
                        <div>{{ $product->stock_large }} {{ $product->large_unit }}</div>
                        <div class="text-sm text-gray-600">{{ $product->stock_small }} {{ $product->small_unit }}</div>
                    </td>
                    <td class="px-4 py-3 text-right">Rp {{ number_format($product->selling_price_retail, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs rounded-full {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route(auth()->user()->isManajerUnit() ? 'manajer.products.show' : 'logistik.products.show', $product->id) }}" 
                           class="text-blue-600 hover:text-blue-800 mx-1">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if(auth()->user()->isManajerUnit())
                        <a href="{{ route('manajer.products.edit', $product->id) }}" class="text-green-600 hover:text-green-800 mx-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data produk</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4">
        @if(method_exists($products, 'links'))
            {{ $products->links() }}
        @endif
    </div>
</div>
@endsection