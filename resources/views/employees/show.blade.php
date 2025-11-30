@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('manajer.employees.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Detail Karyawan</h1>
        </div>
        <a href="{{ route('manajer.employees.edit', $employee->id) }}" 
           class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Employee Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center mb-4">
                    <div class="w-24 h-24 bg-blue-100 rounded-full mx-auto flex items-center justify-center">
                        <i class="fas fa-user text-4xl text-blue-600"></i>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Kode Karyawan</p>
                        <p class="font-semibold text-lg">{{ $employee->employee_code }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="font-semibold">{{ $employee->full_name }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Posisi</p>
                        <p class="font-semibold">{{ ucfirst($employee->position) }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Telepon</p>
                        <p class="font-semibold">{{ $employee->phone }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Alamat</p>
                        <p class="font-semibold">{{ $employee->address }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Masuk</p>
                        <p class="font-semibold">{{ $employee->hire_date->format('d/m/Y') }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Strata Gaji</p>
                        <p class="font-semibold">Strata {{ $employee->salary_grade }}</p>
                        <p class="text-sm text-green-600">Rp {{ number_format($employee->calculateSalaryPerShift(), 0, ',', '.') }}/shift</p>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="px-3 py-1 text-sm rounded-full {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($employee->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Attendance & Salary History -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Recent Attendances -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Absensi Terbaru</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-left">Shift</th>
                                <th class="px-4 py-2 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->attendances()->latest('attendance_date')->take(10)->get() as $att)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $att->attendance_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $att->shift->name }}</td>
                                <td class="px-4 py-2 text-center">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $att->status === 'hadir' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $att->status === 'izin' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $att->status === 'sakit' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $att->status === 'alpha' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($att->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-500">Belum ada riwayat absensi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Salary Payments -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Riwayat Gaji</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-2 text-left">Periode</th>
                                <th class="px-4 py-2 text-center">Total Shift</th>
                                <th class="px-4 py-2 text-right">Total Gaji</th>
                                <th class="px-4 py-2 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employee->salaryPayments as $salary)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ date('F Y', mktime(0, 0, 0, $salary->month, 1, $salary->year)) }}</td>
                                <td class="px-4 py-2 text-center">{{ $salary->total_present }}</td>
                                <td class="px-4 py-2 text-right font-bold text-green-600">
                                    Rp {{ number_format($salary->total_salary, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $salary->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($salary->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada riwayat pembayaran gaji</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection