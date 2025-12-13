@extends('layouts.app')

@section('title', 'Laporan Kinerja Bulanan')

@section('content')
<div class="space-y-6">
    <!-- Header + Filter -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Kinerja Bulanan</h1>
            <p class="text-sm text-gray-500">
                Tahun: <span class="font-semibold">{{ $year }}</span>
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

    <!-- Ringkasan Tahunan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <p class="text-sm text-gray-500">Total Pendapatan {{ $year }}</p>
            <p class="mt-2 text-2xl font-bold text-green-600">
                Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500">Total Transaksi {{ $year }}</p>
            <p class="mt-2 text-2xl font-bold text-blue-600">
                {{ number_format($totalTransactions ?? 0, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <p class="text-sm text-gray-500">Total Diskon Diberikan</p>
            <p class="mt-2 text-2xl font-bold text-purple-600">
                Rp {{ number_format($totalDiscount ?? 0, 0, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Tabel Per Bulan -->
    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Rincian Per Bulan</h2>

        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-2 text-left">Bulan</th>
                    <th class="px-4 py-2 text-right">Pendapatan</th>
                    <th class="px-4 py-2 text-right">Biaya</th>
                    <th class="px-4 py-2 text-right">Gaji</th>
                    <th class="px-4 py-2 text-right">Laba / Rugi</th>
                    <th class="px-4 py-2 text-right"># Transaksi</th>
                    <th class="px-4 py-2 text-right">Diskon</th>
                </tr>
            </thead>
            <tbody>
                @forelse($months as $row)
                    @php
                        // Aman untuk data array maupun object (stdClass)
                        $monthNumber       = data_get($row, 'month');
                        $revenue           = (float) data_get($row, 'revenue', 0);
                        $cost              = (float) data_get($row, 'cost', 0);
                        $salaries          = (float) data_get($row, 'salaries', 0);
                        $profit            = (float) data_get($row, 'profit', $revenue - ($cost + $salaries));
                        $transactionsCount = (int)   data_get($row, 'transactions', 0);
                        $discount          = (float) data_get($row, 'discount', 0);

                        $monthName = $monthNumber
                            ? \Carbon\Carbon::create($year, $monthNumber, 1)->translatedFormat('F')
                            : '-';

                        $isProfit = $profit >= 0;
                    @endphp

                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2 font-semibold">{{ $monthName }}</td>
                        <td class="px-4 py-2 text-right">
                            Rp {{ number_format($revenue, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 text-right">
                            Rp {{ number_format($cost, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 text-right">
                            Rp {{ number_format($salaries, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 text-right font-semibold {{ $isProfit ? 'text-green-600' : 'text-red-600' }}">
                            Rp {{ number_format($profit, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 text-right">
                            {{ number_format($transactionsCount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 text-right">
                            Rp {{ number_format($discount, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            Tidak ada data transaksi pada tahun ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Catatan -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <h3 class="font-semibold text-blue-800 mb-1">Catatan Direktur</h3>
        <p class="text-sm text-blue-700">
            Laporan ini merangkum performa toko setiap bulan, meliputi pendapatan,
            biaya, gaji karyawan, jumlah transaksi, serta total diskon yang
            diberikan. Data ini dapat digunakan untuk mengevaluasi tren penjualan,
            efektivitas program diskon, dan efisiensi biaya operasional.
        </p>
    </div>
</div>
@endsection
