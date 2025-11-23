@extends('layouts.app')

@section('title', 'Daftar Pelanggan')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Pelanggan</h1>
        <a href="{{ route('manajer.customers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Tambah Pelanggan
        </a>
    </div>
    
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari nama/kode/telepon..." 
                   class="px-4 py-2 border rounded-lg">
            
            <select name="type" class="px-4 py-2 border rounded-lg">
                <option value="">Semua Tipe</option>
                <option value="non_member" {{ request('type') == 'non_member' ? 'selected' : '' }}>Non Member</option>
                <option value="member" {{ request('type') == 'member' ? 'selected' : '' }}>Member</option>
                <option value="wholesale_low" {{ request('type') == 'wholesale_low' ? 'selected' : '' }}>Grosir Rendah</option>
                <option value="wholesale_high" {{ request('type') == 'wholesale_high' ? 'selected' : '' }}>Grosir Tinggi</option>
            </select>
            
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
        </form>
    </div>
    
    <!-- Customers Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Tipe</th>
                    <th class="px-4 py-3 text-left">Kontak</th>
                    <th class="px-4 py-3 text-center">Diskon</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $customer->code }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $customer->name }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full {{ 
                            $customer->type === 'wholesale_high' ? 'bg-purple-100 text-purple-800' : 
                            ($customer->type === 'wholesale_low' ? 'bg-blue-100 text-blue-800' : 
                            ($customer->type === 'member' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'))
                        }}">
                            {{ ucfirst(str_replace('_', ' ', $customer->type)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm">{{ $customer->phone ?? '-' }}</div>
                        <div class="text-xs text-gray-500">{{ $customer->email ?? '-' }}</div>
                    </td>
                    <td class="px-4 py-3 text-center font-semibold">
                        {{ $customer->discount_percentage }}%
                        @if($customer->free_shipping)
                            <div class="text-xs text-green-600">Free Ongkir</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs rounded-full {{ $customer->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $customer->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('manajer.customers.show', $customer->id) }}" class="text-blue-600 hover:text-blue-800 mx-1">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('manajer.customers.edit', $customer->id) }}" class="text-green-600 hover:text-green-800 mx-1">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data pelanggan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</div>
@endsection