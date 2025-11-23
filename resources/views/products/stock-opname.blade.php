@extends('layouts.app')

@section('title', 'Stock Opname')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Stock Opname</h1>
    
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <p class="text-yellow-800">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Perhatian:</strong> Stock opname adalah proses pencocokan stok fisik dengan stok sistem. 
            Pastikan Anda menghitung dengan teliti sebelum menyimpan.
        </p>
    </div>
    
    <form method="POST" action="{{ route('logistik.stock-opname.save') }}">
        @csrf
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left">Produk</th>
                            <th class="px-4 py-3 text-center">Stok Sistem<br>(Unit Besar)</th>
                            <th class="px-4 py-3 text-center">Stok Sistem<br>(Unit Kecil)</th>
                            <th class="px-4 py-3 text-center">Stok Fisik<br>(Unit Besar)</th>
                            <th class="px-4 py-3 text-center">Stok Fisik<br>(Unit Kecil)</th>
                            <th class="px-4 py-3 text-center">Selisih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $index => $product)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <input type="hidden" name="products[{{ $index }}][id]" value="{{ $product->id }}">
                                <div class="font-semibold">{{ $product->name }}</div>
                                <div class="text-sm text-gray-600">{{ $product->code }}</div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-semibold">{{ $product->stock_large }}</span>
                                <span class="text-xs text-gray-600">{{ $product->large_unit }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-semibold">{{ $product->stock_small }}</span>
                                <span class="text-xs text-gray-600">{{ $product->small_unit }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" 
                                       name="products[{{ $index }}][actual_large]" 
                                       value="{{ $product->stock_large }}"
                                       min="0"
                                       class="w-24 px-2 py-1 border rounded text-center"
                                       onchange="calculateDiff({{ $index }}, {{ $product->conversion_factor }}, {{ $product->stock_large }}, {{ $product->stock_small }})"
                                       id="actual_large_{{ $index }}">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" 
                                       name="products[{{ $index }}][actual_small]" 
                                       value="{{ $product->stock_small }}"
                                       min="0"
                                       class="w-24 px-2 py-1 border rounded text-center"
                                       onchange="calculateDiff({{ $index }}, {{ $product->conversion_factor }}, {{ $product->stock_large }}, {{ $product->stock_small }})"
                                       id="actual_small_{{ $index }}">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span id="diff_{{ $index }}" class="font-bold">0</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="flex justify-end space-x-3">
            <a href="{{ route('logistik.products.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                Batal
            </a>
            <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg"
                    onclick="return confirm('Apakah Anda yakin ingin menyimpan stock opname ini?')">
                <i class="fas fa-save mr-2"></i>Simpan Stock Opname
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function calculateDiff(index, conversionFactor, systemLarge, systemSmall) {
    const actualLarge = parseInt(document.getElementById('actual_large_' + index).value) || 0;
    const actualSmall = parseInt(document.getElementById('actual_small_' + index).value) || 0;
    
    const systemTotal = (systemLarge * conversionFactor) + systemSmall;
    const actualTotal = (actualLarge * conversionFactor) + actualSmall;
    
    const diff = actualTotal - systemTotal;
    const diffElement = document.getElementById('diff_' + index);
    
    diffElement.textContent = (diff > 0 ? '+' : '') + diff;
    
    if (diff > 0) {
        diffElement.className = 'font-bold text-green-600';
    } else if (diff < 0) {
        diffElement.className = 'font-bold text-red-600';
    } else {
        diffElement.className = 'font-bold text-gray-600';
    }
}
</script>
@endpush
@endsection