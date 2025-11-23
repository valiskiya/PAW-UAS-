@extends('layouts.app')

@section('title', 'Absensi Karyawan')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Absensi Karyawan</h1>
        <a href="{{ route('manajer.attendances.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Tambah Absensi
        </a>
    </div>
    
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}" 
                   class="px-4 py-2 border rounded-lg">
            
            <select name="employee_id" class="px-4 py-2 border rounded-lg">
                <option value="">Semua Karyawan</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->full_name }}
                    </option>
                @endforeach
            </select>
            
            <select name="status" class="px-4 py-2 border rounded-lg">
                <option value="">Semua Status</option>
                <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="alpha" {{ request('status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
            </select>
            
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>
    
    <!-- Attendance Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Karyawan</th>
                    <th class="px-4 py-3 text-center">Shift</th>
                    <th class="px-4 py-3 text-center">Check In</th>
                    <th class="px-4 py-3 text-center">Check Out</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $attendance->attendance_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $attendance->employee->full_name }}</td>
                    <td class="px-4 py-3 text-center">{{ $attendance->shift->name }}</td>
                    <td class="px-4 py-3 text-center">{{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}</td>
                    <td class="px-4 py-3 text-center">{{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs rounded-full {{ 
                            $attendance->status === 'hadir' ? 'bg-green-100 text-green-800' : 
                            ($attendance->status === 'izin' ? 'bg-yellow-100 text-yellow-800' : 
                            ($attendance->status === 'sakit' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'))
                        }}">
                            {{ ucfirst($attendance->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('manajer.attendances.edit', $attendance->id) }}" class="text-green-600 hover:text-green-800">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">Tidak ada data absensi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $attendances->links() }}
    </div>
</div>
@endsection