@extends('layouts.app')

@section('title', 'Dashboard Admin TI')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin TI</h1>
            <p class="text-sm text-gray-500">
                Fokus: manajemen user, konfigurasi sistem, backup, dan monitoring keamanan data.
            </p>
        </div>
        <p class="text-gray-600 text-sm">
            {{ now()->isoFormat('dddd, D MMMM Y') }}
        </p>
    </div>

    <!-- System Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">User aktif</p>
                    <p class="mt-2 text-2xl font-bold text-blue-600">
                        {{ $totalUsers }}
                    </p>
                </div>
                <i class="fas fa-users-cog text-blue-500 text-3xl opacity-80"></i>
            </div>
            <p class="mt-2 text-xs text-gray-500">Akun yang boleh mengakses sistem toko.</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Total transaksi</p>
                    <p class="mt-2 text-2xl font-bold text-green-600">
                        {{ $totalTransactions }}
                    </p>
                </div>
                <i class="fas fa-receipt text-green-500 text-3xl opacity-80"></i>
            </div>
            <p class="mt-2 text-xs text-gray-500">Digunakan untuk cek beban sistem & kebutuhan storage.</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Produk terdaftar</p>
                    <p class="mt-2 text-2xl font-bold text-purple-600">
                        {{ $totalProducts }}
                    </p>
                </div>
                <i class="fas fa-box text-purple-500 text-3xl opacity-80"></i>
            </div>
            <p class="mt-2 text-xs text-gray-500">Data master yang perlu dilindungi dari kehilangan.</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Pelanggan terdaftar</p>
                    <p class="mt-2 text-2xl font-bold text-orange-600">
                        {{ $totalCustomers }}
                    </p>
                </div>
                <i class="fas fa-user-friends text-orange-500 text-3xl opacity-80"></i>
            </div>
            <p class="mt-2 text-xs text-gray-500">Terkait privasi data & perlindungan informasi.</p>
        </div>
    </div>

    <!-- Quick Actions: User & System Management
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Manajemen User & Sistem</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg shadow clickable btn-hover text-sm">
                <i class="fas fa-users-cog text-lg mr-2"></i>
                <span>Kelola User & Role</span>
            </a>

            <a href="{{ route('admin.settings') }}"
               class="flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg shadow clickable btn-hover text-sm">
                <i class="fas fa-cog text-lg mr-2"></i>
                <span>Pengaturan Sistem</span>
            </a>

            <a href="{{ route('admin.backup') }}"
               class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg shadow clickable btn-hover text-sm">
                <i class="fas fa-database text-lg mr-2"></i>
                <span>Backup & Restore</span>
            </a>

            <a href="{{ route('admin.monitoring') }}"
               class="flex items-center justify-center bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg shadow clickable btn-hover text-sm">
                <i class="fas fa-desktop text-lg mr-2"></i>
                <span>Monitoring Sistem</span>
            </a>
        </div>
        <p class="mt-3 text-xs text-gray-500">
            Admin TI bertanggung jawab memastikan hanya user berhak yang memiliki akses,
            konfigurasi sistem konsisten, serta proses backup berjalan rutin.
        </p>
    </div> -->

    <!-- Logs & Security -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Activity / Security Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-800">Aktivitas & Keamanan</h2>
                <a href="{{ route('admin.logs') }}"
                   class="text-xs text-blue-600 hover:underline font-semibold">
                    Lihat semua log
                </a>
            </div>
            <p class="text-sm text-gray-500 mb-3">
                Pantau login, gagal login, dan perubahan data master melalui halaman log aktivitas.
            </p>
            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3 mb-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Status koneksi server</p>
                    <p class="text-sm font-semibold text-green-600">
                        Online (dummy status untuk tampilan, logika bisa ditambahkan nanti)
                    </p>
                </div>
                <i class="fas fa-signal text-green-500 text-2xl"></i>
            </div>
            <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Perkiraan ukuran basis data</p>
                    <p class="text-sm font-semibold text-gray-800">
                        {{ $dbSize > 0 ? $dbSize . ' MB' : 'Belum dihitung' }}
                    </p>
                </div>
                <i class="fas fa-hdd text-gray-500 text-2xl"></i>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">User Terbaru / Perubahan Akun</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Username</th>
                            <th class="px-4 py-2 text-left">Nama</th>
                            <th class="px-4 py-2 text-left">Role</th>
                            <th class="px-4 py-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $user->username }}</td>
                            <td class="px-4 py-2">{{ $user->full_name }}</td>
                            <td class="px-4 py-2">
                                {{ $user->role->display_name ?? $user->role->name ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-center">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                Belum ada data user terbaru.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Info -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
        <h3 class="font-semibold text-yellow-800 mb-1">Catatan untuk Admin TI</h3>
        <ul class="text-sm text-yellow-700 space-y-1">
            <li>• Lakukan backup database secara berkala (minimal mingguan).</li>
            <li>• Pastikan akun yang sudah tidak aktif segera dinonaktifkan.</li>
            <li>• Rutin cek log aktivitas untuk mendeteksi akses yang mencurigakan.</li>
        </ul>
    </div>
</div>
@endsection
