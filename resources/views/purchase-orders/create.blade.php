@extends('layouts.app')

@section('title', 'Buat Purchase Order')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('manajer.purchase-orders.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Buat Purchase Order Baru</h1>
        
        <form method="POST" action="{{ route('manajer.purchase-orders.store') }}" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Supplier *</label>
                    <select name="supplier_id" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Produk *</label>
                    <select name="product_id" id="productSelect" class="w-full px-4 py-2 border rounded-lg" required onchange="updateProductInfo()">
                        <option value="">-- Pilih Produk --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-large-unit="{{ $product->large_unit }}"
                                    data-small-unit="{{ $product->small_unit }}"
                                    data-conversion="{{ $product->conversion_factor }}"
                                    data-purchase-price="{{ $product->purchase_price }}">
                                {{ $product->name }} ({{ $product->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tanggal Order *</label>
                    <input type="date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" 
                           class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Harga Per Unit Kecil *</label>
                    <input type="number" name="unit_price" id="unitPrice" value="{{ old('unit_price', 0) }}" 
                           class="w-full px-4 py-2 border rounded-lg" min="0" step="0.01" required oninput="calculateTotal()">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Jumlah Unit Besar</label>
                    <input type="number" name="quantity_large" id="quantityLarge" value="{{ old('quantity_large', 0) }}" 
                           class="w-full px-4 py-2 border rounded-lg" min="0" required oninput="calculateTotal()">
                    <p class="text-xs text-gray-500 mt-1" id="largeUnitLabel">Unit Besar</p>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Jumlah Unit Kecil</label>
                    <input type="number" name="quantity_small" id="quantitySmall" value="{{ old('quantity_small', 0) }}" 
                           class="w-full px-4 py-2 border rounded-lg" min="0" required oninput="calculateTotal()">
                    <p class="text-xs text-gray-500 mt-1" id="smallUnitLabel">Unit Kecil</p>
                </div>
                
                <div class="md:col-span-2 bg-blue-50 p-4 rounded-lg">
                    <p class="text-blue-800">
                        <strong>Total Harga:</strong><br>
                        <span class="text-2xl font-bold" id="totalPrice">Rp 0</span>
                    </p>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Catatan</label>
                    <textarea name="notes" rows="3" 
                              class="w-full px-4 py-2 border rounded-lg" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('manajer.purchase-orders.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Buat PO
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let selectedProduct = null;

function updateProductInfo() {
    const select = document.getElementById('productSelect');
    const option = select.options[select.selectedIndex];
    
    if (option.value) {
        selectedProduct = {
            largeUnit: option.dataset.largeUnit,
            smallUnit: option.dataset.smallUnit,
            conversion: parseInt(option.dataset.conversion),
            purchasePrice: parseFloat(option.dataset.purchasePrice)
        };
        
        document.getElementById('largeUnitLabel').textContent = selectedProduct.largeUnit;
        document.getElementById('smallUnitLabel').textContent = selectedProduct.smallUnit;
        document.getElementById('unitPrice').value = selectedProduct.purchasePrice;
        
        calculateTotal();
    }
}

function calculateTotal() {
    if (selectedProduct) {
        const quantityLarge = parseInt(document.getElementById('quantityLarge').value) || 0;
        const quantitySmall = parseInt(document.getElementById('quantitySmall').value) || 0;
        const unitPrice = parseFloat(document.getElementById('unitPrice').value) || 0;
        
        const totalSmall = (quantityLarge * selectedProduct.conversion) + quantitySmall;
        const total = totalSmall * unitPrice;
        
        document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
    }
}
</script>
@endpush
@endsection