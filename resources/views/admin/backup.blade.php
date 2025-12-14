@extends('layouts.app')

@section('title', 'Backup Data')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Backup & Restore Data</h1>
            <p class="text-sm text-gray-500">
                Kelola backup basis data sistem untuk menjaga keamanan dan ketersediaan informasi.
            </p>
        </div>
        <p class="text-gray-600 text-sm">
            {{ now()->isoFormat('dddd, D MMMM Y') }}
        </p>
    </div>

    <!-- Create Backup Card -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 mb-1">Buat Backup Baru</h2>
                <p class="text-sm text-gray-600">
                    Backup akan mencakup seluruh database (transaksi, produk, pelanggan, karyawan, dan konfigurasi).
                    Disarankan dilakukan secara berkala (minimal 1x seminggu).
                </p>
            </div>
            <form method="POST" action="{{ route('admin.backup.create') }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow clickable btn-hover">
                    <i class="fas fa-database mr-2 text-lg"></i>
                    <span>Buat Backup Sekarang</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Backup List -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Backup Tersimpan</h2>
            @if(isset($backups) && count($backups) > 0)
                @php
                    $latest = collect($backups)->sortByDesc('date')->first();
                @endphp
                <div class="bg-green-50 border border-green-200 text-xs md:text-sm text-green-800 px-3 py-2 rounded">
                    Backup terakhir: <strong>{{ date('d/m/Y H:i', $latest['date']) }}</strong>
                </div>
            @endif
        </div>

        @if(isset($backups) && count($backups) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama File</th>
                        <th class="px-4 py-3 text-right">Ukuran</th>
                        <th class="px-4 py-3 text-left">Tanggal Dibuat</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($backups as $backup)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs md:text-sm">
                            {{ $backup['name'] }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            {{ number_format($backup['size'] / 1024, 2) }} KB
                        </td>
                        <td class="px-4 py-3">
                            {{ date('d/m/Y H:i', $backup['date']) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('admin.backup.download', $backup['name']) }}"
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs md:text-sm font-semibold mx-1">
                                <i class="fas fa-download mr-1"></i> Download
                            </a>
                            {{-- Kalau nanti mau ada fitur hapus backup, tombolnya bisa ditambahkan di sini --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="py-10 text-center text-gray-500 text-sm">
            Belum ada file backup yang tersimpan. 
            <br>
            <span class="text-gray-400 text-xs">Tekan tombol “Buat Backup Sekarang” untuk membuat backup pertama.</span>
        </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-3"></i>
            <div class="text-sm text-yellow-800 space-y-1">
                <p class="font-semibold">Catatan Penting Backup:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Simpan file backup di lokasi yang aman (external storage atau cloud).</li>
                    <li>Jangan menyimpan satu-satunya backup di server yang sama dengan aplikasi.</li>
                    <li>Lakukan uji restore secara berkala untuk memastikan file backup masih valid.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
