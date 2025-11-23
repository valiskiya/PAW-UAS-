@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        
        <div class="space-x-2">
            @if(auth()->user()->isKasir())
            <a href="{{ route('kasir.transactions.print', $transaction->id) }}" target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-print mr-2"></i>Cetak
            </a>
            @endif
            
            @if(auth()->user()->isManajerUnit() && $transaction->status === 'completed')
            <button onclick="document.getElementById('returnModal').classList.remove('hidden')"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-undo mr-2"></i>Return
            </button>
            @endif
        </div>
    </div>
    
    <!-- Transaction Info -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $transaction->transaction_code }}</h1>
                <p class="text-gray-600">{{ $transaction->transaction_date->format('d/m/Y H:i') }}</p>
            </div>
            <span class="px-3 py-1 rounded-full {{ 
                $transaction->status === 'completed' ? 'bg-green-100 text-green-800' : 
                ($transaction->status === 'returned' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')
            }}">
                {{ ucfirst($transaction->status) }}
            </span>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
            <div>
                <p class="text-gray-600 text-sm">Pelanggan</p>
                <p class="font-semibold">{{ $transaction->customer->name ?? 'Umum' }}</p>
                @if($transaction->customer)
                    <p class="text-xs text-gray-500">{{ $transaction->customer->type }}</p>
                @endif
            </div>
            <div>
                <p class="text-gray-600 text-sm">Kasir</p>
                <p class="font-semibold">{{ $transaction->cashier->full_name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Metode Pembayaran</p>
                <p class="font-semibold">{{ ucfirst($transaction->payment_method) }}</p>
            </div>
        </div>
    </div>
    
    <!-- Items -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Detail Item</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Produk</th>
                        <th class="px-4 py-2 text-center">Qty</th>
                        <th class="px-4 py-2 text-right">Harga</th>
                        <th class="px-4 py-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->details as $detail)
                    <tr class="border-b">
                        <td class="px-4 py-3">
                            <div class="font-semibold">{{ $detail->product->name }}</div>
                            <div class="text-xs text-gray-500">{{ $detail->product->code }}</div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            {{ $detail->quantity }} {{ $detail->unit === 'large' ? $detail->product->large_unit : $detail->product->small_unit }}
                        </td>
                        <td class="px-4 py-3 text-right">Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-semibold">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Summary -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="space-y-2">
            <div class="flex justify-between text-lg">
                <span>Subtotal:</span>
                <span class="font-semibold">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($transaction->discount_amount > 0)
            <div class="flex justify-between text-red-600">
                <span>Diskon ({{ $transaction->discount_percentage }}%):</span>
                <span class="font-semibold">- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($transaction->shipping_cost > 0)
            <div class="flex justify-between">
                <span>Ongkir:</span>
                <span class="font-semibold">Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between text-2xl font-bold border-t pt-2">
                <span>TOTAL:</span>
                <span class="text-green-600">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between border-t pt-2">
                <span>Bayar:</span>
                <span class="font-semibold">Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Kembali:</span>
                <span class="font-semibold">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Return Modal -->
@if(auth()->user()->isManajerUnit())
<div id="returnModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4 text-red-600">Return Transaksi</h3>
        
        <form method="POST" action="{{ route('manajer.transactions.return', $transaction->id) }}">
            @csrf
            
            <div class="mb-4">
                <p class="text-gray-700 mb-2">
                    <strong>Peringatan:</strong> Proses return akan mengembalikan semua stok produk dan 
                    mengubah status transaksi menjadi "returned".
                </p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Alasan Return *</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border rounded-lg" 
                          placeholder="Jelaskan alasan return..." required></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('returnModal').classList.add('hidden')"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                    Batal
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-undo mr-2"></i>Proses Return
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection