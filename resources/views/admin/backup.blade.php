@extends('layouts.app')

@section('title', 'Backup Data')

@section('content')
<div class="max-w-5xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Backup & Restore Data</h1>
    
    <!-- Create Backup -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Buat Backup Baru</h2>
        <p class="text-gray-600 mb-4">
            Backup akan mencakup seluruh database sistem termasuk transaksi, produk, dan data karyawan.
        </p>
        <form method="POST" action="{{ route('admin.backup.create') }}">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                <i class="fas fa-database mr-2"></i>Buat Backup Sekarang
            </button>
        </form>
    </div>
    
    <!-- Backup List -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Daftar Backup</h2>
        
        @if(isset($backups) && count($backups) > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama File</th>
                        <th class="px-4 py-3 text-right">Ukuran</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($backups as $backup)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-sm">{{ $backup['name'] }}</td>
                        <td class="px-4 py-3 text-right">{{ number_format($backup['size'] / 1024, 2) }} KB</td>
                        <td class="px-4 py-3">{{ date('d/m/Y H:i', $backup['date']) }}</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('admin.backup.download', $backup['name']) }}" 
                               class="text-blue-600 hover:text-blue-800 mx-2">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-center text-gray-500 py-8">Belum ada backup tersedia</p>
        @endif
    </div>
    
    <!-- Info -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-6">
        <p class="text-yellow-800">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>Penting:</strong> Simpan file backup di lokasi yang aman. Backup otomatis disarankan dilakukan minimal 1x seminggu.
        </p>
    </div>
</div>
@endsection