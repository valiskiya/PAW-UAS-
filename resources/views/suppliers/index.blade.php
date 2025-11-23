@extends('layouts.app')

@section('title', 'Daftar Supplier')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Supplier</h1>
        @if(auth()->user()->isManajerUnit())
        <a href="{{ route('manajer.suppliers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Tambah Supplier
        </a>
        @endif
    </div>
    
    <!-- Search -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari supplier..." 
                   class="flex-1 px-4 py-2 border rounded-lg">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
        </form>
    </div>
    
    <!-- Suppliers Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Nama Supplier</th>
                    <th class="px-4 py-3 text-left">Contact Person</th>
                    <th class="px-4 py-3 text-left">Kontak</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $supplier->code }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $supplier->name }}</td>
                    <td class="px-4 py-3">{{ $supplier->contact_person ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <div class="text-sm">{{ $supplier->phone }}</div>
                        <div class="text-xs text-gray-500">{{ $supplier->email ?? '-' }}</div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs rounded-full {{ $supplier->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $supplier->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route(auth()->user()->isManajerUnit() ? 'manajer.suppliers.show' : 'logistik.suppliers.show', $supplier->id) }}" 
                           class="text-blue-600 hover:text-blue-800 mx-1">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if(auth()->user()->isManajerUnit())
                        <a href="{{ route('manajer.suppliers.edit', $supplier->id) }}" class="text-green-600 hover:text-green-800 mx-1">
                            <i class="fas fa-edit"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada data supplier</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $suppliers->links() }}
    </div>
</div>
@endsection