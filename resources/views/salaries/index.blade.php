@extends('layouts.app')

@section('title', 'Gaji Karyawan')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800">Gaji Karyawan</h1>
        <a href="{{ route('manajer.salaries.generate') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
            <i class="fas fa-calculator mr-2"></i>Hitung Gaji Bulanan
        </a>
    </div>
    
    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <select name="month" class="px-4 py-2 border rounded-lg">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ request('month', date('n')) == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                    </option>
                @endfor
            </select>
            
            <select name="year" class="px-4 py-2 border rounded-lg">
                @for($i = date('Y'); $i >= date('Y') - 2; $i--)
                    <option value="{{ $i }}" {{ request('year', date('Y')) == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
            
            <select name="status" class="px-4 py-2 border rounded-lg">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
            
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>
    
    <!-- Salary Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left">Karyawan</th>
                    <th class="px-4 py-3 text-center">Periode</th>
                    <th class="px-4 py-3 text-center">Strata</th>
                    <th class="px-4 py-3 text-center">Total Shift</th>
                    <th class="px-4 py-3 text-center">Hadir</th>
                    <th class="px-4 py-3 text-center">Izin</th>
                    <th class="px-4 py-3 text-right">Total Gaji</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salaries as $salary)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold">{{ $salary->employee->full_name }}</td>
                    <td class="px-4 py-3 text-center">{{ \Carbon\Carbon::create()->month($salary->month)->format('M') }} {{ $salary->year }}</td>
                    <td class="px-4 py-3 text-center font-bold">{{ $salary->salary_grade }}</td>
                    <td class="px-4 py-3 text-center">{{ $salary->total_shifts }}</td>
                    <td class="px-4 py-3 text-center text-green-600 font-semibold">{{ $salary->total_present }}</td>
                    <td class="px-4 py-3 text-center text-yellow-600">{{ $salary->total_leave }}</td>
                    <td class="px-4 py-3 text-right font-bold text-green-600">Rp {{ number_format($salary->total_salary, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs rounded-full {{ $salary->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($salary->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($salary->status === 'pending')
                        <form method="POST" action="{{ route('manajer.salaries.pay', $salary->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800" 
                                    onclick="return confirm('Tandai gaji sebagai sudah dibayar?')">
                                <i class="fas fa-money-bill-wave"></i> Bayar
                            </button>
                        </form>
                        @else
                        <span class="text-gray-500 text-sm">{{ $salary->payment_date->format('d/m/Y') }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-8 text-center text-gray-500">Tidak ada data gaji</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $salaries->links() }}
    </div>
</div>
@endsection