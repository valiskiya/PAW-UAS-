@extends('layouts.app')

@section('title', 'Daftar Karyawan')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Karyawan</h1>
        <a href="{{ route('manajer.employees.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Tambah Karyawan
        </a>
    </div>
    
    <!-- Search -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="Cari karyawan..." 
                   class="flex-1 px-4 py-2 border rounded-lg">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
        </form>
    </div>
    
    <!-- Employees Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Posisi</th>
                    <th class="px-4 py-3 text-left">Telepon</th>
                    <th class="px-4 py-3 text-center">Strata Gaji</th>
                    <th class="px-4 py-3 text-center">Gaji/Shift</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $employee->employee_code }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $employee->full_name }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                            {{ ucfirst($employee->position) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ $employee->phone }}</td>
                    <td class="px-4 py-3 text-center font-bold">{{ $employee->salary_grade }}</td>
                    <td class="px-4 py-3 text-center font-semibold">
                        Rp {{ number_format($employee->calculateSalaryPerShift(), 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs rounded-full {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $employee->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('manajer.employees.show', $employee->id) }}" class="text-blue-600 hover:text-blue-800 mx-1">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('manajer.employees.edit', $employee->id) }}" class="text-green-600 hover:text-green-800 mx-1">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada data karyawan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $employees->links() }}
    </div>
</div>
@endsection