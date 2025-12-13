@extends('layouts.app')

@section('title', 'Laporan Laba Rugi')

@section('content')
<div class="space-y-6">
    <!-- Header + Filter -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Laba Rugi</h1>
            <p class="text-sm text-gray-500">
                Periode: <span class="font-semibold">{{ $year }}</span>
            </p>
        </div>

        <form method="GET" class="flex items-center gap-3">
            <label class="text-sm text-gray-600">Tahun</label>
            <select name="year"
                    class="px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @for($y = now()->year - 5; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}" {{ (int)$year === $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg">
                Tampilkan
            </button>
        </form>
    </div>

    @php
        $isProfit = $profit >= 0;
    @endphp

    <!-- Ringkasan Angka Besar -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Total Pendapatan Penjualan</p>
            <p class="mt-2 text-2xl font-bold text-green-600">
                Rp {{ number_format($revenue, 0, ',', '.') }}
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <p class="text-sm text-gray-500">Total Biaya</p>
            <p class="mt-2 text-2xl font-bold text-red-600">
                Rp {{ number_format($totalCost, 0, ',', '.') }}
            </p>
            <p class="mt-1 text-xs text-gray-500">
                (Biaya pembelian + gaji karyawan)
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 {{ $isProfit ? 'border-green-600' : 'border-red-600' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Laba / Rugi Bersih</p>
                    <p class="mt-2 text-2xl font-bold {{ $isProfit ? 'text-green-700' : 'text-red-700' }}">
                        Rp {{ number_format($profit, 0, ',', '.') }}
                    </p>
                </div>
                <span class="px-3 py-1 text-xs rounded-full {{ $isProfit ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $isProfit ? 'Laba' : 'Rugi' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Detail Laporan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pendapatan -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Pendapatan</h2>
            <div class="flex justify-between items-center mb-2">
                <span>Pendapatan penjualan bersih</span>
                <span class="font-semibold">
                    Rp {{ number_format($revenue, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Biaya -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Biaya</h2>

            <div class="space-y-2">
                <div class="flex justify-between">
                    <span>Biaya pembelian barang dagang</span>
                    <span class="font-semibold">
                        Rp {{ number_format($cost, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span>Gaji & upah karyawan</span>
                    <span class="font-semibold">
                        Rp {{ number_format($salaries, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="border-t mt-4 pt-3 flex justify-between text-sm">
                <span class="font-semibold text-gray-700">Total Biaya</span>
                <span class="font-bold text-red-600">
                    Rp {{ number_format($totalCost, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Ringkasan Akhir -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Ringkasan</h2>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <p class="text-gray-700">
                Selama tahun <span class="font-semibold">{{ $year }}</span>, toko
                menghasilkan pendapatan sebesar
                <span class="font-semibold">Rp {{ number_format($revenue, 0, ',', '.') }}</span>
                dengan total biaya
                <span class="font-semibold">Rp {{ number_format($totalCost, 0, ',', '.') }}</span>,
                sehingga {{ $isProfit ? 'laba bersih' : 'rugi bersih' }} yang diperoleh adalah
                <span class="font-semibold {{ $isProfit ? 'text-green-700' : 'text-red-700' }}">
                    Rp {{ number_format($profit, 0, ',', '.') }}
                </span>.
            </p>
        </div>
    </div>
</div>
@endsection
