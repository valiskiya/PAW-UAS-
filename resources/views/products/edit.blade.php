@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('manajer.products.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Produk
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Produk: {{ $product->name }}</h1>
        
        <form method="POST" action="{{ route('manajer.products.update', $product->id) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="border-b pb-4">
                <h2 class="text-lg font-semibold mb-4">Informasi Dasar</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Kode Produk *</label>
                        <input type="text" name="code" value="{{ old('code', $product->code) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nama Produk *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-2">Kategori *</label>
                        <input type="text" name="category" value="{{ old('category', $product->category) }}" 
                               list="categories"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <datalist id="categories">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-2">Deskripsi</label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $product->description) }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Status *</label>
                        <select name="status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Unit & Conversion -->
            <div class="border-b pb-4">
                <h2 class="text-lg font-semibold mb-4">Unit & Konversi</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Unit Besar *</label>
                        <input type="text" name="large_unit" value="{{ old('large_unit', $product->large_unit) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Unit Kecil *</label>
                        <input type="text" name="small_unit" value="{{ old('small_unit', $product->small_unit) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Faktor Konversi *</label>
                        <input type="number" name="conversion_factor" value="{{ old('conversion_factor', $product->conversion_factor) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               min="1" required>
                    </div>
                </div>
            </div>
            
            <!-- Minimum Stock -->
            <div class="border-b pb-4">
                <h2 class="text-lg font-semibold mb-4">Pengaturan Stok</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Minimum Stok *</label>
                        <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               min="0" required>
                    </div>
                    
                    <div class="md:col-span-2 bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Stok Saat Ini:</strong><br>
                            {{ $product->stock_large }} {{ $product->large_unit }}, 
                            {{ $product->stock_small }} {{ $product->small_unit }}<br>
                            <span class="text-xs">(Total: {{ $product->total_stock_small }} {{ $product->small_unit }})</span>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Pricing -->
            <div class="border-b pb-4">
                <h2 class="text-lg font-semibold mb-4">Harga</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Harga Beli *</label>
                        <input type="number" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               min="0" step="0.01" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Harga Jual Eceran *</label>
                        <input type="number" name="selling_price_retail" value="{{ old('selling_price_retail', $product->selling_price_retail) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               min="0" step="0.01" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Harga Jual Member *</label>
                        <input type="number" name="selling_price_member" value="{{ old('selling_price_member', $product->selling_price_member) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               min="0" step="0.01" required>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Harga Grosir Rendah *</label>
                        <input type="number" name="selling_price_wholesale_low" value="{{ old('selling_price_wholesale_low', $product->selling_price_wholesale_low) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               min="0" step="0.01" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 font-semibold mb-2">Harga Grosir Tinggi *</label>
                        <input type="number" name="selling_price_wholesale_high" value="{{ old('selling_price_wholesale_high', $product->selling_price_wholesale_high) }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               min="0" step="0.01" required>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('manajer.products.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection