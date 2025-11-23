@extends('layouts.app')

@section('title', 'Hitung Gaji Bulanan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('manajer.salaries.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Hitung Gaji Bulanan</h1>
        
        <form method="POST" action="{{ route('manajer.salaries.calculate') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Bulan *</label>
                    <select name="month" class="w-full px-4 py-2 border rounded-lg" required>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $currentMonth == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Tahun *</label>
                    <select name="year" class="w-full px-4 py-2 border rounded-lg" required>
                        @for($i = $currentYear; $i >= $currentYear - 2; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Pilih Karyawan *</label>
                <div class="border rounded-lg p-4 max-h-96 overflow-y-auto">
                    <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                        <input type="checkbox" id="selectAll" class="mr-3" onclick="toggleAll(this)">
                        <span class="font-semibold">Pilih Semua</span>
                    </label>
                    <hr class="my-2">
                    @foreach($employees as $employee)
                    <label class="flex items-center p-2 hover:bg-gray-50 rounded cursor-pointer">
                        <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}" class="mr-3 employee-checkbox">
                        <div class="flex-1">
                            <div class="font-semibold">{{ $employee->full_name }}</div>
                            <div class="text-sm text-gray-600">{{ $employee->employee_code }} - Strata {{ $employee->salary_grade }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Informasi:</strong><br>
                    - Gaji akan dihitung berdasarkan absensi bulan terpilih<br>
                    - Hanya shift dengan status "hadir" yang dihitung<br>
                    - Sistem akan otomatis skip karyawan yang sudah ada data gaji bulan tersebut
                </p>
            </div>
            
            <div class="flex justify-end space-x-3">
                <a href="{{ route('manajer.salaries.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                    <i class="fas fa-calculator mr-2"></i>Hitung Gaji
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function toggleAll(source) {
    const checkboxes = document.querySelectorAll('.employee-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = source.checked);
}
</script>
@endpush
@endsection