@extends('layouts.app')

@section('title', 'Dashboard Direktur')

@php $targetAchievementPercent = $targetAchievementPercent ?? 0; @endphp

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Direktur</h1>
            <p class="text-gray-600 text-sm">Ringkasan kinerja toko & laporan strategis</p>
        </div>
        <p class="text-gray-500 text-sm">
            {{ now()->isoFormat('dddd, D MMMM Y') }}
        </p>
    </div>

    {{-- 1. RINGKASAN KINERJA TOKO --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-4 text-white">
            <p class="text-xs uppercase text-blue-100">Omzet Hari Ini</p>
            <h3 class="text-2xl font-bold mt-2">
                Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}
            </h3>
            <p class="text-xs mt-1 opacity-80">Transaksi selesai hari ini</p>
        </div>

        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-4 text-white">
            <p class="text-xs uppercase text-indigo-100">Omzet Minggu Ini</p>
            <h3 class="text-2xl font-bold mt-2">
                Rp {{ number_format($totalRevenueWeek, 0, ',', '.') }}
            </h3>
            <p class="text-xs mt-1 opacity-80">Senin – hari ini</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-4 text-white">
            <p class="text-xs uppercase text-green-100">Omzet Bulan Ini</p>
            <h3 class="text-2xl font-bold mt-2">
                Rp {{ number_format($totalRevenueMonth, 0, ',', '.') }}
            </h3>
            <p class="text-xs mt-1 opacity-80">
                {{ $totalTransactionsMonth }} transaksi
            </p>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow-lg p-4 text-white">
            <p class="text-xs uppercase text-orange-100">Transaksi Hari Ini</p>
            <h3 class="text-2xl font-bold mt-2">
                {{ $totalTransactionsToday }}
            </h3>
            <p class="text-xs mt-1 opacity-80">Total transaksi completed</p>
        </div>
    </div>

    {{-- 2. LAPORAN KEUANGAN & TARGET --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs uppercase text-gray-500">Laba Kotor Bulan Ini</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-2">
                Rp {{ number_format($grossProfitMonth, 0, ',', '.') }}
            </h3>
            <p class="text-xs text-gray-500 mt-1">
                Perkiraan: omzet bulanan - HPP (berdasarkan purchase price produk)
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs uppercase text-gray-500">Biaya Tenaga Kerja Bulan Ini</p>
            <h3 class="text-2xl font-bold text-gray-800 mt-2">
                Rp {{ number_format($labourCostMonth, 0, ',', '.') }}
            </h3>
            <p class="text-xs text-gray-500 mt-1">
                Diambil dari tabel pembayaran gaji (salary payments)
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-xs uppercase text-gray-500">Laba Bersih Sederhana</p>
            <h3 class="text-2xl font-bold text-green-600 mt-2">
                Rp {{ number_format($netProfitMonth, 0, ',', '.') }}
            </h3>
            <p class="text-xs text-gray-500 mt-1">
                Laba kotor - biaya tenaga kerja
            </p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-3">
            <div>
                <p class="text-xs uppercase text-gray-500">Target vs Realisasi Omzet Bulanan</p>
                <h3 class="text-xl font-semibold text-gray-800 mt-1">
                    Target: Rp {{ number_format($targetRevenueMonth, 0, ',', '.') }}
                </h3>
                <p class="text-sm text-gray-600">
                    Realisasi: Rp {{ number_format($totalRevenueMonth, 0, ',', '.') }}
                    @if(!is_null($targetAchievementPercent))
                        ({{ $targetAchievementPercent }}%)
                    @endif
                </p>
            </div>
            <div class="w-full md:w-1/2">
                @if(!is_null($targetAchievementPercent))
                    @php
                        $progress = max(min($targetAchievementPercent, 150), 0);
                    @endphp
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="h-3 rounded-full
                            @if($targetAchievementPercent >= 100)
                                bg-green-500
                            @else
                                bg-blue-500
                            @endif"
                            style="width: {{ $progress > 100 ? 100 : $progress }}%;">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        @if($targetAchievementPercent >= 100)
                            Target tercapai / terlampaui
                        @else
                            Kurang sekitar
                            Rp {{ number_format($targetRevenueMonth - $totalRevenueMonth, 0, ',', '.') }}
                            dari target
                        @endif
                    </p>
                @else
                    <p class="text-xs text-gray-500">Belum ada target omzet yang diset.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- 3. GRAFIK PENJUALAN & PELANGGAN --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Grafik Penjualan 7 Hari --}}
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold mb-4">Grafik Omzet 7 Hari Terakhir</h2>
            <div class="h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        {{-- Ringkasan Pelanggan --}}
        <div class="bg-white rounded-lg shadow p-4 space-y-4">
            <h2 class="text-lg font-semibold">Laporan Pelanggan</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs uppercase text-gray-500">Total Pelanggan Aktif</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalCustomers }}</h3>
                </div>
                <div>
                    <p class="text-xs uppercase text-gray-500">Pelanggan Anggota</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-1">{{ $totalMembers }}</h3>
                    <p class="text-xs text-gray-500">
                        {{ $memberSharePercent }}% dari total customer
                    </p>
                </div>
                <div>
                    <p class="text-xs uppercase text-gray-500">Non-Anggota</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalNonMembers }}</h3>
                    <p class="text-xs text-gray-500">
                        {{ $nonMemberSharePercent }}% dari total customer
                    </p>
                </div>
                <div>
                    <p class="text-xs uppercase text-gray-500">Pelanggan Grosir</p>
                    <h3 class="text-lg font-bold text-green-600 mt-1">
                        Rendah: {{ $wholesaleLowCount }}<br>
                        Tinggi: {{ $wholesaleHighCount }}
                    </h3>
                </div>
            </div>

            <div class="border-t pt-3">
                <p class="text-xs uppercase text-gray-500 mb-1">Pertumbuhan Anggota</p>
                <p class="text-sm text-gray-700">
                    Anggota baru bulan ini: <strong>{{ $newMembersThisMonth }}</strong><br>
                    Anggota baru bulan lalu: <strong>{{ $newMembersLastMonth }}</strong><br>
                    Pelanggan grosir baru bulan ini:
                    <strong>{{ $newWholesaleThisMonth }}</strong>
                </p>
            </div>

            <div class="border-t pt-3">
                <p class="text-xs uppercase text-gray-500 mb-1">Efektivitas Program Diskon</p>
                @php
                    $totalType = $totalRevenueByType ?: 1;
                    $pRetail   = round($revenueRetailMonth / $totalType * 100, 1);
                    $pMember   = round($revenueMemberMonth / $totalType * 100, 1);
                    $pLow      = round($revenueWholesaleLowMonth / $totalType * 100, 1);
                    $pHigh     = round($revenueWholesaleHighMonth / $totalType * 100, 1);
                @endphp
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>Retail / Non-member: 
                        <strong>Rp {{ number_format($revenueRetailMonth, 0, ',', '.') }}</strong>
                        ({{ $pRetail }}%)
                    </li>
                    <li>Anggota perorangan: 
                        <strong>Rp {{ number_format($revenueMemberMonth, 0, ',', '.') }}</strong>
                        ({{ $pMember }}%)
                    </li>
                    <li>Grosir tingkat rendah (diskon 5%): 
                        <strong>Rp {{ number_format($revenueWholesaleLowMonth, 0, ',', '.') }}</strong>
                        ({{ $pLow }}%)
                    </li>
                    <li>Grosir tingkat tinggi (diskon 10% + free ongkir): 
                        <strong>Rp {{ number_format($revenueWholesaleHighMonth, 0, ',', '.') }}</strong>
                        ({{ $pHigh }}%)
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- 4. STOK & INVENTORI --}}
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-semibold mb-4">Laporan Stok & Inventori</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div>
                <p class="text-xs uppercase text-gray-500">Nilai Persediaan (Perkiraan)</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    Rp {{ number_format($inventoryValue, 0, ',', '.') }}
                </h3>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-500">Produk Stok Menipis</p>
                <h3 class="text-2xl font-bold text-orange-600 mt-1">
                    {{ $lowStockCount }}
                </h3>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-500">Stok Mati (&gt; 60 hari tanpa penjualan)</p>
                <h3 class="text-2xl font-bold text-red-600 mt-1">
                    {{ $deadStockCount }}
                </h3>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-500">Skor Kesehatan Stok</p>
                <h3 class="text-2xl font-bold text-green-600 mt-1">
                    {{ $inventoryHealthPercent }}%
                </h3>
            </div>
        </div>

        @if($lowStockProducts->count() > 0)
            <div class="border-t pt-3">
                <p class="text-sm font-semibold mb-2">Daftar Produk Stok Menipis</p>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left">Produk</th>
                                <th class="px-3 py-2 text-left">Kode</th>
                                <th class="px-3 py-2 text-right">Stok</th>
                                <th class="px-3 py-2 text-right">Min Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $product)
                                <tr class="border-b">
                                    <td class="px-3 py-2">{{ $product->name }}</td>
                                    <td class="px-3 py-2 text-xs text-gray-500">{{ $product->code }}</td>
                                    <td class="px-3 py-2 text-right">
                                        {{ $product->stock_large }} {{ $product->large_unit }},
                                        {{ $product->stock_small }} {{ $product->small_unit }}
                                    </td>
                                    <td class="px-3 py-2 text-right">{{ $product->min_stock }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    {{-- 5. SDM / KARYAWAN --}}
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-semibold mb-4">Laporan SDM</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <p class="text-xs uppercase text-gray-500">Total Karyawan Aktif</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalEmployees }}</h3>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-500">Biaya Tenaga Kerja Bulan Ini</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    Rp {{ number_format($labourCostMonth, 0, ',', '.') }}
                </h3>
            </div>
            <div>
                <p class="text-xs uppercase text-gray-500">Ringkasan Absensi Bulan Ini</p>
                <ul class="text-sm text-gray-700 mt-1 space-y-1">
                    @forelse($attendanceSummaryByStatus as $row)
                        <li>
                            <span class="font-semibold">{{ ucfirst($row->status) }}:</span>
                            {{ $row->total }} kejadian
                        </li>
                    @empty
                        <li class="text-gray-500">Belum ada data absensi bulan ini.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- 6. TOP PRODUK --}}
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-semibold mb-4">Top 5 Produk Bulan Ini</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($topProducts as $product)
                <div class="border rounded-lg p-4">
                    <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                    <p class="text-xs text-gray-500 mb-2">{{ $product->code }}</p>
                    <p class="text-sm text-gray-700">
                        Terjual: <strong>{{ $product->transaction_details_count }}</strong> kali transaksi bulan ini
                    </p>
                </div>
            @empty
                <p class="text-gray-500 text-sm">Belum ada data penjualan untuk bulan ini.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json(collect($salesChart)->pluck('date')),
        datasets: [{
            label: 'Omzet (Rp)',
            data: @json(collect($salesChart)->pluck('sales')),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
@endpush
