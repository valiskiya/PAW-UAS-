@extends('layouts.app')

@section('title', 'Laporan Gaji')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Gaji Karyawan</h1>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg print:hidden">
            <i class="fas fa-print mr-2"></i>Cetak
        </button>
    </div>
    
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4 print:hidden">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <select name="month" class="px-4 py-2 border rounded-lg">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                    </option>
                @endfor
            </select>
            
            <select name="year" class="px-4 py-2 border rounded-lg">
                @for($i = date('Y'); $i >= date('Y') - 2; $i--)
                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
            
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>
    
    <!-- Summary -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-center">
            <p class="text-gray-600">Total Gaji Periode {{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}</p>
            <p class="text-4xl font-bold text-green-600 mt-2">Rp {{ number_format($totalSalary, 0, ',', '.') }}</p>
        </div>
    </div>
    
    <!-- Salary Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Karyawan</th>
                    <th class="px-4 py-3 text-center">Strata</th>
                    <th class="px-4 py-3 text-center">Total Hadir</th>
                    <th class="px-4 py-3 text-right">Gaji per Shift</th>
                    <th class="px-4 py-3 text-right">Total Gaji</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salaries as $salary)
                <tr class="border-b">
                    <td class="px-4 py-3 font-semibold">{{ $salary->employee->full_name }}</td>
                    <td class="px-4 py-3 text-center">{{ $salary->salary_grade }}</td>
                    <td class="px-4 py-3 text-center">{{ $salary->total_present }}</td>
                    <td class="px-4 py-3 text-right">Rp {{ number_format($salary->base_salary_per_shift, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-right font-bold text-green-600">Rp {{ number_format($salary->total_salary, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs rounded-full {{ $salary->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($salary->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada data gaji untuk periode ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
@media print {
    .print\:hidden { display: none !important; }
}
</style>
@endsection