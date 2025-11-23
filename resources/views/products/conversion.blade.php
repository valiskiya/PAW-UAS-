@extends('layouts.app')

@section('title', 'Konversi Unit')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Konversi Unit Besar ke Unit Kecil</h1>
    
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
        <p class="text-blue-800">
            <i class="fas fa-info-circle mr-2"></i>
            Fitur ini digunakan untuk mengkonversi stok dari unit besar (Karton) menjadi unit kecil (Pcs/Eceran) 
            untuk mempermudah penjualan eceran.
        </p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="{{ route('logistik.conversion.execute') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Pilih Produk</label>
                <select name="product_id" id="productSelect" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required onchange="updateProductInfo()">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" 
                            data-large-unit="{{ $product->large_unit }}"
                            data-small-unit="{{ $product->small_unit }}"
                            data-conversion="{{ $product->conversion_factor }}"
                            data-stock-large="{{ $product->stock_large }}"
                            data-stock-small="{{ $product->stock_small }}">
                        {{ $product->name }} ({{ $product->code }})
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div id="productInfo" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold mb-3">Informasi Produk</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Stok Unit Besar</p>
                        <p class="text-xl font-bold" id="stockLarge">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Stok Unit Kecil</p>
                        <p class="text-xl font-bold" id="stockSmall">-</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600">Konversi</p>
                        <p class="font-semibold" id="conversionInfo">-</p>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Jumlah Unit Besar yang Akan Dikonversi</label>
                <input type="number" name="quantity_large" id="quantityLarge" value="0" min="0" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       required oninput="calculateConversion()">
            </div>
            
            <div class="mb-6 p-4 bg-green-50 rounded-lg">
                <p class="text-green-800">
                    <strong>Hasil Konversi:</strong><br>
                    <span id="conversionResult" class="text-2xl font-bold">0</span> <span id="resultUnit">unit kecil</span>
                </p>
            </div>
            
            <div class="flex justify-end space-x-3">
                <a href="{{ route('logistik.products.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                    <i class="fas fa-exchange-alt mr-2"></i>Konversi
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
            stockLarge: parseInt(option.dataset.stockLarge),
            stockSmall: parseInt(option.dataset.stockSmall)
        };
        
        document.getElementById('productInfo').classList.remove('hidden');
        document.getElementById('stockLarge').textContent = selectedProduct.stockLarge + ' ' + selectedProduct.largeUnit;
        document.getElementById('stockSmall').textContent = selectedProduct.stockSmall + ' ' + selectedProduct.smallUnit;
        document.getElementById('conversionInfo').textContent = '1 ' + selectedProduct.largeUnit + ' = ' + selectedProduct.conversion + ' ' + selectedProduct.smallUnit;
        document.getElementById('resultUnit').textContent = selectedProduct.smallUnit;
        document.getElementById('quantityLarge').max = selectedProduct.stockLarge;
        
        calculateConversion();
    } else {
        document.getElementById('productInfo').classList.add('hidden');
        selectedProduct = null;
    }
}

function calculateConversion() {
    if (selectedProduct) {
        const quantity = parseInt(document.getElementById('quantityLarge').value) || 0;
        const result = quantity * selectedProduct.conversion;
        document.getElementById('conversionResult').textContent = result;
    }
}
</script>
@endpush
@endsection