@extends('layouts.app')

@section('title', 'Dashboard Admin TI')

@section('content')
<div class="space-y-6">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard Admin TI</h1>
    
    <!-- System Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <i class="fas fa-users text-blue-600 text-3xl mb-2"></i>
            <p class="text-gray-600">Total Users</p>
            <p class="text-2xl font-bold">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <i class="fas fa-receipt text-green-600 text-3xl mb-2"></i>
            <p class="text-gray-600">Total Transaksi</p>
            <p class="text-2xl font-bold">{{ $totalTransactions }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <i class="fas fa-box text-purple-600 text-3xl mb-2"></i>
            <p class="text-gray-600">Total Produk</p>
            <p class="text-2xl font-bold">{{ $totalProducts }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <i class="fas fa-user-friends text-orange-600 text-3xl mb-2"></i>
            <p class="text-gray-600">Total Pelanggan</p>
            <p class="text-2xl font-bold">{{ $totalCustomers }}</p>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Manajemen Sistem</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.users.index') }}" class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg">
                <i class="fas fa-users-cog text-2xl mr-3"></i>
                <span>Kelola User</span>
            </a>
            <a href="{{ route('admin.backup') }}" class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg">
                <i class="fas fa-database text-2xl mr-3"></i>
                <span>Backup Data</span>
            </a>
            <a href="{{ route('admin.logs') }}" class="flex items-center justify-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-4 px-6 rounded-lg">
                <i class="fas fa-list text-2xl mr-3"></i>
                <span>Log Aktivitas</span>
            </a>
        </div>
    </div>
    
    <!-- Recent Users -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">User Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Username</th>
                        <th class="px-4 py-2 text-left">Nama</th>
                        <th class="px-4 py-2 text-left">Role</th>
                        <th class="px-4 py-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $user)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $user->username }}</td>
                        <td class="px-4 py-2">{{ $user->full_name }}</td>
                        <td class="px-4 py-2">{{ $user->role->display_name }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="px-2 py-1 text-xs rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection