@extends('layouts.app')

@section('title', 'Laporan Laba-Rugi')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Laba-Rugi</h1>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg print:hidden">
            <i class="fas fa-print mr-2"></i>Cetak
        </button>
    </div>
    
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4 print:hidden">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <select name="month" class="px-4 py-2 border rounded-lg">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                    </option>
                @endfor
            </select>
            
            <select name="year" class="px-4 py-2 border rounded-lg">
                @for($i = date('Y'); $i >= date('Y') - 2; $i--)
                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
            
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-filter mr-2"></i>Tampilkan
            </button>
        </form>
    </div>
    
    <!-- Report -->
    <div class="bg-white rounded-lg shadow p-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold">LAPORAN LABA RUGI</h2>
            <p class="text-gray-600">Periode: {{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}</p>
        </div>
        
        <div class="space-y-4">
            <!-- Pendapatan -->
            <div class="border-b pb-4">
                <h3 class="font-bold text-lg mb-3">PENDAPATAN</h3>
                <div class="flex justify-between text-lg">
                    <span>Penjualan</span>
                    <span class="font-semibold">Rp {{ number_format($revenue, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <!-- Harga Pokok Penjualan -->
            <div class="border-b pb-4">
                <h3 class="font-bold text-lg mb-3">HARGA POKOK PENJUALAN</h3>
                <div class="flex justify-between">
                    <span>Harga Pokok Produk</span>
                    <span class="font-semibold text-red-600">Rp {{ number_format($cost, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <!-- Laba Kotor -->
            <div class="border-b pb-4 bg-blue-50 p-4 rounded">
                <div class="flex justify-between text-xl font-bold">
                    <span>LABA KOTOR</span>
                    <span class="text-blue-600">Rp {{ number_format($revenue - $cost, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <!-- Beban Operasional -->
            <div class="border-b pb-4">
                <h3 class="font-bold text-lg mb-3">BEBAN OPERASIONAL</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Beban Gaji Karyawan</span>
                        <span class="font-semibold text-red-600">Rp {{ number_format($salaries, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Total Beban -->
            <div class="border-b pb-4 bg-red-50 p-4 rounded">
                <div class="flex justify-between text-lg font-bold">
                    <span>TOTAL BEBAN</span>
                    <span class="text-red-600">Rp {{ number_format($totalCost, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <!-- Laba Bersih -->
            <div class="bg-green-50 p-6 rounded-lg">
                <div class="flex justify-between text-2xl font-bold">
                    <span>LABA BERSIH</span>
                    <span class="{{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($profit, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .print\:hidden { display: none !important; }
}
</style>
@endsection